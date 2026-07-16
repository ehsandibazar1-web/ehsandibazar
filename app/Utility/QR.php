<?php

namespace App\Utility;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QR
{
    /**
     * Generate a QR code PNG for the given string into public/qr.png.
     */
    public static function QR($string)
    {
        $file = public_path('qr.png');

        QrCode::format('png')->size(300)->generate($string, $file);

        return $file;
    }

    /**
     * Generate a product QR code PNG into public/upload/qr/{string}.png.
     */
    public static function QRCode($string)
    {
        $dir = public_path('upload/qr');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir.'/'.$string.'.png';

        QrCode::format('png')->size(300)->generate(
            env('webSiteAddress').'products/'.$string,
            $file
        );
    }
}
