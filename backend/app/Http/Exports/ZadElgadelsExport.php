<?php

namespace App\Http\Exports;

use App\Models\ZadElgadel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ZadElgadelsExport implements FromCollection, WithHeadings
{
    protected $zadElgadels;

    public function __construct($zadElgadels = null)
    {
        $this->zadElgadels = $zadElgadels;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $zadElgadels = $this->zadElgadels ?: ZadElgadel::with(['region', 'city', 'translations', 'categories'])->get();

        return $zadElgadels->map(function ($zadElgadel) {

            $categories = '';
            foreach ($zadElgadel->categories ?? [] as $index => $category){
                $categoryModel = \App\Models\CategoryOfZad::where('id',(int)$category)->first();
                $categoryName = $categoryModel ? $categoryModel->name : '';
                $categories.= $index == count($zadElgadel->categories) - 1 ? $categoryName : $categoryName.', ';
            }

            return [
                'id' => $zadElgadel->id,
                'title_ar' => $zadElgadel->translate('ar')->title ?? '',
                'title_en' => $zadElgadel->translate('en')->title ?? '',
                'description_ar' => $zadElgadel->translate('ar')->description ?? '',
                'description_en' => $zadElgadel->translate('en')->description ?? '',
                'region' => $zadElgadel->region->name ?? '',
                'city' => $zadElgadel->city->name ?? '',
//                'latitude' => $zadElgadel->latitude,
//                'longitude' => $zadElgadel->longitude,
//                'phone' => $zadElgadel->phone,
                'categories' => $categories,
                'website_link' => $zadElgadel->website_link,
                'facebook_link' => $zadElgadel->facebook_link,
                'whatsapp' => $zadElgadel->whatsapp,
                'Instagram_link' => $zadElgadel->Instagram_link,
                'active' => $zadElgadel->active ? 'نشط' : 'غير نشط',
                'created_at' => $zadElgadel->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $zadElgadel->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'الرقم التعريفي',
            'العنوان (عربي)',
            'العنوان (إنجليزي)',
            'الوصف (عربي)',
            'الوصف (إنجليزي)',
            'المنطقة',
            'المدينة',
//            'خط العرض',
//            'خط الطول',
//            'الهاتف',
            'الاقسام',
            'الموقع الإلكتروني',
            'فيس بوك',
            'واتس آب',
            'انستجرام',
            'الحالة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
        ];
    }
}
