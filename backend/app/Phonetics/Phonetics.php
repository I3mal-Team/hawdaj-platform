<?php

namespace App\Phonetics;


class Phonetics
{

    /**
     * @param $string
     * @param string $flag
     * @return array|string|string[]
     */
    public function convertLetters($string, $flag = 'ar')
    {
        $arabic = array('ا', 'ب', 'ح', 'د', 'ر', 'س', 'ص', 'ط', 'ع', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي');
        $english = array('A', 'B', 'J', 'D', 'R', 'S', 'X', 'T', 'E', 'G', 'K', 'L', 'Z', 'N', 'H', 'U', 'V', 'V');


        if ($flag == 'ar') {
            $result = str_replace($english, $arabic, $string);
            return $this->isEnglish($string) ? utf8_strrev($result) : $result;
        } else {
            $result = str_replace($arabic, $english, $string);
            return $this->isArabic($string) ? utf8_strrev($result) : $result;
        }
    }

    /**
     * @param $string
     * @param string $flag
     * @return array|string|string[]
     */
    public function convertNumbers($string, $flag = 'ar')
    {
        $english = range(0, 9);
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');

        if ($flag == 'ar') {
            return str_replace($english, $arabic, $string);
        } else {
            return str_replace($arabic, $english, $string);
        }
    }

    /**
     * @param $string
     * @return bool
     */
    public function isArabic($string)
    {
        $arabic = ['ا', 'ب', 'ح', 'د', 'ر', 'س', 'ص', 'ط', 'ع', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];


        $string = array_filter(mb_str_split($string), function ($item) {
            if ($item != ' ' && $item != '.') {
                return $item;
            };
        });

        $result = count($string);

        array_map(function ($item) use ($arabic, &$result) {
            if (in_array($item, $arabic)) {
                $result--;
            }

        }, $string);

        return $result == 0;
    }

    /**
     * @param $string
     * @return bool
     */
    public function isEnglish($string)
    {
        $english = ['A', 'B', 'J', 'D', 'R', 'S', 'X', 'T', 'E', 'G', 'K', 'L', 'Z', 'N', 'H', 'U', 'V', 'V'] + range(0, 9);

        $string = array_filter(str_split($string), function ($item) {
            if ($item != ' ' && $item != '.') {
                return $item;
            }
        });

        $result = count($string);

        array_map(function ($item) use ($english, &$result) {
            if (in_array($item, $english)) {
                $result--;
            }

        }, $string);

        return $result == 0;

    }
}
