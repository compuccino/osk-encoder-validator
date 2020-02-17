<?php

include('includes/authorize.php');
include('includes/analyze.php');
set_time_limit(0);

$i = count(glob('/videos/*.mp4'))+1;

$url = isset($_GET['url']) ? $_GET['url'] : exit;
$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

if ($username && $password) {
  $parts = parse_url($url);
  $url = $parts['scheme'] . '://' . $username . ':' . $password . '@' . $parts['host'];
  $url .= isset($parts['port']) ? ':' . $parts['port'] : '';
  $url .= $parts['path'];
}

// Tell everyone we are listening
file_put_contents('/tmp/listening-hls-pull', TRUE);

exec('ffmpeg -i "' . $url . '" -c:v copy -c:a copy -strict -2 -t 30 -y /videos/test-' . $i . '.flv');

exec('ffmpeg -i /videos/test-' . $i . '.flv -c copy /videos/test-' . $i . '.mp4');
unlink('/videos/test-' . $i . '.flv');

// Stop listening
unlink('/tmp/listening-hls-pull');

// Make sure the last bytes are written
sleep(2);

// Analyze the file
analyze($i);