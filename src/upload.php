<?php

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://admin.freyre.com.ar");
header("Access-Control-Allow-Origin: http://localhost:6789");
header("Content-Type: application/json");

require("utils/constants.php");
require("utils/ImageUploader.php");

if (DEBUG) {
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
}

try {
  // Must provide a valid API key when uploading pictures
  if ($_POST["apiKey"] !== API_KEY) {
    echo json_encode(array(
      "success" => false,
      "message" => "Please provide a valid API key."
    ));

    die();
  }

  $imageUploader = new ImageUploader(UPLOAD_DIR, MD5_HASH_SALT, MAX_FILE_SIZE);
  $uid = time() . rand();
  $filename = $imageUploader->upload($_FILES[INPUT_FIELD_NAME], $uid);

  if ($filename) {
    $response = array(
      "success" => true,
      "filename" => $filename,
      "url" => MEDIA_SERVER_URL . "serve.php?id=" . $filename
    );
  } else {
    $response = array(
      "success" => false,
      "message" => "We couldn't upload your picture. Please make sure it meets the filetype and size restrictions and try again."
    );
  }

  echo json_encode($response);
} catch (Exception $e) {
  die($e);
}
