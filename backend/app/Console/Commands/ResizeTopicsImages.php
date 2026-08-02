<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Image;
use File;
class ResizeTopicsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:topics-resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize all topics images in the specified directory';


    private function resizeImages($directory, $width, $height, $message) {
        $files = File::allFiles(public_path($directory));

        foreach ($files as $file) {
            // Check if the file is an image
            $mimeType = mime_content_type($file->getRealPath());
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];

            if (in_array($mimeType, $allowedMimeTypes)) {
                $img = Image::make($file->getRealPath());

                // Resize the image
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Save the resized image
                $img->save($file->getRealPath());
            } else {
                $this->info("Skipping file '{$file->getFilename()}' (unsupported image type).");
            }
        }

        $this->info($message);
    }

    public function handle() {
        $this->resizeImages('/uploads/small-topics', 340, 260, 'All topics images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-topics', 500, 500, 'All topics images have been resized medium successfully.');

        return 0;
    }
}
