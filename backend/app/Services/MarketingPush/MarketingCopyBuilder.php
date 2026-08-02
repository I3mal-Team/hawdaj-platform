<?php

namespace App\Services\MarketingPush;

use App\Models\Guide;
use App\Models\Menu;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class MarketingCopyBuilder
{
    public function greetingName(User $user): string
    {
        $name = trim((string) $user->first_name);

        return $name !== '' ? $name : 'صديقنا';
    }

    public function resolveSpotLabel(Model $model, string $type): string
    {
        if (in_array($type, ['place', 'store', 'event', 'zad'], true)) {
            $model->loadMissing('city');
            if ($model->city) {
                return $this->cityArabicName($model->city);
            }
        }

        if ($type === 'menu' && $model instanceof Menu) {
            $model->loadMissing('zad.city');
            if ($model->zad && $model->zad->city) {
                return $this->cityArabicName($model->zad->city);
            }
        }

        if ($type === 'offer' && $model instanceof Offer) {
            $model->loadMissing('menu.zad.city');
            $city = $model->menu?->zad?->city;
            if ($city) {
                return $this->cityArabicName($city);
            }
        }

        if ($type === 'guide' && $model instanceof Guide) {
            $first = $model->allRegions()->first();
            if ($first) {
                $ar = $first->translate('ar');
                if ($ar && $ar->name) {
                    return $ar->name;
                }

                return (string) ($first->name ?? 'حوّدج');
            }
        }

        return 'السعودية';
    }

    /**
     * @param \App\Models\City $city
     */
    private function cityArabicName($city): string
    {
        $ar = $city->translate('ar');
        if ($ar && $ar->name) {
            return $ar->name;
        }

        return (string) ($city->name ?? 'السعودية');
    }

    public function resolveTitle(Model $model, string $type): string
    {
        if ($model instanceof Guide) {
            $model->loadMissing('translations');

            return (string) ($model->translate('ar')?->name
                ?? $model->translate('en')?->name
                ?? $model->name
                ?? 'مرشد سياحي');
        }

        $model->loadMissing('translations');

        return (string) ($model->translate('ar')?->title
            ?? $model->translate('ar')?->name
            ?? $model->translate('en')?->title
            ?? $model->translate('en')?->name
            ?? $model->title
            ?? $model->name
            ?? 'جديد في حوّدج');
    }

    /**
     * @return array{0: string, 1: string, 2: array<string, string>}
     */
    public function build(User $user, string $type, Model $model): array
    {
        $name = $this->greetingName($user);
        $city = $this->resolveSpotLabel($model, $type);
        $title = $this->resolveTitle($model, $type);

        $templates = $this->templatesFor($type);
        $bodyTemplate = Arr::random($templates);

        $body = str_replace(
            [':name', ':city', ':title'],
            [$name, $city, $title],
            $bodyTemplate
        );

        $notificationTitle = $this->shortTitleFor($type);

        $data = [
            'content_type' => $type,
            'content_id' => (string) $model->getKey(),
            'slug' => (string) ($model->getAttribute('slug') ?? ''),
        ];

        return [$notificationTitle, $body, $data];
    }

    private function shortTitleFor(string $type): string
    {
        $map = [
            'place' => 'مكان جديد يستناك ✨',
            'store' => 'متجر جديد وصل 🔥',
            'event' => 'فعالية تستحق الحماس 🎉',
            'zad' => 'تجربة طعام جديدة 😋',
            'application' => 'تطبيق جديد على حوّدج 📱',
            'menu' => 'قائمة جديدة للمقهى/المطعم 📋',
            'offer' => 'عرض يفتح النفس 💥',
            'swalef' => 'حكاية جديدة من السوالف 📖',
            'guide' => 'مرشد جديد في الملعب 🧭',
        ];

        return $map[$type] ?? 'جديد في حوّدج 🤩';
    }

    /**
     * @return list<string>
     */
    private function templatesFor(string $type): array
    {
        $common = [
            ':name، جبنالك «:title» في :city — لازم تطّلع عليه 😍',
            ':name، في :city وصل شيء جديد اسمه «:title» ياخذ القلب 🔥',
            ':name، متحمسين نعرّفك على «:title» في :city… تستاهل التجربة ✨',
            ':name، لقينا لك كنز في :city: «:title» — جرّب وارجع علينا 😉',
        ];

        $byType = [
            'place' => [
                ':name، مكان رهيب في :city اسمه «:title» — يصلح للعائلة والأجواء 😍',
                ':name، وجهة جديدة في :city: «:title» تستاهل تكون على قائمتك 🤩',
            ],
            'store' => [
                ':name، متجر جديد في :city: «:title» — تسوق وانت مرتاح 🛍️',
                ':name، فتح «:title» في :city… والبضاعة تتكلم عن نفسها 💫',
            ],
            'event' => [
                ':name، فعالية قوية في :city: «:title» — لا تفوتها 🎉',
                ':name، تاريخ جديد في :city مع «:title»… جاهز للحماس؟ 🔥',
            ],
            'zad' => [
                ':name، مطعم/مقهى جديد في :city: «:title» — جوّك ولذّتك هنا 😋',
                ':name، «:title» في :city ينتظرك… أكل، قهوة، وضحكة 😍',
            ],
            'application' => [
                ':name، تطبيق جديد على حوّدج: «:title» — جرّبه وقول لنا رأيك 📱',
                ':name، وصل «:title» يسهّل عليك حياتك… تستاهل التحميل ✨',
            ],
            'menu' => [
                ':name، قائمة جديدة لـ «:title» في :city — شوف الأصناف اللي تفتح النفس 📋',
                ':name، تحديث لذيذ على «:title» في :city 😋',
            ],
            'offer' => [
                ':name، عرض على «:title» في :city — فرصة ما تتعوّض 💥',
                ':name، وصلنا عرض يجنّن لـ «:title» في :city 🔥',
            ],
            'swalef' => [
                ':name، سوالف جديدة: «:title» — قصة تربطك بالمكان والزمن 📖',
                ':name، حكاية من «:title» تستحق الدقيقة… ادخل واستمتع ✨',
            ],
            'guide' => [
                ':name، مرشد جديد اسمه «:title» — جاهز يوريك :city على أصولها 🧭',
                ':name، تعرّف على «:title»… خبرة وروح رهيبة في :city 😍',
            ],
        ];

        return array_values(array_unique(array_merge($byType[$type] ?? [], $common)));
    }
}
