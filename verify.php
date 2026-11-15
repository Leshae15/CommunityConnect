
<?php

require_once __DIR__ . '/common.php';

$registration = null;
$message = '';

$id = trim($_GET['id'] ?? '');

if (!preg_match('/^[a-f0-9]{24}$/', $id)) {

    $message = 'Invalid registration ID.';

} else {

    $file = __DIR__ . '/registration.csv';

    if (is_file($file) && is_readable($file)) {

        $handle = fopen($file, 'r');

        if ($handle !== false) {

            $headers = fgetcsv($handle);

            if ($headers !== false) {

                // Remove spaces and UTF-8 BOM from CSV headings.
                $headers = array_map(function ($header) {
                    return preg_replace(
                        '/^\xEF\xBB\xBF/',
                        '',
                        trim($header)
                    );
                }, $headers);

                // Check that the ID column exists.
                if (in_array('id', $headers, true)) {

                    while (($row = fgetcsv($handle)) !== false) {

                        if (count($row) === count($headers)) {

                            $record = array_combine($headers, $row);

                            if (
                                isset($record['id'])
                                && trim($record['id']) === $id
                            ) {
                                $registration = $record;
                                break;
                            }
                        }
                    }

                } else {
                    $message = 'Registration file has an incorrect format.';
                }
            }

            fclose($handle);
        }

    } else {
        $message = 'Registration records could not be accessed.';
    }

    if ($registration === null && $message === '') {
        $message = 'Registration could not be verified.';
    }
}

pageTop('Registration Verification');
?>

<h2>CommunityConnect Verification</h2>

<?php if ($registration !== null): ?>

    <p class="notice" role="status">
        Registration successfully verified!
    </p>

    <h3>Participant Details</h3>

    <p>
        <strong>Registration ID:</strong>
        <?= e($registration['id']) ?>
    </p>

    <p>
        <strong>Participant:</strong>
        <?= e($registration['full_name']) ?>
    </p>

    <p>
        <strong>Activity:</strong>
        <?= e($registration['activity']) ?>
    </p>

    <p>
        <strong>Participation Date:</strong>
        <?= e($registration['participation_date']) ?>
    </p>

    <p>
        <strong>Status:</strong> Verified
    </p>

<?php else: ?>

    <p role="status">
        <?= e($message) ?>
    </p>

<?php endif; ?>

<p>
    <a href="index.php">Return to CommunityConnect</a>
</p>

<?php pageBottom(); ?>
