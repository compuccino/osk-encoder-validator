<?php

class audioSamplingRate extends filterBase {
  
  public function filter($value) {
    return str_replace(' khz', '', strtolower($value));
  }
}