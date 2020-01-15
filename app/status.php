<?php

$output = [
  'listening' => FALSE,
  'running' => FALSE,
];

// Check if it's listening
if (file_exists('/tmp/listening-srt')) {
  $output['listening'] = TRUE;
  $output['listening-format'] = 'srt';
}
if (file_exists('/tmp/listening-rtmp')) {
  $output['listening'] = TRUE;
  $output['listening-format'] = 'rtmp';
}

if ($output['listening'] && count(glob('/videos/*.mp4')) != count(glob('/videos/*.json'))) {
  $output['running'] = TRUE;
}

echo json_encode($output);