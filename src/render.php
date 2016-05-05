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
  $res = $imageRenderer->serve($filename, $_GET["width"], $_GET["height"]);

  var_dump($res);
} catch (Exception $e) {
  var_dump($e);
}
