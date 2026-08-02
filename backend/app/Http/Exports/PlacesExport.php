<?php

namespace App\Http\Exports;

use App\Models\Category;
use App\Models\Place;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlacesExport implements FromCollection, WithHeadings
{
    protected $places;

    public function __construct($places = null)
    {
        $this->places = $places;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $places = $this->places ?: Place::with(['region', 'city', 'translations'])->get();

        return $places->map(function ($place) {
            $seasons = '';
            foreach ($place->seasons_tra as $index => $season) {
                $seasons.= $index == count($place->seasons_tra) - 1 ? $season : $season.', ';
            }

            $categories = '';
            foreach ($place->categories as $index => $category){
                $categoryModel = \App\Models\Category::where('id',$category)->first();
                $categoryName = $categoryModel ? $categoryModel->name : '';
                $categories.= $index == count($place->categories) - 1 ? $categoryName : $categoryName.', ';
            }

            return [
                'id' => $place->id,
                'title_ar' => $place->translate('ar')->title ?? '',
                'title_en' => $place->translate('en')->title ?? '',
                'description_ar' => $place->translate('ar')->description ?? '',
                'description_en' => $place->translate('en')->description ?? '',
                'region' => $place->region->name ?? '',
                'city' => $place->city->name ?? '',
//                'latitude' => $place->latitude,
//                'longitude' => $place->longitude,
                'seasons' => $seasons,
                'categories' => $categories,
                'active' => $place->active ? 'نشط' : 'غير نشط',
                'created_at' => $place->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $place->updated_at->format('Y-m-d H:i:s'),
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
            'افضل المواسم',
            'الاقسام',
//            'خط العرض',
//            'خط الطول',
            'المواسم',
            'الحالة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
        ];
    }
}
