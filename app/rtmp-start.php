<?php

include('includes/analyze.php');
set_time_limit(0);

// Check so we are running from CLI.
if (defined('STDIN')) {
  // Check so we are supposed to run.
  if (file_exists('/tmp/listening-rtmp')) {
    $i = file_get_contents('/tmp/listening-rtmp');
    exec('ffmpeg -t 30 -i "rtmp://localhost:2935/live/test" -c:v copy -c:a copy -strict -2 -y -f mpegts /videos/test-' . $i . '.flv');
    exec('ffmpeg -i /videos/test-' . $i . '.flv -c copy /videos/test-' . $i . '.mp4');
    unlink('/videos/test-' . $i . '.flv');
    // Stop listening
    unlink('/tmp/listening-rtmp');

    // Make sure the last bytes are written
    sleep(2);

    // Analyze the file
    analyze($i);
  }
}