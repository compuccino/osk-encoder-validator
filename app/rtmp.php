<?php

include('includes/analyze.php');
set_time_limit(0);

$i = count(glob('/videos/*.mp4'))+1;

// Tell everyone we are listening
file_put_contents('/tmp/listening-rtmp', TRUE);

exec('ffmpeg -f flv -listen 1 -i "rtmp://0.0.0.0:6872" -t 30 -c:v copy -c:a copy -strict -2 -y -f mpegts /videos/test-' . $i . '.flv');

exec('ffmpeg -i /videos/test-' . $i . '.flv -c copy /videos/test-' . $i . '.mp4');
unlink('/videos/test-' . $i . '.flv');

// Stop listening
unlink('/tmp/listening-rtmp');

// Make sure the last bytes are written
sleep(2);

// Analyze the file
analyze($i);