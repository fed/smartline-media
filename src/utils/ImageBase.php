<?php

abstract class ImageBase {
  /**
   * Path where images get uploaded to
   * @var string
   */
  protected $path;

  /**
   * Valid mime types and processing functions
   * @var array
   */
  protected $mime_types_processors = array(
    "image/gif" => array("imagecreatefromgif", "imagegif"),
    "image/jpg" => array("imagecreatefromjpeg", "imagejpeg"),
    "image/jpeg" => array("imagecreatefromjpeg", "imagejpeg"),
    "image/png" => array("imagecreatefrompng", "imagepng"),
    "image/bmp" => array("imagecreatefromwbmp", "imagewbmp")
  );

  /**
   * Constructor method
   */
  public function __construct($path = null) {
    $this->path = $path;
  }

  /**
   * Set $path
   * @param string $path Path where images get uploaded to
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * Get $path
   * @return string Path where images get uploaded to
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Get path to a given filename
   * @param string $identifier Image identifier
   * @return string Relative path to the image
   */
  protected function getImagePath($identifier) {
    return $this->path . DIRECTORY_SEPARATOR . $identifier;
  }

  /**
   * Get path to a given filename
   * @param string $identifier Image identifier
   * @return string Relative path to the image
   */
  protected function getMimeTypesProcessors() {
    return $this->mime_types_processors;
  }

  /**
   * Check whether an image with this identifier exists
   * @param string $identifier Image identifier
   * @return boolean Indicates whether a file with the provided name exists
   */
  protected function exists($identifier) {
    $image_path = $this->getImagePath($identifier);

    return file_exists($image_path);
  }
}
