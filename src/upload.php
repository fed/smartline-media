<?php

header("Content-Type: application/json");

require("utils/constants.php");
require("utils/ImageUploader.php");

if (DEBUG) {
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
}

try {
  $imageUploader = new ImageUploader(UPLOAD_DIR, MD5_HASH_SALT, MAX_FILE_SIZE);
  $uid = time() . rand();
  $filename = $imageUploader->upload($_FILES[INPUT_FIELD_NAME], $uid);

  if ($filename) {
    $response = array(
      "success" => true,
      "filename" => $filename,
      "url" => MEDIA_SERVER_URL . "?id=" . $filename . "&width="
    );
  } else {
    $response = array(
      "success" => false
    );
  }

  echo json_encode($response);
} catch (Exception $e) {
  die($e);
}
