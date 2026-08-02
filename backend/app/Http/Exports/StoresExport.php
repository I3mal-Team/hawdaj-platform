<?php

namespace App\Http\Exports;

use App\Models\Store;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoresExport implements FromCollection, WithHeadings
{
    protected $stores;

    public function __construct($stores = null)
    {
        $this->stores = $stores;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $stores = $this->stores ?: Store::with(['region', 'city', 'translations'])->get();

        return $stores->map(function ($store) {

            $categories = '';
            foreach ($store->categories as $index => $category){
                $categoryModel = \App\Models\CategoryOfStore::where('id',$category)->first();
                $categoryName = $categoryModel ? $categoryModel->name : '';
                $categories.= $index == count($store->categories) - 1 ? $categoryName : $categoryName.', ';
            }
            return [
                'id' => $store->id,
                'title_ar' => $store->translate('ar')->title ?? '',
                'title_en' => $store->translate('en')->title ?? '',
                'description_ar' => $store->translate('ar')->description ?? '',
                'description_en' => $store->translate('en')->description ?? '',
//                'region' => $store->region->name ?? '',
//                'city' => $store->city->name ?? '',
//                'latitude' => $store->latitude,
//                'longitude' => $store->longitude,
//                'phone' => $store->phone,
                'categories' => $categories,
                'website_link' => $store->website_link,
                'facebook_link' => $store->facebook_link,
                'Instagram_link' => $store->Instagram_link,
                'whatsapp' => $store->whatsapp,
                'active' => $store->active ? 'نشط' : 'غير نشط',
                'created_at' => $store->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $store->updated_at->format('Y-m-d H:i:s'),
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
//            'المنطقة',
//            'المدينة',
//            'خط العرض',
//            'خط الطول',
//            'الهاتف',
            'الاقسام',
            'رابط الموقع',
            'فيس بوك',
            'انستجرام',
            'واتس آب',
            'الحالة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
        ];
    }
}
