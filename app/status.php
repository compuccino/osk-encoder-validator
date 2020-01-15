<?php

$output = [
  'listening' => FALSE,
  'running' => FALSE,
];

// Check if it's listening
if (file_exists('/tmp/listening-srt')) {
  $output['listening'] = TRUE;
  $output['listening_format'] = 'srt';
}
if (file_exists('/tmp/listening-rtmp')) {
  $output['listening'] = TRUE;
  $output['listening_format'] = 'rtmp';
}

if ($output['listening'] && (glob('/videos/*.ts') || glob('/videos/*.flv'))) {
  $output['running'] = TRUE;
}

echo json_encode($output);