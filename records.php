<?php
require __DIR__ . '/common.php';

// Only a logged-in administrator may view this report.
requireAdmin();

$reportFields = [
    'full_name' => 'Name',
    'email' => 'Email',
    'telephone' => 'Telephone',
    'age' => 'Age',
    'parish' => 'Parish',
    'contact_method' => 'Contact Preference',
    'volunteer_experience' => 'Volunteering Experience',
    'availability' => 'Availability',
    'activity' => 'Activity',
    'participation_date' => 'Participation Date',
    'skills' => 'Skills'
];

// Include existing records and registrations with the new fields.
$files = [
    __DIR__ . '/registrations.csv',
    __DIR__ . '/registrations_extended.csv'
];

$requiredColumns = [
    'id',
    'full_name',
    'email',
    'telephone',
    'age',
    'parish',
    'activity',
    'participation_date',
    'skills'
];

$records = [];
$messages = [];
$foundFile = false;
$skippedRows = 0;

foreach ($files as $path) {
    if (!is_file($path)) {
        continue;
    }

    $foundFile = true;
    $handle = @fopen($path, 'r');

    if ($handle === false) {
        $messages[] = 'One registration file could not be opened.';
        continue;
    }

    if (!flock($handle, LOCK_SH)) {
        fclose($handle);

        $messages[] = 'One registration file is busy. Please try again.';
        continue;
    }

    // Read each file's own column headings.
    $header = fgetcsv($handle, 0, ',', '"', '');

    $validHeader = is_array($header)
        && count(array_diff($requiredColumns, $header)) === 0
        && count(array_unique($header)) === count($header);

    if (!$validHeader) {
        $messages[] = 'One registration file has an unexpected header.';
    } else {
        while (
            ($row = fgetcsv($handle, 0, ',', '"', '')) !== false
        ) {
            // Ignore blank lines.
            if ($row === [null]) {
                continue;
            }

            if (count($row) !== count($header)) {
                $skippedRows++;
                continue;
            }

            $record = array_combine($header, $row);

            if ($record['id'] === '') {
                $skippedRows++;
                continue;
            }

            // If an ID appears in both files, use the extended record.
            $records[$record['id']] = $record;
        }
    }

    flock($handle, LOCK_UN);
    fclose($handle);
}

pageTop('Administrator Registration Report');
?>

<p>
    You are signed in as the administrator.
    Saved participant information is displayed below.
</p>

<?php showErrors($messages); ?>

<?php if (!$foundFile): ?>
    <p class="notice">
        No registration file is available yet.
        Save a registration to create it.
    </p>
<?php endif; ?>

<?php if ($skippedRows > 0): ?>
    <p class="notice">
        Some incomplete or incorrectly formatted records could not
        be displayed.
    </p>
<?php endif; ?>

<div class="table-wrap">
    <table>
        <caption>Saved Participant Registrations</caption>

        <thead>
            <tr>
                <?php foreach ($reportFields as $label): ?>
                    <th scope="col"><?= e($label) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <?php foreach ($reportFields as $field => $label): ?>
                        <?php
                        $value = $record[$field] ?? '';

                        if ($value === '') {
                            $value = 'Not specified';
                        }
                        ?>

                        <td><?= e($value) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($records)): ?>
                <tr>
                    <td colspan="<?= count($reportFields) ?>">
                        No saved registrations are available.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<p>
    Total saved registrations:
    <strong><?= count($records) ?></strong>
</p>

<p class="hint">
    Older registrations may show “Not specified” for fields
    that were added later.
</p>

<p>
    <a href="registration.php">Return to Registration</a>
    |
    <a href="admin_logout.php">Log Out of Administrator Account</a>
</p>

<?php pageBottom(); ?>