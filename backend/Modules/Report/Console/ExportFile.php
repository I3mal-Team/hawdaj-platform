<?php

namespace Modules\Report\Console;

use App\Services\SettingService;
use Illuminate\Console\Command;
use Modules\Report\Entities\ArchiveFile;
use Modules\Report\Services\ExportFileService;
use Storage;
use Symfony\Component\Console\Input\InputOption;


class ExportFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export file according to specific date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $start = now();
            $this->comment('Processing');

            $files = ArchiveFile::where('status', 0)->get();
            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file->status == false || !(Storage::disk('public')->exists($file->url))) {
                        try {
                            $new_file = ExportFileService::handle($file);
                        } catch (\Exception $e) {
                            continue;
                        }

                        if (Storage::disk('public')->exists($new_file->url)) {
                            $body['title'] = 'File Prepared';
                            $body['message'] = "Your file $file->name has been prepared successfully click to download";
                            $body['url'] = "/storage/" . $new_file->url;
                            SettingService::notifyUser($file->user_id, 'notification_general', $body);
                        }

                    }
                }
            }

            $this->info('Successfully Exported waiting files');

            $time = $start->floatDiffInSeconds(now());

            $this->comment("Processed in " . round($time, 3) . " seconds");

        } catch (\Exception $e) {
            \Log::error($e->getMessage() . json_encode($e->getTrace()));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
