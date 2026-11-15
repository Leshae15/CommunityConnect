<?php
require __DIR__ . '/common.php';

header('Cache-Control: no-store, private');

if (isAdmin()) {
    redirectTo('records.php');
}

$config = require __DIR__ . '/admin_config.php';

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = input('username');

    // Do not trim passwords because spaces may be intentional.
    $password = isset($_POST['password'])
        && is_string($_POST['password'])
        ? $_POST['password']
        : '';

    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    } elseif (
        isset($_SESSION['admin_retry_after'])
        && time() < $_SESSION['admin_retry_after']
    ) {
        $errors[] = 'Too many unsuccessful attempts. '
            . 'Please wait one minute before trying again.';
    } else {
        $correctUsername = hash_equals(
            $config['username'],
            $username
        );

        $correctPassword = password_verify(
            $password,
            $config['password_hash']
        );

        if ($correctUsername && $correctPassword) {
            // Change the session ID after successful authentication.
            session_regenerate_id(true);

            $_SESSION['admin_logged_in'] = true;

            unset(
                $_SESSION['admin_attempts'],
                $_SESSION['admin_retry_after']
            );

            $_SESSION['token'] = bin2hex(random_bytes(24));

            redirectTo('records.php');
        } else {
            $_SESSION['admin_attempts'] =
                ($_SESSION['admin_attempts'] ?? 0) + 1;

            if ($_SESSION['admin_attempts'] >= 5) {
                $_SESSION['admin_retry_after'] = time() + 60;
                $_SESSION['admin_attempts'] = 0;
            }

            $errors[] = 'Incorrect administrator username or password.';
        }
    }
}

pageTop('Administrator Login');
?>

<p>
    Only the administrator can view saved participant registrations.
</p>

<?php showErrors($errors); ?>

<form method="POST" action="admin_login.php">
    <?php formToken(); ?>

    <label for="username">Username</label>
    <input
        type="text"
        id="username"
        name="username"
        value="<?= e($username) ?>"
        autocomplete="username"
        maxlength="100"
        required
    >

    <label for="password">Password</label>
    <input
        type="password"
        id="password"
        name="password"
        autocomplete="current-password"
        required
    >

    <button type="submit">Log In</button>
</form>

<a href="registration.php">Return to Registration</a>

<?php pageBottom(); ?>