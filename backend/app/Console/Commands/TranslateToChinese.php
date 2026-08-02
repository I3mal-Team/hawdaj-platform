<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateToChinese extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate-to-chinese';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TranslateToChinese';

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
     * @return int
     */
    public function handle()
    {
        $path = resource_path("lang/ar.json");
        $data = json_decode(file_get_contents($path), true);
        foreach ($data as $key => $value) {
            $value = GoogleTranslate::trans($value, "zh");
            $data[$key] = $value;
        }
        dd($data);
        return 0;
    }
}
