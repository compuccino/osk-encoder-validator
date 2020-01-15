<?php

$list = [];
foreach (glob('/videos/*.json') as $file) {
  $info = json_decode(file_get_contents($file), TRUE);
  $list[] = [
    'number' => $info['number'], 
    'date' => $info['date']
  ];
}

$reverse = array_reverse($list);

echo json_encode($reverse);