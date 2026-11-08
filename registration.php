<?php
require __DIR__ . '/common.php';

$errors = [];

$contactMethods = [
    'Email',
    'Telephone',
    'WhatsApp'
];

$experienceLevels = [
    'First-time volunteer',
    'Some volunteering experience',
    'Experienced volunteer'
];

$availabilityOptions = [
    'Weekday mornings',
    'Weekday afternoons',
    'Weekday evenings',
    'Weekends',
    'Flexible'
];


$defaults = [
    'full_name' => '',
    'email' => '',
    'telephone' => '',
    'age' => '',
    'parish' => '',
    'contact_method' => '',
    'volunteer_experience' => '',
    'availability' => ''
];

$data = $defaults;


if (
    isset($_SESSION['registration'])
    && is_array($_SESSION['registration'])
) {
    $data = array_merge($defaults, $_SESSION['registration']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($defaults) as $field) {
        $data[$field] = input($field);
    }

    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    }

    $requiredFields = [
        'full_name' => 'Full name',
        'email' => 'Email address',
        'telephone' => 'Telephone number',
        'age' => 'Age',
        'parish' => 'Parish'
    ];

    foreach ($requiredFields as $field => $label) {
        if ($data[$field] === '') {
            $errors[] = $label . ' is required.';
        }
    }

    if (
        $data['full_name'] !== ''
        && (
            strlen($data['full_name']) < 3
            || strlen($data['full_name']) > 100
        )
    ) {
        $errors[] = 'Full name must contain 3 to 100 characters.';
    }

    if (
        $data['email'] !== ''
        && (
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            || strlen($data['email']) > 254
        )
    ) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($data['telephone'] !== '') {
    $telephone = $data['telephone'];

    $digits = preg_replace('/\D/', '', $telephone);

    if (
        !preg_match('/^\+?[0-9 ()-]+$/', $telephone)
        || !preg_match('/^(?:1)?(?:876|658)\d{7}$/', $digits)
    ) {
        $errors[] = 'Enter a valid Jamaican telephone number, '
            . 'such as 876-291-0465 or +1-876-291-0465.';
    }
}

    if ($data['age'] !== '') {
        $validAge = filter_var(
            $data['age'],
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 16,
                    'max_range' => 120
                ]
            ]
        );

        if ($validAge === false) {
            $errors[] = 'Age must be a whole number between 16 and 120.';
        }
    }

    if (
        $data['parish'] !== ''
        && !in_array($data['parish'], $parishes, true)
    ) {
        $errors[] = 'Select a parish from the list.';
    }

    // Optional fields must still contain valid choices when supplied.
    if (
        $data['contact_method'] !== ''
        && !in_array($data['contact_method'], $contactMethods, true)
    ) {
        $errors[] = 'Select a listed contact method.';
    }

    if (
        $data['volunteer_experience'] !== ''
        && !in_array(
            $data['volunteer_experience'],
            $experienceLevels,
            true
        )
    ) {
        $errors[] = 'Select a listed volunteering experience level.';
    }

    if (
        $data['availability'] !== ''
        && !in_array(
            $data['availability'],
            $availabilityOptions,
            true
        )
    ) {
        $errors[] = 'Select a listed availability option.';
    }

    if (empty($errors)) {
        $changed = !isset($_SESSION['registration']);

        foreach (array_keys($defaults) as $field) {
            if (
                ($_SESSION['registration'][$field] ?? '')
                !== $data[$field]
            ) {
                $changed = true;
            }
        }

        if ($changed || !isset($data['id'])) {
            $data['id'] = bin2hex(random_bytes(12));

            unset($data['saved_at']);
        }

        $_SESSION['registration'] = $data;

        redirectTo('preferences.php');
    }
}

pageTop('Participant Registration');
?>

<p>
    Register to take part in a community activity.
    Fields marked with an asterisk (*) are required.
</p>

<?php showErrors($errors); ?>

<form method="POST" action="registration.php">
    <?php formToken(); ?>

    <fieldset>
        <legend>Personal Information</legend>

        <label for="full_name">Full Name *</label>
        <input
            type="text"
            id="full_name"
            name="full_name"
            value="<?= e($data['full_name']) ?>"
            minlength="3"
            maxlength="100"
            autocomplete="name"
            required
        >

        <label for="age">Age *</label>
        <input
            type="number"
            id="age"
            name="age"
            value="<?= e($data['age']) ?>"
            min="16"
            max="120"
            step="1"
            required
        >

        <p class="hint">
            Participants must be at least 16 years old.
        </p>

        <label for="parish">Parish *</label>
        <select id="parish" name="parish" required>
            <option value="">Select your parish</option>

            <?php foreach ($parishes as $parish): ?>
                <option
                    value="<?= e($parish) ?>"
                    <?= $data['parish'] === $parish
                        ? 'selected' : '' ?>
                >
                    <?= e($parish) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </fieldset>

    <fieldset>
        <legend>Contact Information</legend>

        <label for="email">Email Address *</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= e($data['email']) ?>"
            maxlength="254"
            autocomplete="email"
            required
        >

        <label for="telephone">Telephone Number *</label>
        <input
            type="tel"
            id="telephone"
            name="telephone"
            value="<?= e($data['telephone']) ?>"
            placeholder="876-555-0100"
            maxlength="30"
            autocomplete="tel"
            required
        >

        <label for="contact_method">
            Preferred Contact Method (optional)
        </label>

        <select id="contact_method" name="contact_method">
            <option value="">No preference</option>

            <?php foreach ($contactMethods as $method): ?>
                <option
                    value="<?= e($method) ?>"
                    <?= $data['contact_method'] === $method
                        ? 'selected' : '' ?>
                >
                    <?= e($method) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <p class="hint">
            If you select WhatsApp, use a telephone number
            that can receive WhatsApp messages.
        </p>
    </fieldset>

    <fieldset>
        <legend>Volunteering Information</legend>

        <label for="volunteer_experience">
            Volunteering Experience (optional)
        </label>

        <select
            id="volunteer_experience"
            name="volunteer_experience"
        >
            <option value="">Prefer not to specify</option>

            <?php foreach ($experienceLevels as $level): ?>
                <option
                    value="<?= e($level) ?>"
                    <?= $data['volunteer_experience'] === $level
                        ? 'selected' : '' ?>
                >
                    <?= e($level) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="availability">
            General Availability (optional)
        </label>

        <select id="availability" name="availability">
            <option value="">Prefer not to specify</option>

            <?php foreach ($availabilityOptions as $option): ?>
                <option
                    value="<?= e($option) ?>"
                    <?= $data['availability'] === $option
                        ? 'selected' : '' ?>
                >
                    <?= e($option) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <p class="hint">
            Your availability helps with planning.
            You will choose your preferred participation date next.
        </p>
    </fieldset>

    <button type="submit">Continue to Activity Selection</button>
</form>

<?php pageBottom(); ?>