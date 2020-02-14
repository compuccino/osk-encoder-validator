<?php

include('includes/analyze.php');
set_time_limit(0);

$i = count(glob('/videos/*.mp4'))+1;

$url = isset($_GET['url']) ? $_GET['url'] : exit;
$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

if ($username && $password) {
  $url .= " flashver=FMLE/3.0\20(compatible;\20FMSc/1.0) live=true pubUser=$username pubPasswd=$password";
}

// Tell everyone we are listening
file_put_contents('/tmp/listening-rtmp-pull', TRUE);
exec('ffmpeg -i "' . $url . '" -c:v copy -c:a copy -strict -2 -y -t 30 -f mpegts /videos/test-' . $i . '.flv');

exec('ffmpeg -i /videos/test-' . $i . '.flv -c copy /videos/test-' . $i . '.mp4');
unlink('/videos/test-' . $i . '.flv');

// Stop listening
unlink('/tmp/listening-rtmp-pull');

// Make sure the last bytes are written
sleep(2);

// Analyze the file
analyze($i);