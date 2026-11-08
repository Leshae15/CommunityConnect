<?php
require __DIR__ . '/common.php';

if (
    !isset(
        $_SESSION['registration']['activity'],
        $_SESSION['registration']['participation_date'],
        $_SESSION['registration']['skills']
    )
    || !$_SESSION['registration']['skills']
) {
    pageTop('Registration Incomplete');
    ?>
    <p>
        Complete your participant details and select an activity,
        date, and at least one skill.
    </p>

    <a href="preferences.php">Return to Preferences</a>
    <?php
    pageBottom();
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    } elseif (!isset($_SESSION['registration']['saved_at'])) {
        $record = $_SESSION['registration'];
        $record['saved_at'] = date('c');

        if (saveRegistration($record)) {
            $_SESSION['registration'] = $record;

            // Redirect prevents resubmission on refresh.
            redirectTo('confirmation.php');
        } else {
            $errors[] = 'Unable to save your registration. '
                . 'Check that the project folder and CSV file are writable.';
        }
    }
}

$data = $_SESSION['registration'];

$labels = [
    'full_name' => 'Full Name',
    'email' => 'Email Address',
    'telephone' => 'Telephone Number',
    'age' => 'Age',
    'parish' => 'Parish',
    'contact_method' => 'Preferred Contact Method',
    'volunteer_experience' => 'Volunteering Experience',
    'availability' => 'General Availability',
    'activity' => 'Selected Activity',
    'participation_date' => 'Participation Date',
    'skills' => 'Interests and Skills'
];

pageTop('Step 3: Confirmation');

showErrors($errors);
?>

<?php if (isset($data['saved_at'])): ?>
    <p class="notice" role="status">
        Your registration was saved successfully.
    </p>
<?php else: ?>
    <p>Review your details, then select Confirm and Save.</p>
<?php endif; ?>

<dl>
    <?php foreach ($labels as $field => $label): ?>
        <?php
        $value = $data[$field] ?? '';

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        if ($value === '') {
            $value = 'Not specified';
        }
        ?>

        <dt><?= e($label) ?></dt>
        <dd><?= e($value) ?></dd>
    <?php endforeach; ?>
</dl>

<?php if (!isset($data['saved_at'])): ?>
    <form method="POST" action="confirmation.php">
        <?php formToken(); ?>

        <button type="submit">Confirm and Save</button>
    </form>
<?php endif; ?>

<p>
    <a href="preferences.php">Edit Preferences</a>

    <?php if (isAdmin()): ?>
        |
        <a href="records.php">View Saved Registrations</a>
    <?php endif; ?>

    |
    <a href="finish.php">Finish and End Session</a>
</p>

<?php pageBottom(); ?>