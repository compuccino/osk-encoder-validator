<?php

class videoFrameRate extends filterBase {
  
  public function filter($value) {
    return explode(' ', $value)[0];
  }
}