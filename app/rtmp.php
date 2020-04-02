<?php

$i = count(glob('/videos/*.mp4'))+1;

// Tell everyone we are listening
file_put_contents('/tmp/listening-rtmp', $i);