<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SliderSeeder extends Seeder
{
    /**
     * Minimal valid PNG (1×1) for demo media when no asset is available.
     */
    private const PLACEHOLDER_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    public function run(): void
    {
        if (Slider::query()->exists()) {
            return;
        }

        $slides = [
            [
                'order_id' => 0,
                'active' => true,
                'link' => 'https://example.com/places',
                'titles' => [
                    'ar' => 'اكتشف أجمل الوجهات السياحية',
                    'en' => 'Discover the best travel destinations',
                    'ru' => 'Откройте лучшие туристические направления',
                    'zh' => '探索最佳旅游目的地',
                ],
            ],
            [
                'order_id' => 1,
                'active' => true,
                'link' => 'https://example.com/events',
                'titles' => [
                    'ar' => 'رحلاتك تبدأ من هنا',
                    'en' => 'Your journey starts here',
                    'ru' => 'Ваше путешествие начинается здесь',
                    'zh' => '您的旅程从这里开始',
                ],
            ],
            [
                'order_id' => 2,
                'active' => true,
                'link' => 'https://example.com/stores',
                'titles' => [
                    'ar' => 'دليلك لأفضل التجارب',
                    'en' => 'Your guide to great experiences',
                    'ru' => 'Ваш гид к лучшим впечатлениям',
                    'zh' => '精彩体验指南',
                ],
            ],
        ];

        foreach ($slides as $slide) {
            $titles = $slide['titles'];
            unset($slide['titles']);

            $slider = Slider::withoutEvents(function () use ($slide, $titles) {
                $model = Slider::create([
                    'order_id' => $slide['order_id'],
                    'active' => $slide['active'],
                    'link' => $slide['link'] ?? null,
                ]);
                foreach ($titles as $locale => $title) {
                    $model->translateOrNew($locale)->title = $title;
                }
                $model->save();

                return $model;
            });

            $this->attachPlaceholderImage($slider);
        }
    }

    private function attachPlaceholderImage(Slider $slider): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'slider_seed_');
        if ($tmp === false) {
            return;
        }

        File::put($tmp, base64_decode(self::PLACEHOLDER_PNG_BASE64));

        try {
            $slider->clearMediaCollection('image');
            $slider->addMedia($tmp)
                ->usingFileName('slider-seed-' . $slider->id . '.png')
                ->toMediaCollection('image');
        } finally {
            if (File::exists($tmp)) {
                @unlink($tmp);
            }
        }
    }
}
