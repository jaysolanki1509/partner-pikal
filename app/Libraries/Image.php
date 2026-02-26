<?php namespace App\Libraries;

// We need to add these namespaces
// in order to have access to these classes.
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class Image{

    protected $imagine;

    // We instantiate the Imagine library with Imagick or GD
    public function __construct()
    {
        $this->library = null;
        if ( !$this->imagine) {
            if ( !$this->library and class_exists('Imagick')) {
                $this->imagine = new \Imagine\Imagick\Imagine();
            } else {
                $this->imagine = new \Imagine\Gd\Imagine();
            }
        }

    }

    /*
     * Resize function.
     * @param string filename
     * @param string sizeString
     *
     * @return blob image contents.
     */
    public function resizegalleryimage($filename, $sizeString) {

        // We can read the output path from our configuration file.
        $outputDir = Config::get('galleryresize.images.paths.output');


        // Create an output file path from the size and the filename.
        $outputFile = $outputDir . '/' . $sizeString . '_' . $filename;

        // If the resized file already exists we will just return it.
        if (File::isFile($outputFile)) {
            return File::get($outputFile);
        }

        // File doesn't exist yet, so we will resize the original.
        $inputDir = Config::get('galleryresize.images.paths.getimage');

        $inputFile = $inputDir . '/' . $filename;

        // Get the width and the height of the chosen size from the Config file.
        $sizeArr = Config::get('galleryresize.images.sizes.' . $sizeString);
        $width = $sizeArr['width'];
        $height = $sizeArr['height'];

        // We want to crop the image so we set the resize mode and size.
        $size = new \Imagine\Image\Box($width, $height);
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        // Create the output directory if it doesn't exist yet.


        // Open the file, resize it and save it.
        $this->imagine->open($inputFile)
            ->thumbnail($size, $mode)
            ->save($outputFile, array('quality' => 90));

        // Return the resized file.
        return File::get($outputFile);

    }

    public function resize($filename, $sizeString) {

        // We can read the output path from our configuration file.
        $outputDir = Config::get('asset.images.paths.output');

        // Create an output file path from the size and the filename.
        $outputFile = $outputDir . '/' . $sizeString . '_' . $filename;

        // If the resized file already exists we will just return it.
        if (File::isFile($outputFile)) {
            return File::get($outputFile);
        }

        // File doesn't exist yet, so we will resize the original.
        $inputDir = Config::get('asset.images.paths.input');
        $inputFile = $inputDir . '/' . $filename;

        // Get the width and the height of the chosen size from the Config file.
        $sizeArr = Config::get('asset.images.sizes.' . $sizeString);
        $width = $sizeArr['width'];
        $height = $sizeArr['height'];

        // We want to crop the image so we set the resize mode and size.
        $size = new \Imagine\Image\Box($width, $height);
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        // Create the output directory if it doesn't exist yet.


        // Open the file, resize it and save it.
        $this->imagine->open($inputFile)
            ->thumbnail($size, $mode)
            ->save($outputFile, array('quality' => 90));

        // Return the resized file.
        return File::get($outputFile);

    }

    /**
     * @param string $filename
     * @return string mimetype
     */
    public function getMimeType($filename) {

        // Make the input file path.
        $inputDir = Config::get('asset.images.paths.input');
        $inputFile = $inputDir . '/' .  $filename;

        // Get the file mimetype using the Symfony File class.
        $file = new \Symfony\Component\HttpFoundation\File\File($inputFile);
        return $file->getMimeType();
    }

    public function getGalleryMimeType($filename) {

        // Make the input file path.
        $inputDir = Config::get('galleryresize.images.paths.getimage');
        $inputFile = $inputDir . '/' .  $filename;

        // Get the file mimetype using the Symfony File class.
        $file = new \Symfony\Component\HttpFoundation\File\File($inputFile);
        return $file->getMimeType();
    }
}