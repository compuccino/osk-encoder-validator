<?php

$list = [];
foreach (glob('/videos/*.json') as $file) {
  $info = json_decode(file_get_contents($file), TRUE);
  $list[] = [
    'number' => $info['number'], 
    'date' => $info['date']
  ];
}

$validations = [];
foreach (glob('/validation-config/*.yaml') as $file) {
  $validations[] = str_replace(['.yaml', '/validation-config/'], '', $file);
}

$reverse = array_reverse($list);

echo json_encode(['list' => $reverse, 'validations' => $validations]);