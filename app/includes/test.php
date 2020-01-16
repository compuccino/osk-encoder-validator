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
}