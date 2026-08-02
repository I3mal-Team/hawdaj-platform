<?php

namespace App\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;

trait HasSettingTranslationUpdate
{

    public function updateTranslation($value)
    {
        $value_ar = "";
        $value_en = "";
        $value_ru = "";
        $value_zh = "";
        if($value){
            $value_ar = GoogleTranslate::trans($value, "ar");
            $value_en = GoogleTranslate::trans($value, "en");
            $value_ru = GoogleTranslate::trans($value, "ru");
            $value_zh = GoogleTranslate::trans($value, "zh");    
        }

        $this->translateOrNew('ar')->value = $value_ar;
        $this->translateOrNew('en')->value = $value_en;
        $this->translateOrNew('ru')->value = $value_ru;
        $this->translateOrNew('zh')->value = $value_zh;
        $this->save();
    }

}
