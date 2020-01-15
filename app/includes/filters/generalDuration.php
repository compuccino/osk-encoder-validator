<?php

class generalDuration extends filterBase {
  
  public function filter($value) {
    return $this->ffmpegTimeToSeconds($value);
  }
}