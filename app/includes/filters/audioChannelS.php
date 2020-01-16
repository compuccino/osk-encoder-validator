<?php

class audioChannelS extends filterBase {
  
  public function filter($value) {
    return str_replace([' channels', ' channel'], '', $value);
  }
}