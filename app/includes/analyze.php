<?php

function analyze($i) {
  $file_name = "test-$i.mp4";
  $file = '/videos/' . $file_name;
  if (file_exists($file)) {
    $header = 'General info';
    $info = [
      'number' => $i,
      'date' => date('Y-m-d H:i:s'),
    ];

    // Mediainfo
    exec('mediainfo ' . $file, $output, $code);
    if ($code) {
      $info['corrupt'] = TRUE;
    } else {
      $info['corrupt'] = FALSE;
      foreach($output as $row) {
        if ($row) {
          preg_match_all('/(.*)\s\: (.*)/i', $row, $matches);
          if (isset($matches[2][0])) {
            $info['mediainfo'][$header][trim($matches[1][0])] = trim($matches[2][0]);
          } else {
            $header = trim($row);
          }
        }
      }
    }

    // FFprobe
    if (!$info['corrupt']) {
      unset($output);
      exec('ffprobe -show_frames ' . $file, $output);
      $frames = [];
      $media_type = '';
      $stream_index = '';
      foreach ($output as $row) {
        if ($row == '[FRAME]') {
          $frame = [];
        } else if ($row == '[/FRAME]') {
          $frames[$media_type][$stream_index][] = $frame;
        } else {
          $parts = explode('=', $row);
          if (isset($parts[1])) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            if ($key == 'media_type') {
              $media_type = $value;
            }
            if ($key == 'stream_index') {
              $stream_index = $value;
            }
            $frame[$key] = $value;
          }          
        }
      }
      $info['ffprobe'] = $frames;
    }
    
    $json_file = '/videos/' . str_replace('.mp4', '.json', $file_name);
    file_put_contents($json_file, json_encode($info));
  }
}