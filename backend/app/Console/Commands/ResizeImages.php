<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Image;
use File;
class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize all applications images in the specified directory';


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
        $this->resizeImages('/uploads/small-applications', 260, 360, 'All applications images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-applications', 500, 500, 'All applications images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-events', 288, 350, 'All events images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-events', 500, 500, 'All events images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-places', 190, 290, 'All places images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-places', 1200, 700, 'All places images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-stores', 250, 270, 'All stores images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-stores', 500, 500, 'All stores images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-stories', 250, 250, 'All stories images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-stories', 500, 500, 'All stories images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-swalefs', 250, 250, 'All swalefs images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-swalefs', 500, 500, 'All swalefs images have been resized medium successfully.');

        $this->resizeImages('/uploads/small-zad_elgadels', 320, 350, 'All zad_elgadels images have been resized small successfully.');
        $this->resizeImages('/uploads/medium-zad_elgadels', 500, 500, 'All zad_elgadels images have been resized medium successfully.');

        return 0;
    }
}
