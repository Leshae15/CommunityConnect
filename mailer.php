
<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/vendor/autoload.php';

function sendRegistrationEmail($record, $qrFile)
{
    $config = require __DIR__ . '/mail_config.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['port'];

        $mail->setFrom($config['username'], 'CommunityConnect');
        $mail->addAddress($record['email'], $record['full_name']);

        $mail->isHTML(true);
        $mail->Subject = 'CommunityConnect Registration Confirmation';

        $name = htmlspecialchars($record['full_name'], ENT_QUOTES, 'UTF-8');
        $id = htmlspecialchars($record['id'], ENT_QUOTES, 'UTF-8');


	$mail->isHTML(true);

	$name = htmlspecialchars($record['full_name'], ENT_QUOTES, 'UTF-8');
	$activity = htmlspecialchars($record['activity'], ENT_QUOTES, 'UTF-8');
	$date = htmlspecialchars($record['participation_date'], ENT_QUOTES, 'UTF-8');
	$id = htmlspecialchars($record['id'], ENT_QUOTES, 'UTF-8');

	$skills = $record['skills'] ?? [];

	if (is_array($skills)) {
		$skills = implode(', ', $skills);
	}

	$skills = htmlspecialchars($skills, ENT_QUOTES, 'UTF-8');

	$mail->Subject = 'Welcome to CommunityConnect, ' . $record['full_name'] . '!';

	$mail->Body = "
		<div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; padding: 20px;'>

			<h2 style='color: #39745A;'>Welcome to CommunityConnect!</h2>

			<p>Hi $name,</p>

			<p>
				Thank you for choosing to make a difference in your community!
				We're excited to have you join us as a volunteer.
			</p>

			<p>
				Your registration has been successfully completed.
				Here is a summary of your volunteer registration:
			</p>

			<div style='background: #F3F7F3; padding: 15px; border-radius: 8px;'>
				<p><strong>Registration ID:</strong> $id</p>
				<p><strong>Selected Activity:</strong> $activity</p>
				<p><strong>Participation Date:</strong> $date</p>
				<p><strong>Your Skills:</strong> $skills</p>
			</div>

			<p>
				We've attached your personal registration QR code.
				Please keep it safe for your records.
			</p>

			<p>
				Every helping hand makes a difference.
				We look forward to having you involved!
			</p>

			<p>
				Warm regards,<br>
				<strong>CommunityConnect Team</strong>
			</p>

		</div>
	";

	$mail->AltBody =
		"Hi " . $record['full_name'] . "!\n\n"
		. "Welcome to CommunityConnect. Thank you for volunteering!\n\n"
		. "Activity: " . $record['activity'] . "\n"
		. "Participation Date: " . $record['participation_date'] . "\n"
		. "Skills: " . $skills . "\n"
		. "Registration ID: " . $record['id'] . "\n\n"
		. "Your QR code is attached.\n\n"
		. "Warm regards,\nCommunityConnect Team";


        if ($qrFile && is_file($qrFile)) {
            $mail->addAttachment($qrFile, 'CommunityConnect-QR.png');
        }

        $mail->send();

        return true;

    } catch (Throwable $e) {
        error_log('Email Error: ' . $e->getMessage());
        return false;
    }
}
