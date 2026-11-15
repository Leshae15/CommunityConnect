
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
//Davanea Morsby- Datatbase connection function
function dbConnect(){
            $config = require __DIR__ . '/dbConfig.php';

            // Return false instead of stopping the page with a fatal error.
            mysqli_report(MYSQLI_REPORT_OFF);
            $connection = @mysqli_connect(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database']
            );

            if ($connection === false) {
                return false;
            } 
            mysqli_set_charset($connection, 'utf8mb4');
            return $connection;
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
            error_log('CommunityConnect: Missing or invalid field: ' . $field);
            return false;
        }
    }

    if (!in_array($record['activity'], $activities, true)) {
        error_log('CommunityConnect: Invalid activity.');
        return false;
    }

    if (!validActivityDate(
        $record['activity'],
        $record['participation_date']
    )) {
        error_log('CommunityConnect: Invalid activity date.');
        return false;
    }

    if (
        !isset($record['skills'])
        || !is_array($record['skills'])
        || empty($record['skills'])
    ) {
        error_log('CommunityConnect: Invalid skills.');
        return false;
    }

    foreach ($record['skills'] as $skill) {
        if (!is_string($skill) || !in_array($skill, $skills, true)) {
            error_log('CommunityConnect: Invalid skill.');
            return false;
        }
    }

    //Davanea Morsby- Connect to the database and save the record.
    $connection = dbConnect();

    if ($connection === false) {
        return false;
    }

    $statement = mysqli_prepare(
        $connection,
        'INSERT INTO registrations '
        . '(id, saved_at, full_name, email, telephone, age, parish, activity, '
        . 'participation_date, skills, contact_method, volunteer_experience, availability) '
        . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    // The statement fails to prepare if the registrations table is missing.
    if ($statement === false) {
        mysqli_close($connection);
        return false;
    }

    $savedAt = date('Y-m-d H:i:s', strtotime($record['saved_at'] ));
    $age = (int) $record['age'];
    $skillsList = implode(',', $record['skills']);

    //Optional fields left blank if not provided.
    $contactMethod = ($record['contact_method'] ?? '') ?: '';
    $experience = ($record['volunteer_experience'] ?? '') ?: '';
    $availability = ($record['availability'] ?? '') ?: '';

    mysqli_stmt_bind_param(
        $statement, 
        "sssssisssssss",
        $record['id'],
        $savedAt,
        $record['full_name'],
        $record['email'],
        $record['telephone'],
        $age,
        $record['parish'],
        $record['activity'],
        $record['participation_date'],
        $skillsList,
        $contactMethod,
        $experience,
        $availability   
    );

    $success = mysqli_stmt_execute($statement);

    //ERROR 1062 MEANS THIS REGISTRATION ID WAS ALREADY SAVED, SO WE RETURN TRUE.
    if (!$success && mysqli_stmt_errno($statement) === 1062) {
        $success = true;
        }
        
        mysqli_stmt_close($statement);
        mysqli_close($connection);
        return $success;
    }