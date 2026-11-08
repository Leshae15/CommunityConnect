<?php
require __DIR__ . '/common.php';

if (!isset($_SESSION['registration'])) {
    pageTop('Registration Unavailable');
    ?>
    <p>Please enter your participant details first.</p>
    <a href="registration.php">Start Registration</a>
    <?php
    pageBottom();
    exit;
}

$data = $_SESSION['registration'];
$errors = [];
$notice = '';

$rememberedActivity = '';

if (
    isset($_COOKIE['preferred_activity'])
    && is_string($_COOKIE['preferred_activity'])
    && in_array($_COOKIE['preferred_activity'], $activities, true)
) {
    $rememberedActivity = $_COOKIE['preferred_activity'];
}

$activity = $data['activity'] ?? $rememberedActivity;
$participationDate = $data['participation_date'] ?? '';
$selectedSkills = $data['skills'] ?? [];

// Prepare available dates for each activity.
$displayDates = [];

foreach ($activities as $activityName) {
    $displayDates[$activityName] = datesForActivity($activityName);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    } elseif (input('action') === 'clear') {
        // Remove only the selected skills from the session.
        unset($_SESSION['registration']['skills']);

        $selectedSkills = [];

        $notice = 'Your selected skills were cleared. '
            . 'Your participant details remain available.';
    } else {
        $activity = input('activity');
        $participationDate = input('participation_date');

        $selectedSkills = isset($_POST['skills'])
            && is_array($_POST['skills'])
            ? $_POST['skills']
            : [];

        if (!in_array($activity, $activities, true)) {
            $errors[] = 'Select a listed activity.';
        }

        // A date must belong to the chosen activity.
        if (!validActivityDate($activity, $participationDate)) {
            $errors[] = 'Select an available date for your chosen activity.';
        }

        $validSkills = true;

        foreach ($selectedSkills as $skill) {
            if (
                !is_string($skill)
                || !in_array($skill, $skills, true)
            ) {
                $validSkills = false;
            }
        }

        if (empty($selectedSkills) || !$validSkills) {
            $errors[] = 'Select at least one listed interest or skill.';
        }

        if (empty($errors)) {
            $newSkills = array_values(array_unique($selectedSkills));

            $changed = ($data['activity'] ?? '') !== $activity
                || ($data['participation_date'] ?? '')
                    !== $participationDate
                || ($data['skills'] ?? []) !== $newSkills;

            if ($changed && isset($data['saved_at'])) {
                $_SESSION['registration']['id'] =
                    bin2hex(random_bytes(12));

                unset($_SESSION['registration']['saved_at']);
            }

            $_SESSION['registration']['activity'] = $activity;

            $_SESSION['registration']['participation_date'] =
                $participationDate;

            $_SESSION['registration']['skills'] = $newSkills;

            setcookie(
                'preferred_activity',
                $activity,
                [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Lax',
                    'secure' => !empty($_SERVER['HTTPS'])
                        && $_SERVER['HTTPS'] !== 'off'
                ]
            );

            redirectTo('confirmation.php');
        }
    }
}

pageTop('Step 2: Activity and Preferences');
?>

<p>
    Hello, <strong><?= e($data['full_name']) ?></strong>.
    Select an activity to see its scheduled dates.
</p>

<?php if ($rememberedActivity !== ''): ?>
    <p class="notice">
        Remembered activity: <?= e($rememberedActivity) ?>.
        You can change it below.
    </p>
<?php endif; ?>

<?php if ($notice !== ''): ?>
    <p class="notice" role="status"><?= e($notice) ?></p>
<?php endif; ?>

<?php showErrors($errors); ?>

<form method="POST" action="preferences.php">
    <?php formToken(); ?>

    <input type="hidden" name="action" value="continue">

    <label for="activity">Event or Activity</label>
    <select id="activity" name="activity" required>
        <option value="">Select an activity</option>

        <?php foreach ($activities as $choice): ?>
            <option
                value="<?= e($choice) ?>"
                <?= $activity === $choice ? 'selected' : '' ?>
            >
                <?= e($choice) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="participation_date">
        Preferred Participation Date
    </label>

    <select
        id="participation_date"
        name="participation_date"
        aria-describedby="date_help"
        required
    >
        <option value="">Select an available date</option>

        <?php foreach ($displayDates as $name => $dates): ?>
            <optgroup label="<?= e($name) ?>">
                <?php foreach ($dates as $date): ?>
                    <option
                        value="<?= e($date) ?>"
                        <?= $activity === $name
                            && $participationDate === $date
                            ? 'selected' : '' ?>
                    >
                        <?= e(date('l, F j, Y', strtotime($date))) ?>
                    </option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>

    <p id="date_help" class="hint" aria-live="polite">
        Choose a scheduled date for your selected activity.
    </p>

    <noscript>
        <p class="notice">
            JavaScript is disabled. Choose a date under the heading
            matching your activity. PHP will check your selection.
        </p>
    </noscript>

    <fieldset>
        <legend>Interests and Skills — select at least one</legend>

        <?php foreach ($skills as $skill): ?>
            <label class="check">
                <input
                    type="checkbox"
                    name="skills[]"
                    value="<?= e($skill) ?>"
                    <?= in_array($skill, $selectedSkills, true)
                        ? 'checked' : '' ?>
                >
                <?= e($skill) ?>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <p class="hint">
        Your preferred activity will be remembered on this browser
        for 30 days.
    </p>

    <button type="submit" id="review_button">
        Review Registration
    </button>
</form>

<form method="POST" action="preferences.php">
    <?php formToken(); ?>

    <button
        type="submit"
        name="action"
        value="clear"
        class="secondary"
    >
        Clear Session Skills
    </button>
</form>

<a href="registration.php">Back to Participant Details</a>

<script>
const activityDates = <?= json_encode(
    $displayDates,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;

const activitySelect = document.getElementById('activity');
const dateSelect = document.getElementById('participation_date');
const dateHelp = document.getElementById('date_help');
const reviewButton = document.getElementById('review_button');

function updateDates(keepDate = '') {
    const activity = activitySelect.value;
    const dates = activityDates[activity] || [];

    dateSelect.replaceChildren();

    const placeholder = new Option(
        activity ? 'Select an available date' : 'Select an activity first',
        ''
    );

    dateSelect.add(placeholder);

    dates.forEach(function (value) {
        const parts = value.split('-').map(Number);
        const date = new Date(parts[0], parts[1] - 1, parts[2]);

        const label = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        dateSelect.add(new Option(label, value));
    });

    if (dates.includes(keepDate)) {
        dateSelect.value = keepDate;
    }

    dateSelect.disabled = dates.length === 0;
    reviewButton.disabled = dates.length === 0;

    if (!activity) {
        dateHelp.textContent = 'Choose an activity to see its dates.';
    } else if (dates.length === 0) {
        dateHelp.textContent =
            'There are no upcoming dates for this activity.';
    } else {
        dateHelp.textContent =
            'Showing scheduled dates for ' + activity + '.';
    }
}

// Changing activities clears the previously selected date.
activitySelect.addEventListener('change', function () {
    updateDates();
});

// Retain a valid date when returning to this page.
updateDates(dateSelect.value);
</script>

<?php pageBottom(); ?>