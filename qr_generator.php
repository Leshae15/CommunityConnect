
<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

// Hide third-party library deprecation notices.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

function generateRegistrationQr($id)
{
    // Validate the registration ID.
    if (!preg_match('/^[a-f0-9]{24}$/', $id)) {
        error_log('QR Error: Invalid registration ID.');
        return false;
    }

    // QR code storage folder.
    $folder = __DIR__ . '/qr_codes';

    // Create the folder if it does not exist.
    if (!is_dir($folder)) {
        if (!mkdir($folder, 0755, true)) {
            error_log('QR Error: Could not create qr_codes folder.');
            return false;
        }
    }

    // Check folder permissions.
    if (!is_writable($folder)) {
        error_log('QR Error: qr_codes folder is not writable.');
        return false;
    }

    // QR image filename.
    $file = $folder . '/' . $id . '.png';

    try {

        $builder = Builder::create()
            ->writer(new PngWriter())
            ->data(
                'https://communityconnect.lovestoblog.com/verify.php?id='
                . urlencode($id)
            )
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10);

        // Generate the QR code.
        $result = $builder->build();

        // Save the QR image.
        $result->saveToFile($file);

        // Confirm that the image was saved.
        if (!is_file($file) || filesize($file) === 0) {
            error_log('QR Error: QR image was not saved correctly.');
            return false;
        }

        return $file;

    } catch (Throwable $e) {

        error_log('QR Error: ' . $e->getMessage());

        return false;
    }
}
