<?php

namespace App\Http\Livewire;

use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
    use WithFileUploads;

    public $images = [];
    public $watermark;

    public $options;

    protected $listeners = [
        'cleanupFiles',
    ];

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function uploadImages()
    {
        try {
            $this->options['extension'] ??= 'webp';
            $this->options['quality'] ??= 50;
            $this->options['watermarkPosition'] ??= 'top-left';
            $this->options['watermarkSize'] ??= 10;

            $zipName = 'my-images.zip';

            // create Intervention\Image object for watermark
            $watermark = null;
            if ($this->watermark) {
                $watermark = Image::make($this->watermark);
            }

            // create a temporary file for the zip archive
            $tempFile = tempnam(sys_get_temp_dir(), '');

            // create the zip archive
            $zip = new \ZipArchive();
            $zip->open($tempFile, \ZipArchive::CREATE);

            foreach ($this->images as $image) {

                // create Intervention\Image object for images
                $img = Image::make($image);

                // Insert watermark
                if ($watermark) {
                    $watermark->resize(null, $img->height() * ($this->options['watermarkSize'] / 100), function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->insert($watermark, $this->options['watermarkPosition'], $this->options['watermarkPosition'] != 'center' ? 10 : 0, $this->options['watermarkPosition'] != 'center' ? 10 : 0);
                }

                // image extension
                $img->encode($this->options['extension'], $this->options['quality']);

                $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $this->options['extension'];

                $zip->addFromString($imageName, $img->__toString());
            }

            $zip->close();

            // stream the zip archive to the client
            return response()->download($tempFile, $zipName, [
                'Content-Type' => 'application/zip'
            ])->deleteFileAfterSend();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function cleanupFiles()
    {
        if ($this->watermark) {
            $this->watermark->delete();
        }
        if ($this->images) {
            foreach ($this->images as $image) {
                $image->delete();
            }
        }
        $this->dispatchBrowserEvent('reloadPage');
    }
}
