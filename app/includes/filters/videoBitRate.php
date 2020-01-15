<?php

class videoBitRate extends filterBase {
  
  public function filter($value) {
    return $this->filterBandwidthValueMbits($value);
  }
}