<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<?php
	ini_set('max_execution_time', 3);
    function isCode($line) {
      $line = trim($line);
      if(empty($line))
        return 0;
      if(substr($line, 0, 2) == '//')
        return 0;
      return 1;
    }
    function getEndCode($dataCode) {
      foreach ($dataCode as $key => $value) {
        $j = 0;
        $count = 0;
        $else=0;
        if(strpos_array($value['value'], '{') == true) {
        if(strpos_array($value['value'], '{') == true && strpos_array($value['value'], '}') == true)
              $else=1;
          for ($i=$key; $i < count($dataCode); $i++) { 
            if(strpos_array($dataCode[$i]['value'], '}') == true) {
              $count--;
              if($else == 1) {
                $count++;
                $else = 0;
              } else {
                if($count==0) {
                 $dataCode[$key]['endcode'] = $i;
                 break;
                }
              }
            }
            if(strpos_array($dataCode[$i]['value'], '{') == true) {
              $count++;
            }
            if($count==0) {
             $dataCode[$key]['endcode'] = $i;
              
             break;
            }
          }
        }
      }
      return $dataCode;
    }
    function getLevelForCode($dataCode) {
      $level = 0;
      foreach ($dataCode as $key => $value) {
        if($dataCode[$key]['isCode'] == 1) {
          if(strpos_array($value['value'], '}') == true) {
            $level--;
             if($value['isType'] != 'na' && isset($dataCode[$key]['start']))
              $dataCode[$key]['level'] = $level;
            if(strpos_array($value['value'], 'else') == true) {
               $dataCode[$key]['level'] = $level;
              $level++;

            }
            continue;
          }
          if($value['isType'] != 'na' && isset($dataCode[$key]['start']))
          $dataCode[$key]['level'] = $level;
          if(strpos_array(trim($value['value']), '{') == true) {
            $level++;
          }
          
        }
      }
      return $dataCode;
    }
    function strpos_array($haystack, $needles) {
      if ( is_array($needles) ) {
        foreach ($needles as $str) {
          if ( is_array($str) ) {
            $pos = strpos_array($haystack, $str);
          } else {
            $pos = strpos($haystack, $str);
          }
          if ($pos !== FALSE) {
            return $pos;
          }
        }
      } else {
        return strpos($haystack, $needles);
      }
    }
    function checkTypeCode($line) {
      $ifData = array('if', 'elseif', 'try');
      $loopData = array('for','foreach');
      if(strpos_array($line,$ifData) == true && substr(trim($line), 0, 1) != '$' || substr(trim($line), 0, 2) == 'if')
        return 'if';
      if(strpos_array($line,$loopData) == true && substr(trim($line), 0, 1) != '$')
        return 'loop';
      if(substr(trim($line), 0, 6) == 'return' || strpos_array($line,'return') == true)
        return 'end';
      if(substr(trim($line), 0, 1) == '$' || strpos_array($line,'$') == true)
        return 'var';
      return 'na';
    }
    function autoFormatFirst($dataCode) {
      $node = 0;
      $continue = 1;
      $nowType = null;
      foreach ($dataCode as $key => $value) {
        if($value['isCode'] == 0 || $value['isType'] == 'na' || $value['isType'] == 'var')
          $continue = 1;
        else
          $continue = 0;
        if(trim($value['value']) == '}' || strpos_array($value['value'], array('else', '->')) == true)
          $nowType = null;
        if($nowType != $value['isType'] && $value['isType'] != 'na' ||  $nowType == $value['isType'] && $value['isType'] != 'var') {
          $node++;
          $dataCode[$key]['start'] = getStartNode($value, $node);
          $dataCode[$key]['end'] = ($value['isType'] == 'end' ? 'E' : getEndNode($value, $node));
          $nowType = $value['isType'];
        }
      }
      
      return $dataCode;
    }
    function getStartNode($dataLine, $node) {
      if($dataLine['isCode'] == 0 || $dataLine['isType'] == 'na')
        return false;
      return $node;
    }
    function getEndNode($dataLine, $node) {
      if($dataLine['isCode'] == 0 || $dataLine['isType'] == 'na')
        return false;
      return ($node+1);
    }
    function autoFormatFalse($dataCode) {
      $nowType = null;
      $conditionData = array('if', 'loop');
      foreach ($dataCode as $key => $value) {
        if($value['isCode'] == 0 || $value['isType'] == 'na' || $value['isType'] == 'var')
          $continue = 1;
        else
          $continue = 0;
        if(trim($value['value']) == '}' || strpos_array($value['value'], array('else', '->')) == true)
          $nowType = null;
        if($nowType != $value['isType'] && $value['isType'] != 'na' ||  $nowType == $value['isType'] && $value['isType'] != 'var') {
          if (in_array($value['isType'], $conditionData)) {
            $dataCode[$key]['end2'] = getEndNode2($dataCode,$key);
          }
          $nowType = $value['isType'];
        }
      }
      return $dataCode;
    }


    function getEndNode22($dataCode, $key) {
      $count = 0;
      $j = 0;
      $elseif = 0;
      for ($i=$key; $i < count($dataCode); $i++) { 
        if(strpos_array($dataCode[$i]['value'],'elseif') == true && $dataCode[$i]['start'] != $dataCode[$key]['start'])
          $elseif = 1;
        if(strpos_array($dataCode[$i]['value'],'}')) {
          $count--;
          if($count == 0) { 
            $j = $i;
            break;
          }
        }
        if(strpos_array($dataCode[$i]['value'],'{') == true) {
          $count++;
        }
        if($count == 0) {
          $j = $i;
          break;
        }
      }
      if($elseif == 1) $j--;
      for ($i=$j+1; $i < count($dataCode); $i++) { 
       if(isset($dataCode[$i]['start']))
        return $dataCode[$i]['start'];
      }
        return 'E';
    }
    function getEndNode2($dataCode, $key) {
      for ($i=$key; $i < count($dataCode); $i++) { 
       if(isset($dataCode[$i]['start']) && isset($dataCode[$key]['endcode']) && $i > $dataCode[$key]['endcode']) {
        if(strpos_array($dataCode[$i-1]['value'],'elseif') == true)
          return $dataCode[$i]['start'] - 1;
        else
          return $dataCode[$i]['start'];
       }
      }
        return 'E';
    }
    function autoFormatLoop($dataCode) {
      foreach ($dataCode as $key => $value) {
        if(isset($dataCode[$key]['isType']) && $dataCode[$key]['isType'] == 'loop') {
          for ($i=$key+1; $i <= $dataCode[$key]['endcode']; $i++) { 
            if($i == $dataCode[$key]['endcode'] - 1 && isset($dataCode[$i]['end'])) {
              $dataCode[$i]['end'] = $dataCode[$key]['start'];
              continue;
            }
            if(isset($dataCode[$i]['end']) && $dataCode[$i]['end'] == $dataCode[$key]['end2'])
              $dataCode[$i]['end'] = $dataCode[$key]['start'];
            if(isset($dataCode[$i]['end2']) && $dataCode[$i]['end2'] == $dataCode[$key]['end2'])
              $dataCode[$i]['end2'] = $dataCode[$key]['start'];
          }
        }
      }
      return $dataCode;
    }
    function autoFormatLoop2($dataCode) {
      foreach ($dataCode as $key => $value) {
        if(isset($dataCode[$key]['isType']) && $dataCode[$key]['isType'] == 'loop') {
          $data = array();
          for ($i=$key+1; $i <= $dataCode[$key]['endcode']; $i++) { 
            if(isset($dataCode[$i]['endcode'])) {
              $data[$i] = 0;
              for ($j=$i; $j < $dataCode[$i]['endcode']; $j++) { 
                if(isset($dataCode[$j]['endcode'])) {
                  $data[$i] = 1;
                  break;
                }
              }
              
            }
          }
          if(count($data) > 0) {
            foreach ($data as $key_min => $value_min) {
              for ($i=$dataCode[$key_min]['endcode'] ; $i < count($dataCode); $i++) {
                if(isset($dataCode[$i]['level'])) {
                  if(isset($dataCode[$key_min]['end2']) && $dataCode[$i]['level'] != $dataCode[$key_min]['level']) {
                    $dataCode[$key_min]['end2'] = $dataCode[$key]['start'];
                  }
                  break;
                }
              }
            }
          }
         for ($i=$key+1; $i <= $dataCode[$key]['endcode']; $i++) { 
           if(isset($dataCode[$i]['end2']) && $dataCode[$i]['end2'] == $dataCode[$key]['start']) {
            for ($j=$dataCode[$i]['endcode']; $j > 0; $j--) { 
              if($dataCode[$j]['isType'] != 'na' && $dataCode[$j]['isCode'] == 1 && !isset($dataCode['end2'])) {
                $dataCode[$j]['end'] = $dataCode[$key]['start'];
                break;
              }
            }
           }
         }
        }
      }
      return $dataCode;
    }
    function autoFormatIf($dataCode) {
      foreach ($dataCode as $key => $value) {
        if($dataCode[$key]['isType'] == 'if' && isset($dataCode[$key]['endcode'])) {
          $end = 0;
          $i =  $dataCode[$key]['endcode'];
          $k = 0;
          while ($end == 0) {
            if(!isset($dataCode[$i]['endcode']))
              $end = $i;
            else
              $i = $dataCode[$i]['endcode'];
            if($k == 1000) 
              break;
            $k++;
          }
          $dataCode[$key]['endblock'] = $end;
          $end = 0;
        }
      }
      return $dataCode;
    }
    $dataCode = array();
    $dataCodeExplode = explode("\n", $_POST['code']);

    foreach ($dataCodeExplode as $key => $value) {
      $dataCode[$key]['isCode'] = isCode($value);
      $dataCode[$key]['isType'] = checkTypeCode($value);
      $dataCode[$key]['value'] = $value;
    }


    $dataCode = autoFormatFirst($dataCode);
    $dataCode = getLevelForCode($dataCode);
    $dataCode = getEndCode($dataCode);
    $dataCode = autoFormatFalse($dataCode);
    $dataCode = autoFormatIf($dataCode);
    $dataCode = autoFormatLoop($dataCode);
    $dataCode = autoFormatLoop2($dataCode);
    echo '<pre class="prettyprint lang-php">';
    if($_POST['type'] == 0) {
      foreach ($dataCode as $key => $value) {
        $value['value'] = str_replace(' ', '&nbsp;', $value['value']);
        echo nl2br($value['value']).'<br>';
      }
      exit();
    }
    if($_POST['type'] == 1) {
      dump($dataCode);
      exit();
    }
    foreach ($dataCode as $key => $value) {
      echo ($_POST['type'] == 3 ? $key : '');
     if(isset($value['start'])) 
      echo '//'.$value['start'].'->'.$value['end'].(isset($value['end2']) ? ' || '. $value['start'].'->'.$value['end2'] : '').'';
      echo (isset($value['level']) && $_POST['type'] == 3 ? ' (Lv: '.$value['level'].')' : '');
      echo (isset($value['endcode']) && $_POST['type'] == 3 ? ' (Endcode: '.$value['endcode'].')' : '');
      echo (isset($value['endblock']) && $_POST['type'] == 3 ? ' (Endblock: '.$value['endblock'].')' : '');
      echo '<br>';
      $value['value'] = str_replace(' ', '&nbsp;', $value['value']);
      echo nl2br($value['value']).'<br>';
    }
    echo '</pre>';