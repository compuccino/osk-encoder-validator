<?php

class generalFileSize extends filterBase {
  
  public function filter($value) {
    return $this->filterMbValue($value);
  }
}