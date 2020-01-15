<?php

class audioDuration extends filterBase {
  
  public function filter($value) {
    return $this->ffmpegTimeToSeconds($value);
  }
}