<?php

class audioBitRate extends filterBase {
  
  public function filter($value) {
    return $this->filterBandwidthValueKbits($value);
  }
}