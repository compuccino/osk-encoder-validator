<?php

class videoDuration extends filterBase {
  
  public function filter($value) {
    return $this->ffmpegTimeToSeconds($value);
  }
}