<?php

include('includes/filters/filterBase.php');

function getRealValue($key, $value) {
  $newKey = camelCase($key);
  if (file_exists('includes/filters/' . $newKey . '.php')) {
    include('includes/filters/' . $newKey . '.php');
    $object = new $newKey();
    return $object->filter($value);
  } else {
    return strtolower($value);
  }
}

// http://www.mendoweb.be/blog/php-convert-string-to-camelcase-string/
function camelCase($str, array $noStrip = []) {
  $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
  $str = trim($str);
  $str = ucwords($str);
  $str = str_replace(" ", "", $str);
  $str = lcfirst($str);

  return $str;
}