<?php

class videoHeight extends filterBase {
  
  public function filter($value) {
    return str_replace([' ', 'pixels'], '', strtolower($value));
  }
}