<?php

include('includes/filters/filterBase.php');

class test {
  protected $validateValues = [];

  public function __construct() {
    if (isset($_GET['validate'])) {
      $file = '/validation-config/' . $_GET['validate'] . '.yaml';
      if (file_exists($file)) {
        $config = yaml_parse_file($file);
        foreach ($config as $headerKey => $values) {
          foreach ($values as $mainKey => $rules) {
            $this->validateValues[$headerKey . ucfirst($mainKey)] = $rules;
          }
        }
      }
    }
  }

  function testValue($header, $key, $value) {
    $realKey = $this->camelCase($header . ' ' . $key);
    $realValue = $this->getRealValue($realKey, $value);
    // Run the tests if they exists
    if (isset($this->validateValues[$realKey])) {
      $col = '<td>' . $this->writtenRules($this->validateValues[$realKey]) . '</td>';
      if ($this->parser($this->validateValues[$realKey], strtolower($realValue))) {
        $state = "table-success";
      } else {
        $state = "table-danger";
      }
    } else {
      $state = "";
      $col = '<td></td>';
    }

    $output = "<tr id=\"$realKey\" class=\"$state\">\n";
    $output .= "<td>$key</td>\n<td>$realValue</td>\n";
    $output .= "$col\n</tr>";
    return $output;
  }

  function getRealValue($key, $value) {
    if (file_exists('includes/filters/' . $key . '.php')) {
      include('includes/filters/' . $key . '.php');
      $object = new $key();
      return $object->filter($value);
    } else {
      return strtolower($value);
    }
  }
  
  // http://www.mendoweb.be/blog/php-convert-string-to-camelcase-string/
  function camelCase($str, array $noStrip = []) {
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    $str = ucwords($str);
    $str = str_replace(" ", "", $str);
    $str = lcfirst($str);
  
    return $str;
  }

  function parser($rules, $testValue) {
    foreach ($rules as $key => $value) {
      switch ($key) {
        case 'and':
          return $this->and($value, $testValue);
        case 'or':
          return $this->or($value, $testValue);
        case 'min':
          return $this->min($value, $testValue);
        case 'max':
          return $this->max($value, $testValue);
        case 'contains':
          return $this->contains($value, $testValue);
        case 'not-contains':
          return $this->notContains($value, $testValue);
        case 'exact':
          return $this->exact($value, $testValue);
        case 'not-exact':
          return $this->notExact($value, $testValue);
        default:
          echo "The rule $key is not known";
          exit;
      }
    }
  }

  function writtenRules($rules, $break = '') {
    $parts = [];
    if(array_keys($rules) !== range(0, count($rules) - 1)) {
      foreach ($rules as $key => $value) {
        $parts[] = $this->writtenRule($key, $value);
      }
    } else {
      foreach ($rules as $ruleArray) {
        foreach ($ruleArray as $key => $value) {
          $parts[] = $this->writtenRule($key, $value);
        }
      }
    }
    return implode($break, $parts);  
  }

  function writtenRule($key, $value) {
    switch ($key) {
      case 'and':
        return $this->writtenRules($value, ' and ');
      case 'or':
        return $this->writtenRules($value, ' or ');
      default:
        return '<strong>' . $key . '</strong>' . ': ' . $value;
    }
  }

  function and($rules, $testValue) {
    foreach ($rules as $rule) {
      if ($this->parser($rule, $testValue) == FALSE) return FALSE;
    }
    return TRUE;
  }

  function or($rules, $testValue) {
    foreach ($rules as $rule) {
      if ($this->parser($rule, $testValue)) return TRUE;
    }
    return FALSE;
  }

  function min($value, $testValue) {
    return $testValue >= $value ? TRUE : FALSE;
  }

  function max($value, $testValue) {
    return $testValue <= $value ? TRUE : FALSE;
  }

  function contains($value, $testValue) {
    return strpos(strtolower((string) $testValue), strtolower((string) $value)) !== false ? TRUE : FALSE;
  }

  function notContains($value, $testValue) {
    return strpos(strtolower((string) $testValue), strtolower((string) $value)) === false ? FALSE : TRUE;
  }

  function exact($value, $testValue) {
    return strtolower((string) $value) == strtolower((string) $testValue) ? TRUE : FALSE;
  }

  function notExact($value, $testValue) {
    return strtolower((string) $value) == strtolower((string) $testValue) ? FALSE : TRUE;
  }

  // Test frames
  function testFrames($frames) {
    $this->testKeyFrames($frames);
    $this->testDtsPtsAlignment($frames);
    $this->testDtsPtsStart($frames);
    $this->testFrameOrder($frames);
  }

  // Test keyframes
  function testKeyFrames($frames) {
    if (isset($this->validateValues['framesKeyFrameDistance'])) {
      foreach ($frames['video'] as $key => $frameInfo) {
        $i = 0;
        $longestInterval = 0;
        $shortestInterval = NULL;
        foreach ($frameInfo as $frame) {
          if ($frame['key_frame']) {
            if ($i > $longestInterval) {
              $longestInterval = $i;
            }
            if ($shortestInterval == NULL || $i && $i < $shortestInterval) {
              $shortestInterval = $i;
            }
            $i = 1;
          } else {
            $i++;
          }
        }
        
        $class = "table-success";
        $message = "The shortest interval between video key frames on stream #$key was $shortestInterval and the longest $longestInterval.";
        $adds = [];
        if ($this->validateValues['framesKeyFrameDistance']['min'] > $shortestInterval) {
          $adds[] = "The shortest value should be minimum " . $this->validateValues['framesKeyFrameDistance']['min'];
          $class = "table-danger";
        }

        if ($this->validateValues['framesKeyFrameDistance']['max'] < $longestInterval) {
          $adds[] = "The longest value should be maximum " . $this->validateValues['framesKeyFrameDistance']['max'];
          $class = "table-danger";
        }

        $message .= ' ' . ucfirst(implode(' and ', $adds));

        echo "<table class=\"table table-hover table-striped\"><tr class=\"$class\"><td>$message</td></tr></table>";
      }
    }
  }

  function testDtsPtsAlignment($frames) {
    if (isset($this->validateValues['framesDtsPtsDiff'])) {
      foreach ($frames['video'] as $key => $frameInfo) {
        $message = "The longest diff between DTS and PTS on stream #$key allowed is " . $this->validateValues['framesDtsPtsDiff'] . '.';
        foreach ($frameInfo as $frameNr => $frame) {
          if ($frame['pkt_dts_time'] !== 'N/A' && $frame['pkt_pts_time'] !== 'N/A') {
            $timeDiff = round(abs($frame['pkt_pts_time']-$frame['pkt_dts_time']), 6);
            if ($timeDiff > $this->validateValues['framesDtsPtsDiff']) {
              $errorMessage = "Frame $frameNr is $timeDiff off.";
              echo "<table class=\"table table-hover table-striped\"><tr class=\"table-danger\"><td>$message $errorMessage<td></td></tr></table>";
              return;
            }
          }
        }
        echo "<table class=\"table table-hover table-striped\"><tr class=\"table-success\"><td>$message<td></td></tr></table>";
      }
    }
  }

  function testDtsPtsStart($frames) {
    if (isset($this->validateValues['framesDtsPtsStartMax'])) {
      $message = "DTS and PTS start time should be lower then " . $this->validateValues['framesDtsPtsStartMax'] . '.';
      foreach ($frames['video'] as $key => $frameInfo) {
        $adds = [];
        $class = "table-success";
        if ($frameInfo[0]['pkt_pts_time'] > $this->validateValues['framesDtsPtsStartMax']) {
          $adds[] = " PTS on stream #$key is larger at " . $frameInfo[0]['pkt_pts_time'];
          $class = "table-danger";
        }
        if ($frameInfo[0]['pkt_dts_time'] > $this->validateValues['framesDtsPtsStartMax']) {
          $adds[] = " DTS on stream #$key is larger at " . $frameInfo[0]['pkt_dts_time'];
          $class = "table-danger";
        }
        $message .= ' ' . ucfirst(implode(' and ', $adds));
        echo "<table class=\"table table-hover table-striped\"><tr class=\"$class\"><td>$message<td></td></tr></table>";
      }
    }
  }

  function testFrameOrder($frames) {
    if (isset($this->validateValues['framesFrameOrder'])) {
      $message = "The frame order should be " . $this->validateValues['framesFrameOrder'] . '.';
      foreach ($frames['video'] as $key => $frameInfo) {
        foreach ($frameInfo as $frameNr => $frame) {
          if ($frame['key_frame']) {
            $start = strtoupper(substr($this->validateValues['framesFrameOrder'], 1));
          } else if ($start) {
            $next = substr($start, 0, 1);
            if ($next != $frame['pict_type']) {
              echo "<table class=\"table table-hover table-striped\"><tr class=\"table-danger\"><td>$message We saw another pattern in video stream #$key.<td></td></tr></table>";
              return;
            }
            $start = substr($start, 1);
          }
        }
      }
      echo "<table class=\"table table-hover table-striped\"><tr class=\"table-success\"><td>$message<td></td></tr></table>";
    }
  }
}