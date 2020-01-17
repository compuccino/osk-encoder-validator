<?php

include('includes/analyze.php');
set_time_limit(0);

$i = count(glob('/videos/*.mp4'))+1;

// Tell everyone we are listening
file_put_contents('/tmp/listening-srt', TRUE);

exec('ffmpeg -i "srt://0.0.0.0:6872?pkt_size=1316&mode=listener&listen_timeout=1000&passphrase=1234567890123456&pbkeylen=16" -t 30 -c:v copy -c:a copy -strict -2 -y -f mpegts /videos/test-' . $i . '.ts');

exec('ffmpeg -i /videos/test-' . $i . '.ts -c copy /videos/test-' . $i . '.mp4');
unlink('/videos/test-' . $i . '.ts');

// Stop listening
unlink('/tmp/listening-srt');

// Make sure the last bytes are written
sleep(2);

// Analyze the file
analyze($i);