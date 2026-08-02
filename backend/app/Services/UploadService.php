<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Image;
use Intervention\Image\Exception\NotSupportedException;

class UploadService
{
    /**
     * Check if image processing is available
     * @return bool
     */
    private static function isImageProcessingAvailable(): bool
    {
        // Check if GD or Imagick extension is loaded
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            try {
                // Try to create a simple image to verify it works
                $testImg = Image::make('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
                return true;
            } catch (NotSupportedException $e) {
                return false;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $files
     * @param string $path
     * @param string $disk
     * @return array|false|mixed|string
     */
    public static function store($files, string $path = 'files', string $disk = 'public')
    {
        $items = is_array($files) ? $files : [$files];

        $paths = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                try {
                    if (is_file($item)) {
                        // Check if image processing is available
                        if (self::isImageProcessingAvailable()) {
                            // Create image instance for original
                            $originalImg = Image::make($item->getRealPath());

                            // Save original image as WebP with high quality (95% to preserve quality)
                            $webpPath = $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                            Storage::disk($disk)->put($webpPath, (string) $originalImg->encode('webp', 95));
                            $paths[] = $webpPath;

                            // Create new instance for 250x250 resize
                            $img250 = Image::make($item->getRealPath());
                            $resized250Path = 'small-' . $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                            $img250->resize(250, 250, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize(); // Prevent upscaling
                            })->encode('webp', 85); // Slightly lower quality for thumbnails is acceptable

                            Storage::disk($disk)->put($resized250Path, (string) $img250);

                            // Create new instance for 500x500 resize
                            $img500 = Image::make($item->getRealPath());
                            $resized500Path = 'medium-' . $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                            $img500->resize(500, 500, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize(); // Prevent upscaling
                            })->encode('webp', 90); // Higher quality for medium size

                            Storage::disk($disk)->put($resized500Path, (string) $img500);
                        } else {
                            // Fallback: Save file without processing
                            $filePath = $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $item->getClientOriginalExtension();
                            Storage::disk($disk)->put($filePath, file_get_contents($item->getRealPath()));
                            $paths[] = $filePath;
                        }

                    } elseif (is_string($item) && is_base64($item)) {
                        // Check if image processing is available
                        if (self::isImageProcessingAvailable()) {
                            // Handle base64 encoded files
                            $originalImg = Image::make(base64_decode($item));

                            // Save original image as WebP with high quality (95% to preserve quality)
                            $webpPath = $path . '/' . \Str::random(10) . time() . '.webp';
                            Storage::disk($disk)->put($webpPath, (string) $originalImg->encode('webp', 95));
                            $paths[] = $webpPath;

                            // Create new instance for 250x250 resize
                            $img250 = Image::make(base64_decode($item));
                            $resized250Path = 'small-' . $path . '/' . \Str::random(10) . time() . '.webp';
                            $img250->resize(250, 250, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize(); // Prevent upscaling
                            })->encode('webp', 85); // Slightly lower quality for thumbnails is acceptable

                            Storage::disk($disk)->put($resized250Path, (string) $img250);

                            // Create new instance for 500x500 resize
                            $img500 = Image::make(base64_decode($item));
                            $resized500Path = 'medium-' . $path . '/' . \Str::random(10) . time() . '.webp';
                            $img500->resize(500, 500, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize(); // Prevent upscaling
                            })->encode('webp', 90); // Higher quality for medium size

                            Storage::disk($disk)->put($resized500Path, (string) $img500);
                        } else {
                            // Fallback: Save base64 file without processing
                            $decoded = base64_decode($item);
                            $filePath = $path . '/' . \Str::random(10) . time() . '.png';
                            Storage::disk($disk)->put($filePath, $decoded);
                            $paths[] = $filePath;
                        }
                    }
                } catch (NotSupportedException $e) {
                    // If image processing fails, fallback to simple file storage
                    if (is_file($item)) {
                        $filePath = $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $item->getClientOriginalExtension();
                        Storage::disk($disk)->put($filePath, file_get_contents($item->getRealPath()));
                        $paths[] = $filePath;
                    } elseif (is_string($item) && is_base64($item)) {
                        $decoded = base64_decode($item);
                        $filePath = $path . '/' . \Str::random(10) . time() . '.png';
                        Storage::disk($disk)->put($filePath, $decoded);
                        $paths[] = $filePath;
                    }
                } catch (\Exception $e) {
                    // Log error but continue with fallback
                    \Log::error('Image processing error: ' . $e->getMessage());
                    if (is_file($item)) {
                        $filePath = $path . '/' . pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $item->getClientOriginalExtension();
                        Storage::disk($disk)->put($filePath, file_get_contents($item->getRealPath()));
                        $paths[] = $filePath;
                    }
                }
            }
        }

        return $paths ? (count($paths) == 1 ? $paths[0] : $paths) : null;
    }

    public static function storeFile($file, string $path = 'files', string $disk = 'public')
    {
        if (is_file($file)) {
            $path = $path . '/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension();
            Storage::disk($disk)->put($path, file_get_contents($file));
            return $path;
        }
    }

    /**
     * @param $files
     * @param string $disk
     * @return bool
     */
    public static function delete($files, string $disk = 'public'): bool
    {
        $items = is_array($files) ? $files : [$files];

        if (!empty($items)) {
            foreach ($items as $item) {
                if (!$item) {
                    continue;
                }
                
                Storage::disk($disk)->delete($item);
                
                $pathInfo = pathinfo($item);
                $directory = $pathInfo['dirname'];
                $filename = $pathInfo['basename'];
                
                $smallPath = 'small-' . $directory . '/' . $filename;
                if (Storage::disk($disk)->exists($smallPath)) {
                    Storage::disk($disk)->delete($smallPath);
                }
                
                $mediumPath = 'medium-' . $directory . '/' . $filename;
                if (Storage::disk($disk)->exists($mediumPath)) {
                    Storage::disk($disk)->delete($mediumPath);
                }
            }
        }

        return true;
    }
}
