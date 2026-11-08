<?php
session_start();

date_default_timezone_set('America/Jamaica');

// Indexed array of activities.
$activities = [
    'Beach Cleanup',
    'Food Drive',
    'Community Gardening',
    'Youth Mentoring'
];

// Associative array: each activity has its own scheduled dates.
$activityDates = [
    'Beach Cleanup' => [
        '2026-10-24',
        '2027-01-09',
        '2027-04-10',
        '2027-07-10',
        '2027-10-09'
    ],

    'Food Drive' => [
        '2026-11-14',
        '2026-12-19',
        '2027-05-08',
        '2027-09-11',
        '2027-12-18'
    ],

    'Community Gardening' => [
        '2026-10-31',
        '2027-02-13',
        '2027-06-12',
        '2027-08-14',
        '2027-11-13'
    ],

    'Youth Mentoring' => [
        '2026-11-28',
        '2026-12-12',
        '2027-03-13',
        '2027-10-23',
        '2027-12-11'
    ]
];

$parishes = [
    'Kingston',
    'St. Andrew',
    'St. Catherine',
    'Clarendon',
    'Manchester',
    'St. Elizabeth',
    'Westmoreland',
    'Hanover',
    'St. James',
    'Trelawny',
    'St. Ann',
    'St. Mary',
    'Portland',
    'St. Thomas'
];

$skills = [
    'Gardening',
    'Teaching',
    'Cooking',
    'Organising',
    'First Aid',
    'Event Planning',
    'Fundraising',
    'Childcare',
    'Youth Mentoring',
    'Elderly Support',
    'Computer Support',
    'Photography',
    'Arts and Crafts',
    'Community Outreach',
    'General Volunteering'
];

$columns = [
    'id',
    'saved_at',
    'full_name',
    'email',
    'telephone',
    'age',
    'parish',
    'activity',
    'participation_date',
    'skills',
    'contact_method',
    'volunteer_experience',
    'availability'
];

// Escape text before displaying it in HTML.
function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Read a submitted text field.
function input($name)
{
    return isset($_POST[$name]) && is_string($_POST[$name])
        ? trim($_POST[$name])
        : '';
}

function redirectTo($page)
{
    header('Location: ' . $page);
    exit;
}

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(24));
}

function formToken()
{
    echo '<input type="hidden" name="token" value="'
        . e($_SESSION['token']) . '">';
}

function validPost()
{
    return isset($_POST['token'])
        && is_string($_POST['token'])
        && hash_equals($_SESSION['token'], $_POST['token']);
}

function isAdmin()
{
    return isset($_SESSION['admin_logged_in'])
        && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin()
{
    header('Cache-Control: no-store, private');

    if (!isAdmin()) {
        redirectTo('admin_login.php');
    }
}

// Return future or current dates for one activity.
function datesForActivity($activity)
{
    global $activityDates;

    $dates = [];

    foreach ($activityDates[$activity] ?? [] as $date) {
        if ($date >= date('Y-m-d')) {
            $dates[] = $date;
        }
    }

    return $dates;
}

// Check that the date belongs to the selected activity.
function validActivityDate($activity, $date)
{
    return in_array($date, datesForActivity($activity), true);
}

function showErrors($errors)
{
    if (empty($errors)) {
        return;
    }

    echo '<div class="error" role="alert">';
    echo '<strong>Please correct the following:</strong><ul>';

    foreach ($errors as $error) {
        echo '<li>' . e($error) . '</li>';
    }

    echo '</ul></div>';
}

function pageTop($title)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >

        <title><?= e($title) ?> | CommunityConnect</title>

        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <h1>CommunityConnect</h1>
            <p>Community Service and Event Registration Portal</p>
        </header>

        <nav aria-label="Main navigation">
            <a href="registration.php">Register</a>
            <a href="preferences.php">Preferences</a>
            <a href="confirmation.php">My Confirmation</a>

            <?php if (isAdmin()): ?>
                <a href="records.php">View Records</a>
                <a href="admin_logout.php">Admin Logout</a>
            <?php else: ?>
                <a href="admin_login.php">Admin Login</a>
            <?php endif; ?>

            <a href="finish.php">End Session</a>
        </nav>

        <main>
            <h2><?= e($title) ?></h2>
    <?php
}

function pageBottom()
{
    ?>
        </main>

        <footer>
            CommunityConnect &copy; <?= date('Y') ?>
        </footer>
    </body>
    </html>
    <?php
}

function saveRegistration($record)
{
    global $columns, $activities, $skills;

    $required = [
        'id',
        'saved_at',
        'full_name',
        'email',
        'telephone',
        'age',
        'parish',
        'activity',
        'participation_date'
    ];

    foreach ($required as $field) {
        if (
            !isset($record[$field])
            || !is_scalar($record[$field])
            || trim((string) $record[$field]) === ''
        ) {
            return false;
        }
    }

    if (!in_array($record['activity'], $activities, true)) {
        return false;
    }

    // Validate the activity/date combination again before saving.
    if (
        !validActivityDate(
            $record['activity'],
            $record['participation_date']
        )
    ) {
        return false;
    }

    if (
        !isset($record['skills'])
        || !is_array($record['skills'])
        || empty($record['skills'])
    ) {
        return false;
    }

    foreach ($record['skills'] as $skill) {
        if (!is_string($skill) || !in_array($skill, $skills, true)) {
            return false;
        }
    }

    $handle = @fopen(
        __DIR__ . '/registrations_extended.csv',
        'c+'
    );

    if ($handle === false) {
        return false;
    }

    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        return false;
    }

    rewind($handle);

    $header = fgetcsv($handle, 0, ',', '"', '');

    if ($header !== false && $header !== $columns) {
        flock($handle, LOCK_UN);
        fclose($handle);
        return false;
    }

    // Prevent duplicate saves of the same registration.
    while (
        ($existing = fgetcsv($handle, 0, ',', '"', '')) !== false
    ) {
        if (($existing[0] ?? '') === $record['id']) {
            flock($handle, LOCK_UN);
            fclose($handle);
            return true;
        }
    }

    fseek($handle, 0, SEEK_END);

    $success = true;

    if ($header === false) {
        $success = fputcsv(
            $handle,
            $columns,
            ',',
            '"',
            ''
        ) !== false;
    }

    $row = [];

    foreach ($columns as $column) {
        $value = $column === 'skills'
            ? implode('; ', $record[$column] ?? [])
            : (string) ($record[$column] ?? '');

        // Prevent spreadsheet formula interpretation.
        if (preg_match('/^[=+@\-\t\r\n]/', $value)) {
            $value = "'" . $value;
        }

        $row[] = $value;
    }

    if ($success) {
        $success = fputcsv(
            $handle,
            $row,
            ',',
            '"',
            ''
        ) !== false;
    }

    if ($success) {
        $success = fflush($handle);
    }

    flock($handle, LOCK_UN);
    fclose($handle);

    return $success;
}