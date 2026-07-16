<?php


namespace App\Utility;


class date
{

    public static function day($chooseDay = null)
    {
        $day = 31;
        for ($i = 1; $i <= $day; $i++) {
            if ($chooseDay == $i) {
                ?>
                <option value="<?= $i ?>"><?= $i ?></option>
                <?php
            }
            ?>
            <option value="<?= $i ?>"><?= $i ?></option>
            <?php
        }
    }

    public static function month($chooseMonth = null)
    {
        $day = 12;
        for ($i = 1; $i <= $day; $i++) {
            if ($chooseMonth == $i) {
                ?>
                <option value="<?= $i ?>"><?= $i ?></option>
                <?php
            }
            ?>
            <option value="<?= $i ?>"><?= $i ?></option>
            <?php
        }
    }

    public static function year($chooseYear = null)
    {
        $i = 1300;
        $day = 1500;
        for ($i; $i <= $day; $i++) {
            if ($chooseYear == $i) {
                ?>
                <option value="<?= $i ?>"><?= $i ?></option>
                <?php
            }
            ?>
            <option value="<?= $i ?>"><?= $i ?></option>
            <?php
        }
    }

}
