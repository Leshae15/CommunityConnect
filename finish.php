<?php
require __DIR__ . '/common.php';

$finished = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validPost()) {
        $errors[] = 'Your form expired. Please submit it again.';
    } else {
        // Remove all session variables.
        session_unset();

        // Expire the PHP session cookie.
        if (ini_get('session.use_cookies')) {
            $parameters = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $parameters['path'],
                $parameters['domain'],
                $parameters['secure'],
                $parameters['httponly']
            );
        }

        // Destroy the session itself.
        session_destroy();

        $finished = true;
    }
}

pageTop($finished ? 'Session Ended' : 'Finish Your Session');

showErrors($errors);
?>

<?php if ($finished): ?>
    <p class="notice">
        Your session has ended successfully.
        Saved registrations remain in the report.
    </p>

    <p>
        Your non-sensitive activity preference remains available
        for your next visit.
    </p>

    <a href="registration.php">Start a New Registration</a>
<?php else: ?>
    <p>
        Ending your session clears your current participant details.
        Unsaved information will be lost.
    </p>

    <form method="POST" action="finish.php">
        <?php formToken(); ?>

        <button type="submit">End Session</button>
    </form>

    <a href="confirmation.php">Return to Confirmation</a>
<?php endif; ?>

<?php pageBottom(); ?>