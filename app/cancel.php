<?php

include('includes/authorize.php');
exec('killall -9 ffmpeg');
if (count(glob('/videos/*.mp4')) != count(glob('/videos/*.json'))) {
  foreach (glob('/videos/*.mp4') as $file) {
    $json_file = str_replace('.mp4', '.json', $file_name);
    if (!file_exists($json_file)) {
      unlink($file_name);
    }
  };
}

foreach (glob('/videos/*.ts') as $file) {
  unlink($file);
}

foreach (glob('/videos/*.flv') as $file) {
  unlink($file);
}

if (file_exists('/tmp/listening-srt')) {
  unlink('/tmp/listening-srt');
}

if (file_exists('/tmp/listening-rtmp')) {
  unlink('/tmp/listening-rtmp');
}

if (file_exists('/tmp/listening-rtmp-pull')) {
  unlink('/tmp/listening-rtmp-pull');
}

if (file_exists('/tmp/listening-hls-pull')) {
  unlink('/tmp/listening-hls-pull');
}