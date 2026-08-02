<?php

namespace Modules\Report\Database\Seeders;

use App\Models\Config;
use App\Models\DraftReport as ModelsDraftReport;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Modules\Report\Entities\ChartDetails;
use Modules\Report\Entities\PinnedReport;

class ConfigSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $charts = ['card', 'bar', 'line', 'pie', 'table'];
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get();

        foreach ($users as $user) {
            foreach (['report'] as $type) {
                foreach ($charts as $chart) {
                    Config::create([
                        'key' => "chart",
                        'value' => $chart,
                        'view' => $type,
                        'user_id' => $user->id,
                        'active' => 1,
                    ]);
                }
                Config::create([
                    'key' => "card",
                    'value' => "card",
                    'view' => $type,
                    'user_id' => $user->id,
                    'active' => 1,
                ]);
            }

            foreach ($charts as $chart) {
                ChartDetails::create([
                    'type' => $chart,
                    'title' => ucfirst($chart) . ' Chart',
                    'description' => ucfirst($chart) . ' Chart',
                    'time_unit' => 'minute',
                    'user_id' => $user->id
                ]);
            }

            foreach (['CarModel'] as $model) {
                $drafts = [];
                $site_id = $user->sites()->distinct()->take(7)->pluck('id')->toArray();
                $reports = \config($model . ".report");
                foreach ($reports ?? [] as $key => $report) {
                    $drafts[] = ModelsDraftReport::create([
                        'model_type' => $model,
                        'report_type' => 'comparison',
                        'report_list' => $key,
                        'time_type' => 'dynamic',
                        'time_range' => 16,
                        'user_id' => $user->id,
                        'site_id' => $site_id,
                        'columns' => $report['data'],
                        'title' => "Monthly Report For $model",
                    ]);
                }
                foreach ($charts as $chart) {
                    foreach ($drafts as $draft) {
                        $draft->charts()->create([
                            'type' => $chart,
                            'title' => ucfirst($chart) . " Title [$model] (" . __("dashboard.$draft->report_list") . ')',
                            'description' => ucfirst($chart) . " Description [$model] (" . __("dashboard.$draft->report_list") . ')',
                            'time_unit' => "minute",
                            'active' => true,
                        ]);
                    }
                }
            }

            $pinned = PinnedReport::create([
                'title' => 'Monthly Report',
                'default' => true,
                'user_id' => $user->id,
                'active' => true,
            ]);

            $pinned->charts()->attach([2, 3, 7, 8, 10, 12, 13, 17, 21, 22]);

            cache()->forget("report_pinned_$pinned->id");
            cache()->forget("config.$user->id");
            cache()->forget("chart_details.ar.$user->id");
            cache()->forget("chart_details.en.$user->id");
        }
    }
}
