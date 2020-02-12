<?php

// Only allow to set initially
if (file_get_contents('/htpasswd/.htpasswd') == 'oskberlin:YWG41BPzVAkN6') {
  $username = isset($_GET['username']) ? $_GET['username'] : exit;
  $password = isset($_GET['password']) ? $_GET['password'] : exit;
  $encrypted_password = crypt($password, base64_encode($password));
  file_put_contents('/htpasswd/.htpasswd', $username . ':' . $encrypted_password);
  echo json_encode(['status' => true]);
  exit;
}
echo json_encode(['status' => false]);