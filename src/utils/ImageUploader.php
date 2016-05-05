<?php

require("ImageBase.php");

class ImageUploader extends ImageBase {
  /**
   * Salt used by the application to hash images names
   * @var string
   */
  private $salt;

  /**
   * Maximum size allowed (in bytes)
   * @var number
   */
  private $max_size;

  /**
   * Constructor method
   */
  public function __construct($path = null, $salt = null, $max_file_size = null) {
    parent::__construct($path);
    $this->salt = $salt;
    $this->max_file_size = $max_file_size;
  }

  /**
   * Set $salt
   * @param string $salt Salt used to hash images names
   */
  public function setSalt($salt) {
    $this->salt = $salt;
  }

  /**
   * Get $salt
   * @return string Salt used to hash images names
   */
  public function getSalt() {
    return $this->salt;
  }

  /**
   * Set $max_file_size
   * @param number $max_file_size Maximum file size allowed (in bytes)
   */
  public function setMaxFileSize($max_file_size) {
    $this->max_file_size = $max_file_size;
  }

  /**
   * Get $max_file_size
   * @return number Maximum file size allowed (in bytes)
   */
  public function getMaxFileSize() {
    return $this->max_file_size;
  }

  /**
   * Get hashed image name
   * @param string $identifier Image identifier
   * @return string MD5 hash
   */
  private function getHash($identifier) {
    if ($this->salt === null) {
      $image_name = md5($identifier);
    } else {
      $image_name = md5($identifier . $this->salt);
    }

    return $image_name;
  }
  /**
   * Make sure we get the correct input
   * @param array $image This is the $_FILES["image"] object
   */
  private function checkParameters($image) {
    if (!is_array($image)) {
      throw new Exception("No image matching the name provided was uploaded");
    }

    if (!file_exists($this->path)) {
      throw new Exception("Invalid path, make sure the provided route exists");
    }
  }

  /**
   * Check for upload errors
   * @param array $image The $_FILES["image"] param
   */
  private function checkUploadError($image) {
    if (!isset($image["error"]) || is_array($image["error"])) {
      throw new Exception("Invalid params");
    }

    switch ($image["error"]) {
      case UPLOAD_ERR_OK:
        break;

      case UPLOAD_ERR_NO_FILE:
        throw new Exception("No file sent");
        break;

      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new Exception("Exceeded maximum filesize limit");
        break;

      default:
        throw new Exception("Oops, something went wrong when trying to upload your image!");
    }
  }

  /**
   * Check whether uploaded file size is within max filesize limit
   * @param array $image The $_FILES["image"] param
   */
  private function checkFileSize($image) {
    if ($this->max_file_size !== null && $image["size"] > $this->max_file_size) {
      throw new Exception("Exceeded maximum filesize limit");
    }
  }

  /**
   * Check whether first 100 bytes contain any non ASCII character
   * @param array $image The $_FILES["image"] param
   */
  private function checkInitialBytes($image) {
    // Read first 100 bytes
    $content = file_get_contents($image["tmp_name"], null, null, 0, 100);

    if ($content === false) {
      throw new Exception("Unable to read uploaded file");
    }

    $regex = "[\x01-\x08\x0c-\x1f]";

    if (preg_match($regex, $content)) {
      throw new Exception("Invalid image content found");
    }
  }

  /**
   * Run a handful of safety checks before uploading the image
   * @param array $image The $FILES["image"] param
   */
  private function securityChecks($image) {
    $this->checkParameters($image);
    $this->checkUploadError($image);
    $this->checkFileSize($image);
    $this->checkInitialBytes($image);
  }

  /**
   * Reprocess the image using the GD library to remove any malicious code.
   * @param array $image The $_FILES["image"] param
   * @param function $callback Callback to allow for extra image manipulation
   */
  private function reprocessImage($image) {
    $image_info = getimagesize($image["tmp_name"]);

    if ($image_info === null) {
      throw new Exception("Invalid image type");
    }

    $mime_type = $image_info["mime"];

    if (!array_key_exists($mime_type, $this->getMimeTypesProcessors())) {
      throw new Exception("Invalid image MIME type");
    }

    $image_from_file = $this->getMimeTypesProcessors()[$mime_type][0];
    $image_to_file = $this->getMimeTypesProcessors()[$mime_type][1];

    $reprocessed_image = $image_from_file($image["tmp_name"]);

    if (!$reprocessed_image) {
      throw new Exception("Unable to create reprocessed image from file");
    }

    $image_to_file($reprocessed_image, $image["tmp_name"]);

    // Free up memory
    imagedestroy($reprocessed_image);
  }

  /**
   * Upload an image
   * @param array $image The $_FILES["image"] param
   * @param string $identifier Image identifier
   * @param function $callback Optional callback, allows for extra image manipulation
   * @return boolean Indicates whether the image upload was successful
   */
  public function upload($image, $identifier, $callback = null) {
    $this->securityChecks($image);
    $this->reprocessImage($image, $callback);

    $new_name = $this->getHash($identifier);
    $destination_path = $this->getImagePath($new_name);
    $success = move_uploaded_file($image["tmp_name"], $destination_path);

    if ($success) {
      $success = $new_name; // If the image got uploaded successfully, return the hashed filename
    }

    return $success;
  }
}
