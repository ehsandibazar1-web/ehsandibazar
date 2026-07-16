<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 03/16/2019
 * Time: 12:25 PM
 */

namespace App\Utility;


use LaravelQRCode\Facades\QRCode;

class QR
{
    public static function QR($string)
    {
        $file = public_path('qr.png');

        // Personal Information
        $firstName = 'John';
        $lastName = 'Doe';
        $title = 'Mr.';
        $email = 'john.doe@example.com';

        // Addresses
        $homeAddress = [
            'type' => 'home',
            'pref' => true,
            'street' => '123 my street st',
            'city' => 'My Beautiful Town',
            'state' => 'LV',
            'country' => 'Neverland',
            'zip' => '12345-678'
        ];
        $wordAddress = [
            'type' => 'work',
            'pref' => false,
            'street' => '123 my work street st',
            'city' => 'My Dreadful Town',
            'state' => 'LV',
            'country' => 'Hell',
            'zip' => '12345-678'
        ];

        $addresses = [$homeAddress, $wordAddress];

        // Phones
        $workPhone = [
            'type' => 'work',
            'number' => '001 555-1234',
            'cellPhone' => false
        ];
        $homePhone = [
            'type' => 'home',
            'number' => '001 555-4321',
            'cellPhone' => false
        ];
        $cellPhone = [
            'type' => 'work',
            'number' => '001 9999-8888',
            'cellPhone' => true
        ];

        $phones = [$workPhone, $homePhone, $cellPhone];

        /* return QRCode::vCard($firstName, $lastName, $title, $email, $addresses, $phones)
             ->setErrorCorrectionLevel('H')
             ->setSize(4)
             ->setMargin(2)
             ->svg();*/

        return QRCode::URL($string)->setOutFile($file)->png();
    }

    public static function QRCode($string)
    {
        /* update Qr-code */
        $file = public_path()."/upload/qr/".$string.".png";
        QRCode::URL(env('webSiteAddress')."products/".$string)->setOutFile($file)->png();
    }
}
