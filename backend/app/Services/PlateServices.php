<?php

namespace App\Services;

use App\Phonetics\Phonetics;
use Illuminate\Support\Str;

class PlateServices
{

    public Phonetics $phonetic;

    public function __construct()
    {
        $this->phonetic = new Phonetics();
    }

    /**
     * @param $plateNumber
     * @return array
     */
    public function resolvePlate($plateNumber)
    {
        $plate_status = 'error';
        $plate_en = Str::after($plateNumber, 'lower:');
        $plate_ar = Str::after(Str::before($plateNumber, 'lower:'), 'upper:');

        if ($plate_en == '' && $plate_ar == '') {
            return false;
        }

        $plate_char = $this->getChar($plate_ar, $plate_en);
        $plate_number = $this->getNumber($plate_ar, $plate_en);

        if ($plate_char['char_status'] == true && $plate_number['number_status'] == true) {
            $plate_status = 'success';
        }

        $plate_fn_ar = $plate_char['char_ar'] . ' ' . $plate_number['number_ar'];
        $plate_fn_en = $plate_number['number_en'] . ' ' . $plate_char['char_en'];

        $plate = "upper: " . str_replace(' ', '.', $plate_fn_ar) . " lower: " . str_replace(' ', '.', $plate_fn_en);

        return [
            'plate_ar' => [
                'char' => $plate_char['char_ar'],
                'number' => utf8_strrev($plate_number['number_ar']),
                'plate' => $plate_char['char_ar'] . ' ' . utf8_strrev($plate_number['number_ar']),
            ],
            'plate_en' => [
                'char' => $plate_char['char_en'],
                'number' => $plate_number['number_en'],
                'plate' => $plate_fn_en,
            ],
            'status' => $plate_status,
            'char_status' => $plate_char['char_status'],
            'number_status' => $plate_number['number_status'],
            'plate' => $plate
        ];
    }

    /**
     * @param $plate_ar
     * @param $plate_en
     * @return array
     */
    public function getChar($plate_ar, $plate_en): array
    {
        $plate_char_ar = trim(preg_replace("/[0-9,]/", "", $this->phonetic->convertNumbers($plate_ar, 'en')));
        $plate_char_en = trim(preg_replace("/[0-9,]/", "", $plate_en));

        $char_ar = str_replace(['.', ' '], "", $this->phonetic->convertLetters($plate_char_ar, 'en'));
        $char_en = str_replace(['.', ' '], "", $plate_char_en);

        return $this->resolveChar($char_ar, $char_en);
    }


    /**
     * @param $plate_ar
     * @param $plate_en
     * @return array
     */
    public function getNumber($plate_ar, $plate_en): array
    {
        $plate_number_ar = trim(preg_replace("/[aA-zZ,]/", "", $this->phonetic->convertLetters($plate_ar, 'en')));
        $plate_number_en = trim(preg_replace("/[aA-zZ,]/", "", $plate_en));

        $number_ar = str_replace(['.', ' '], "", $this->phonetic->convertNumbers($plate_number_ar, 'en'));
        $number_en = str_replace(['.', ' '], "", $plate_number_en);

        return $this->resolveNumber($number_ar, $number_en);
    }

    /**
     * @param $char_ar
     * @param $char_en
     * @return array
     */
    public function resolveChar($char_ar, $char_en)
    {
        $char_status = false;
        $char_ar_len = mb_strlen($char_ar);
        $char_en_len = mb_strlen($char_en);

        if ($char_ar_len == 3 || $char_en_len == 3) {

            if ($char_ar_len != 3 && $char_en_len == 3) {
                $char_ar = $char_en;

            } elseif ($char_ar_len == 3 && $char_en_len != 3) {
                $char_en = $char_ar;

            }

            $char_status = true;
        }

        $char_ar = implode(' ', str_split($char_ar));
        $char_en = implode(' ', str_split($char_en));

        return [
            'char_ar' => $this->phonetic->convertLetters($char_ar),
            'char_en' => $char_en,
            'char_status' => $char_status
        ];
    }

    /**
     * @param $number_ar
     * @param $number_en
     * @return array
     */
    public function resolveNumber($number_ar, $number_en): array
    {
        $number_status = false;
        $number_ar_len = mb_strlen($number_ar);
        $number_en_len = mb_strlen($number_en);


        if (($number_ar_len >= 1 && $number_ar_len <= 4) || ($number_en_len >= 1 && $number_en_len <= 4)) {

            if (($number_ar_len > $number_en_len && $number_ar_len <= 4) || $number_en_len > 4) {

                $number_en = $number_ar;

            } elseif (($number_ar_len < $number_en_len && $number_en_len <= 4) || $number_ar_len > 4) {

                $number_ar = $number_en;
            }

            $number_status = true;
        }
        $number_ar = implode(' ', str_split($number_ar));
        $number_en = implode(' ', str_split($number_en));

        return [
            'number_ar' => $this->phonetic->convertNumbers($number_ar),
            'number_en' => $number_en,
            'number_status' => $number_status
        ];
    }

}
