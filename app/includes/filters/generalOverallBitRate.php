<?php

class generalOverallBitRate extends filterBase {
  
  public function filter($value) {
    return $this->filterBandwidthValueMbits($value);
  }
}