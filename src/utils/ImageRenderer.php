<?php

require("ImageBase.php");

class ImageRenderer extends ImageBase {
   /**
   * Constructor method
   */
  public function __construct($path = null) {
    parent::__construct($path);
  }

  /**
   * Serve an image
   * @param string $identifier Image identifier
   * @param number $width The width to scale the image to
   * @param number $height The height to scale the image to. If omitted or negative, the aspect ratio will be preserved.
   * @return boolean Indicates whether the image has been served successfully
   */
  public function serve($filename, $width = null, $height = null) {
    if (!$this->exists($filename)) {
      return false;
    }

    $image_path = $this->getImagePath($filename);
    $mime_type = getimagesize($image_path)["mime"];

    $image_from_file = $this->getMimeTypesProcessors()[$mime_type][0];
    $image_to_file = $this->getMimeTypesProcessors()[$mime_type][1];

    $image = $image_from_file($image_path);

    if (!$image) {
      throw new Exception("Unable to read image");
    }

    if ($width !== null && is_numeric($width) && intval($width) !== 0) {
      $width = intval(abs($width));
      $height = $height !== null ? intval(abs($height)) : -1;
      $image = imagescale($image, $width, $height);
    }

    header("Content-Type: " . $mime_type);

    $result = $image_to_file($image, null);

    return $result;
  }
}
