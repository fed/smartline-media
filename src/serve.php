<?php

require("utils/constants.php");
require("utils/ImageRenderer.php");
require("utils/Sanitizer.php");

if (DEBUG) {
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
}

try {
  $imageRenderer = new ImageRenderer(UPLOAD_DIR);
  $filename = Sanitizer::sanitizeFilename($_GET["id"]);
  $width = isset($_GET['width']) ? intval($_GET['width']) : null;
  $height = isset($_GET['height']) ? intval($_GET['height']) : null;
  $res = $imageRenderer->serve($filename, $width, $height);

  var_dump($res);
} catch (Exception $e) {
  var_dump($e);
}
