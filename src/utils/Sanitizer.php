<?php

class Sanitizer {
  /**
   * Returns a safe, sanitized filename. Every forbidden character is removed
   * @param string $dangerousFilename The source filename to be sanitized
   * @return string A safe version of the input filename
   */
  public static function sanitizeFilename($dangerousFilename) {
    $dangerousCharacters = array(" ", '"', "'", "&", "/", "\\", "?", "#");

    return str_replace($dangerousCharacters, '', $dangerousFilename);
  }
}
