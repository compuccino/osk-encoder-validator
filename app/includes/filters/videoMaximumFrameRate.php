<?php

class videoMaximumFrameRate extends filterBase {
  
  public function filter($value) {
    return explode(' ', $value)[0];
  }
}