<?php

class filterBase {
  public function filterMbValue($value) {
    $parts = explode(' ', $value);
    switch(strtolower($parts[1])) {
      case 'mib':
      case 'mb':
      case 'm':
        return trim($parts[0]);
      case 'kib':
      case 'kb':
      case 'k':
        return round(trim($parts[0])/1024, 3);
      case 'gib':
      case 'gb':
      case 'g':
        return trim($parts[0])*1024;
    }
  }

  public function filterBandwidthValueMbits($value) {
    $parts = explode(' ', $value);
    switch(strtolower($parts[1])) {
      case 'mb/s':
      case 'mbits':
      case 'm/s':
        return trim($parts[0]);
      case 'kb/s':
      case 'kbits':
      case 'k/s':
        return round(trim($parts[0])/1024, 3);
      case 'gb/s':
      case 'gbits':
      case 'g/s':
        return trim($parts[0])*1024;
    }
  }

  public function filterBandwidthValueKbits($value) {
    $parts = explode(' ', $value);
    switch(strtolower($parts[1])) {
      case 'mb/s':
      case 'mbits':
      case 'm/s':
        return trim($parts[0])*1024;
      case 'kb/s':
      case 'kbits':
      case 'k/s':
        return trim($parts[0]);
      case 'gb/s':
      case 'gbits':
      case 'g/s':
        return trim($parts[0])*1024*1024;
    }
  }

  public function ffmpegTimeToSeconds($value) {
    $value = str_replace(' ', '', $value);
    $totalSeconds = 0;

    $hours = explode('h', $value);
    if (count($hours) == 2) {
      $totalSeconds = $hours[0]*3600;
      $value = $hours[1];
    }

    $minutes = explode('mn', $value);
    if (count($minutes) == 2) {
      $totalSeconds += $minutes[0]*60;
      $value = $minutes[1];
    }

    $seconds = explode('s', $value);
    if (count($seconds) >= 2) {
      $totalSeconds += array_shift($seconds);
      $value = implode('s', $seconds);
    }

    if (strstr($value, 'ms')) {
      $totalSeconds += str_replace('ms', '', trim($value))/100;
    }

    return $totalSeconds;
  }
}
