<?php
require __DIR__ . '/common.php';

header('Cache-Control: no-store, private');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    } else {
        unset($_SESSION['admin_logged_in']);

        session_regenerate_id(true);

        $_SESSION['token'] = bin2hex(random_bytes(24));

        redirectTo('admin_login.php');
    }
}

pageTop('Administrator Logout');

showErrors($errors);
?>

<?php if (isAdmin()): ?>
    <p>Log out to prevent further access to participant records.</p>

    <form method="POST" action="admin_logout.php">
        <?php formToken(); ?>

        <button type="submit">Confirm Admin Logout</button>
    </form>

    <a href="records.php">Return to Records</a>
<?php else: ?>
    <p>You are not signed in as an administrator.</p>
    <a href="admin_login.php">Administrator Login</a>
<?php endif; ?>

<?php pageBottom(); ?>