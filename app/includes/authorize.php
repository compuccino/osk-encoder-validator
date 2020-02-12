<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
  authenticate();
} else {
  $encrypted_password = isset($_SERVER['PHP_AUTH_PW']) ? crypt($_SERVER['PHP_AUTH_PW'], base64_encode($_SERVER['PHP_AUTH_PW'])) : '';
  if ($_SERVER['PHP_AUTH_USER'] . ':' . $encrypted_password != file_get_contents('/htpasswd/.htpasswd')) {
    authenticate();
  }
}

function authenticate() {
  header('WWW-Authenticate: Basic realm="OSK Encoder Validator - default (oskberlin/admin)"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'No Access';
  exit;
}