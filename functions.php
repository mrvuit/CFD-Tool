<?php
/*
 * Tool Drawing Control follow diagram
 * Designed by vutl (Soft-Flight)
 * Email: vutrinh352@gmail.com
 * Please no delete the note
 */
	ob_start();
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	ini_set('max_execution_time', 300);
	error_reporting(-1);
	if(isset($_COOKIE['linkNode']))
		define("LINKNODE", $_COOKIE['linkNode']);
	else
		define("LINKNODE", "->");
	function dump($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	
    function runcodephp($editor_code) {
		$param = array(
		    'editor_code' => $editor_code
		);
		$url = 'http://www.writephponline.com/php-functions';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, count($param));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param); 
		$result = curl_exec($ch);
		curl_close($ch);
	    $get = explode('<div class="section result" style="margin-top:0px;float:left;margin-left: 21%;">', $result);
	    $get = explode('</div>', $get[1]);
	    // $get[0] = str_replace('<a href="http://www.writephponline.com/phpbeautifier" class="btn btn-primary pull-right run_code php_back" title="', '', $get[0]);
	    return $get[0];
	}    

	function phpbeautifier($beautify_code, $indent_format, $indent_number) {
		$param = array(
		    'indent_format' => $indent_format,
		    'beautify_code' => $beautify_code,
		    'indent_number'	=> $indent_number,
		    'beautify_sbmt' => ''
		);
		$url = 'http://www.writephponline.com/phpbeautifier';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, count($param));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param); 
		$result = curl_exec($ch);
		curl_close($ch);
	    $get = explode('<div class="highlight">', $result);
	    $get = explode('Back', $get[1]);
	    $get[0] = str_replace('<a href="http://www.writephponline.com/phpbeautifier" class="btn btn-primary pull-right run_code php_back" title="', '', $get[0]);
	    return $get[0];
	}

	// 
	function removeAllCookie() {
		setcookie('node', '', time() -1, "/"); 
		setcookie('jsonData', '', time() -1, "/"); 
		setcookie('abnormal', '', time() -1, "/"); 
	}
	function FileSizeConvert($bytes)
	{
	    $bytes = floatval($bytes);
	        $arBytes = array(
	            0 => array( "UNIT" => "TB", "VALUE" => pow(1024, 4)),
	            1 => array( "UNIT" => "GB", "VALUE" => pow(1024, 3)),
	            2 => array( "UNIT" => "MB", "VALUE" => pow(1024, 2)),
	            3 => array( "UNIT" => "KB", "VALUE" => 1024),
	            4 => array( "UNIT" => "B", "VALUE" => 1)
	        );
	    foreach($arBytes as $arItem)
	    {
	        if($bytes >= $arItem["VALUE"])
	        {
	            $result = $bytes / $arItem["VALUE"];
	            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
	            break;
	        }
	    }
	    return $result;
	}
	function convertTime($var) {
        $time  = time();
        $jun   = round(($time - $var) / 60);
        $shift = 7 * 3600;
        if (date('Y', $var) == date('Y', time())) {
            if ($jun < 1)
                $jun = 'Right now';
            if ($jun >= 1 && $jun < 60)
                $jun = "$jun minutes ago";
            if ($jun >= 60 && $jun < 1440) {
                $jun = round($jun / 60);
                $jun = "$jun hours ago";
            }
            if ($jun >= 1440 && $jun < 2880)
                $jun = "Yesterday · ".date("H:i a", $var + $shift);;
            if ($jun >= 2880 && $jun < 10080) {
                $day = round($jun / 60 / 24);
                $jun = "$day days ago";
            }
        }
        if ($jun > 10080) {
            $jun = date("d/m/Y", $var + $shift);
        }
        return $jun;
    }
	function formatLine($line) {
		$line = str_replace('span&nbsp;', 'span ', $line);
		$line = preg_replace('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', '<span style="color:#a67f59">$0</span>', $line);
		$line = preg_replace('/\'([a-zA-Z:\x7f-\xff][a-zA-Z0-9:\x7f-\xff]*)\'/', '<span style="color:#690">$0</span>', $line);
		$line = preg_replace('/\'([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\'/', '<span style="color:#690">$0</span>', $line);
		$line = preg_replace('/\'([a-zA-Z0-9])*\'/', '<span style="color:#690">$0</span>', $line);
		$line = preg_replace('/->([a-zA-Z0-9])*/', '<span style="color:#905">$0</span>', $line);
		return $line;
	}
	function replacePHP($line) {
		$dataPhp4  = array('return');
		foreach ($dataPhp4 as $key => $value) {
			$line = str_replace(''.$value.'&nbsp;', '&nbsp;<span style="color:#b34700">'.$value.'</span>&nbsp;', $line);
		}
		$dataPhp3  = array('else', 'else if', 'elseif');
		foreach ($dataPhp3 as $key => $value) {
			$line = str_replace('}&nbsp;'.$value, '}&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
			$line = str_replace('}'.$value, '}&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
		}
		$dataPhp  = array('if', 'foreach', 'for', 'in_array', 'empty', 'array', 'rand', 'while');
		foreach ($dataPhp as $key => $value) {
			$line = str_replace($value.'&nbsp;(', '<span style="color:#b34700">'.$value.'</span>&nbsp;(', $line);
			$line = str_replace($value.'(', '<span style="color:#b34700">'.$value.'</span>&nbsp;(', $line);
		}
		$dataPhp2  = array('null','true','false');
		foreach ($dataPhp2 as $key => $value) {
			$line = str_replace('=&nbsp;'.$value, '=&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
			$line = str_replace('='.$value, '=&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
			$line = str_replace('=>&nbsp;'.$value, '=>&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
			$line = str_replace('=>'.$value, '=>&nbsp;<span style="color:#b34700">'.$value.'</span>', $line);
			$line = str_replace('&nbsp;'.$value.';', '&nbsp;<span style="color:#b34700">'.$value.'</span>;', $line);
		}

	  	return $line;
	}
	function getPathRela($key, $dataNode) {
		$out = null;
		foreach ($dataNode as $path) {
			$node = explode(LINKNODE, $path);
			if(isset($node[0]) && $node[0] == $key) {
				$out .= ($out != null ? ' || ' : '').$path;
			}
		}
		return $out;
	}
	function setRecord() {
		if(!isset($_COOKIE['record']))
			return;
		$dataRecord = getRecord();
		$file = fopen("record.txt", "w") or die("Unable to open file!");
		$txt = "".($dataRecord[0] + 1)."\n".(time())."";
		fwrite($file, $txt);
		fclose($file);
		setcookie('record', true, time() -1, "/");
	}
	function getRecord() {
		$data = file_get_contents('record.txt');
		$data = explode("\n", $data);
		return $data;
	}
	function base64_url_encode($input) {
	 return strtr(base64_encode($input), '+/=', '._-');
	}
	function base64_url_decode($input) {
	 return base64_decode(strtr($input, '._-', '+/='));
	}
	function checkNodeInLine($string)
	{
		$string = preg_replace("/ {2,}/", " ",$string);
		$string = formatNode($string,true,false);
		$regex = '/^[0-9]{1,10}'.LINKNODE.'[0-9|E]{1,10}$/';
		$dataNode = explode(' ', $string);
		foreach ($dataNode as $key => $value) {
			if (preg_match($regex, $value)) {
				return true;
			}

		}
		return false;
	}
	function checkFormatAllRoute($nodeString) {
		$errorLine = array();
		$dataNodeFormat = explode(' ', formatNode($nodeString,true,false));
		$regex = '/^[0-9]{1,10}'.LINKNODE.'[0-9|E]{1,10}$/';
		$checkEnd = 0;
		$nodeUnique = getNode($dataNodeFormat);
		for ($i=1; $i < max($nodeUnique); $i++) { 
			if(!in_array($i, $nodeUnique))
				$errorLine[] = 'Node '. $i.' not found';
		}
		foreach ($dataNodeFormat as $key => $value) {
			$node = explode(LINKNODE, $value);
			//check node E
			if(isset($node[1]) && $node[1] == 'E')
				$checkEnd = 1;
			//check format error
			if (!preg_match($regex, $value)) {
			    $errorLine[] = $value.' (format error)';
			    continue;
			}
			if($node[0] == $node[1]) {
				$errorLine[] = $value.' (not be the same)';
				continue;
			}
			$countSame = 0;
			$checkFirstNode = false;
			$checkLastNode = false;
			for ($i=0; $i < count($dataNodeFormat); $i++) { 
				$findNode = explode(LINKNODE, $dataNodeFormat[$i]);
				// check same
				if($countSame >= 0) {
					if($dataNodeFormat[$i] == $value)
						$countSame++;
					if($countSame > 1) {
						$errorLine[] = $value.' (more than one)';
						$checkFirstNode = true;
						$checkLastNode = true;
						break;
					}
				}
				// check start - end node
				if($checkFirstNode == false && isset($node[0]) && isset($findNode[1])) {
					if($node[0] == $findNode[1])
						$checkFirstNode = true;
				}
				if($checkLastNode == false && isset($node[1])) {
					if($node[1] == $findNode[0])
						$checkLastNode = true;
				}
			}
			if($node[1] == 'E' || $node[0] == 1)
				continue;
			if($checkFirstNode ==  false)
				$errorLine[] = $value.' (no starting point or end point)';
			if($checkLastNode ==  false)
				$errorLine[] = $value.' (no starting point or end point)';
		}
		if($checkEnd == 0)
			$errorLine[] = 'The last node was not found (Node E)';
		return $errorLine;
	}
	function getAllRoute($explodeRoute) {
		$allRoute = array('1');
	    $max = 0;
	    $check = false;
	   	while ($check != true) {
	   		$allRoute = calculateRoute($allRoute,$explodeRoute);
	   		$check = checkBreak($allRoute);
	   		$max ++;
	   		if(count($allRoute) > 5000)
	   			$check = true;
	   	}
		array_multisort(array_map('strlen', $allRoute), $allRoute);
		return $allRoute;
	}
	function calculateRoute ($allRoute,$dataNode) {
		$newAllRoute = array();
		foreach ($allRoute as $keyAll => $valueAll) {
			$node = explode('-', $valueAll);
			$lastNode = $node[count($node)-1];
			if($lastNode == 'E')
				$newAllRoute[] = $valueAll;
			for ($i=0; $i < count($dataNode); $i++) { 
				$wayData = explode(LINKNODE, $dataNode[$i]);

				if($lastNode == $wayData[0] && strpos($valueAll, '-'.$lastNode.'-'.$wayData[1]) == false) {
					$newAllRoute[] = $valueAll.'-'.$wayData[1];
				}
			}
		}
		return $newAllRoute;
	}
	function checkBreak($allRoute) {
		foreach ($allRoute as $key => $value) {
			$node = explode('-', $value);
			$lastNode = $node[count($node)-1];
			if($lastNode != 'E')
				return false;
		}
		return true;
	}
	function getMinWay($dataRoute) {
		if(empty($dataRoute) || is_array($dataRoute) && count($dataRoute) == 0)
			return false;
		$minWay = array(substr($dataRoute, 0, 1));
		$dataRoute = explode(' ', $dataRoute);

		
		for ($i=0; $i < count($dataRoute); $i++) { 
			$way = explode(LINKNODE, $dataRoute[$i]);
			$isMax = max($minWay);
			if (isset($way[1]) && in_array($way[1], $minWay))
				continue;
			if(isset($way[1]) && $way[1] == 'E') {
				break;
			}
			if(isset($way[1]) && $way[0] < $isMax || isset($way[1]) && $way[1] < $isMax)
				continue;
			$maxNode = $way[1];
			for ($j=$i; $j < count($dataRoute); $j++) { 
				$findWay = explode(LINKNODE, $dataRoute[$j]);
				if($findWay[0] == $way[0] && $findWay[1] > $findWay[0] && $findWay[1] > $maxNode)
					$maxNode = $findWay[1];
			}
			$minWay[] = $maxNode;
		}
		return $minWay;
	}
	function getDataCodeFormFile($target_file) {
		if(file_exists('upload/'.$target_file))
			return getTableToExcel(explode("\n", file_get_contents('upload/'.$target_file)));
		return false;
	}
	function getTableToExcel($dataCode)
	{
		$node = 0;
		$dataTable = array();
		foreach ($dataCode as $line) {
			// $line_out = preg_replace('/[\n\r]+/', '', $line);
			// $line_out = str_replace(array("\n", "\r"), '', $line);
			// if(strlen($line_out) == 0)
			// 	continue;
			
			if(substr(trim($line), 0,2) == '//' && checkNodeInLine($line) == true) {
				$node++;
			} else 
				$dataTable[$node][] = $line;
		}
		return $dataTable;
	}
	function getNodeInCode($dataCode) {
		$nodeString = null;
		foreach ($dataCode as $line) {
			if(substr(trim($line), 0,2) == '//' && checkNodeInLine($line) == true)
				$nodeString .= $line;
		}
		return $nodeString;
	}
	function getDataCodeInFile($target_file) {
		$string = file_get_contents($target_file);
		if($string == null)
			return false;
		return explode("\n", $string);
	}
	function getNodeInFile($target_file) {
		if(file_exists($target_file) == false)
			return false;
		$handle = fopen($target_file, "rb");
		$nodeString = null;
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				if(substr(trim($line), 0,2) == '//' && checkNodeInLine($line) == true)
					$nodeString .= $line;
			}
			fclose($handle);
		} 
		return $nodeString;
	}
	function getLastNode($dataNode) {
		return end($dataNode) + 1;
	}
	function getNode($dataNode) {
		if(count($dataNode) == 0)
			return false;
		foreach ($dataNode as $key => $value) {
			$dataNode[$key] = explode(LINKNODE, $value)[0];
		}
		return array_unique($dataNode);
	}
	function getMap($dataNode) {
	  $mapNode[1] = '0 100';

	  $nodeDataArray = getNode($dataNode);

	  array_push($nodeDataArray,'E');
	  
	  foreach ($nodeDataArray as $key => $value) {
	    $branch = array();
	    for ($j=0; $j < count($dataNode); $j++) { 
	      if($nodeDataArray[$key] == explode(LINKNODE, $dataNode[$j])[0] ) {
	        if(isset($mapNode[explode(LINKNODE, $dataNode[$j])[1]]))
	          continue;
	        $branch[] = explode(LINKNODE, $dataNode[$j])[1];
	      }
	    }
	    if(isset($mapNode[$nodeDataArray[$key]-1]) || $nodeDataArray[$key] == 1) {
	      if($nodeDataArray[$key] == 1) {
	      	$before = explode(' ', $mapNode[1]);
	      } else {
	      	$before = explode(' ', $mapNode[$nodeDataArray[$key]]);
	      }
	        
	      if(count($branch) == 2) {
	        if(in_array($branch[0], $nodeDataArray) && in_array($branch[1], $nodeDataArray)) {
	          	if(checkLinkMap($dataNode,$mapNode,$nodeDataArray[$key]-1,$nodeDataArray[$key],$branch[0]) == true) 
	          		$mapNode[$branch[0]] = ($before[0] + 150).' '.($before[1] - 100);
	          	else
	          		$mapNode[$branch[0]] = ($before[0] + 150).' '.($before[1] + 100);
	          $mapNode[$branch[1]] = ($before[0] - 150).' '.($before[1] + 100);
	        }
	      } else if(count($branch) == 1) {
	        if( in_array($branch[0], $nodeDataArray)) {
	      		$mapNode[$branch[0]] = $before[0].' '.($before[1]+100);
	        }
	      }
	    }
	  }
	  return $mapNode;
	}
	function checkLinkMap($dataNode,$mapNode,$node1,$node2,$branch) {
		if(!isset($mapNode[$node1]) || !isset($mapNode[$node2]))
			return false;
		if(!in_array($branch.LINKNODE.($node1), $dataNode))
			return false;
		$data[1] = explode(' ', $mapNode[$node1]);
		$data[2] = explode(' ', $mapNode[$node2]);
		if($data[1][0] + 150 == $data[2][0] && $data[1][1] + 100 == $data[2][1])
			return true;
		return false;
	}
	function checkTheSameMap($mapNode) {
		foreach ($mapNode as $key => $value) {
			$mapXY = explode(' ', $value);
			for ($i=$key + 1; $i < count($mapNode); $i++) { 
				if($value == $mapNode[$i]) {
					$mapNode[$i] = ($mapXY[0] + 100).' '.$mapXY[1];
				}
			}
		}
		return $mapNode;
	}

	function convertData($dataNode, $lastNode, $minWay,$explodeRoute, $typeFormat) {
		if($typeFormat == 1) {
			$mapNode = getMap($explodeRoute);
			$mapNode = checkTheSameMap($mapNode);	
			$script = '{"key":-1, "category":"Start", "loc":"0 0", "text":"Start"},'.PHP_EOL.'';
			$script .= '{"key":0, "category":"End", "loc":"'.$mapNode['E'].'", "text":"End"}, '.PHP_EOL.'';
		} else {
			$loc[1] = 0;
			$loc[0] = 0;
			$script = '{"key":-1, "category":"Start", "loc":"0 0", "text":"Start"},'.PHP_EOL.'';
			$script .= '{"key":0, "category":"End", "loc":"0 '.(80*count($dataNode)).'", "text":"End"}, '.PHP_EOL.'';
		}
		foreach ($dataNode as $key => $value) {
			if($typeFormat == 1) { 
				$locXY = $mapNode[$value];
			} else {
				$loc0 = rand(100,300);
				if (in_array($value, $minWay)) {
					$loc0 = 0;
				}
					$loc[1] += 80;
					$locXY = $loc0.' '.$loc[1];
				}
				if($value == 'E') 
					continue;
			if($value == $lastNode - 1) {
				$script .= '{"key":'.$value.', "loc":"'.$locXY.'", "text":"'.$value.'"} '.PHP_EOL.'';
			}
			else
				$script .= '{"key":'.$value.', "loc":"'.$locXY.'", "text":"'.$value.'"}, '.PHP_EOL.'';
		}
		return $script;
	}
	function getCurviness($dataNode) {
		$dataCurviness = array();
		for ($i=0; $i < count($dataNode); $i++) { 
			$node = explode(LINKNODE, $dataNode[$i]);
			for ($j=0; $j < count($dataNode); $j++) { 
				$findNode = explode(LINKNODE, $dataNode[$j]);
				if($node[0] == $findNode[1] && $node[1] == $findNode[0]) {
					$dataCurviness[$i] = 30;
				}
			}
			if(!isset($dataCurviness[$i]))
				$dataCurviness[$i] = 0;
		}
		return $dataCurviness;
	}
	function getTextLink($dataNode) {
		$textLink = array();
		for ($i=0; $i < count($dataNode); $i++) { 
			$node = explode(LINKNODE, $dataNode[$i]);
			for ($j=$i+1; $j < count($dataNode); $j++) { 
				$findNode = explode(LINKNODE, $dataNode[$j]);
				if($node[0] == $findNode[0]) {
					$textLink[$i] = 'True';
					$textLink[$j] = 'False';
				}
			}
			if(!isset($textLink[$i]))
				$textLink[$i] = '';
		}
		return $textLink;
	}
	function convertLink($dataNode) {
		$script = null;
		$dataCurviness = getCurviness($dataNode);
		$textLink = getTextLink($dataNode);
		$script = '{"from":-1, "to":1, "text": "", "curviness": 0},'.PHP_EOL;
		foreach ($dataNode as $key => $value) {
			$value = explode(LINKNODE, $value);
			if($value[1] == 'E') {
				if( $key == count($dataNode) - 1) {
					$script .= '{ "from": '.$value[0].', "to": 0, "text": "'.($textLink[$key]).'", "curviness": '.($dataCurviness[$key]).' } '.PHP_EOL;
					break;
				} else {
					$script .= '{ "from": '.$value[0].', "to": 0, "text": "'.($textLink[$key]).'", "curviness": '.($dataCurviness[$key]).' }, '.PHP_EOL;
					continue;
				}
			}
			if($key == count($dataNode) - 1)
				$script .= '{ "from": '.$value[0].', "to": '.$value[1].', "text": "'.($textLink[$key]).'", "curviness": '.($dataCurviness[$key]).' } '.PHP_EOL;
			else
				$script .= '{ "from": '.$value[0].', "to": '.$value[1].', "text": "'.($textLink[$key]).'", "curviness": '.($dataCurviness[$key]).' }, '.PHP_EOL;
		}
		return $script;
	}

	function calcuNode($nodeString, $calculation, $number) {
		$dataNode = explode(' ', $nodeString);
		$out = '// ';
		foreach ($dataNode as $key => $value) {
			$value = explode(LINKNODE, $value);
			if($key > 0)
				$out .= ' || ';
			if($calculation == 0)
				$out .= ($value[0] - $number).LINKNODE.(is_numeric($value[1]) ? $value[1] - $number : $value[1]);
			else
				$out .= ($value[0] + $number).LINKNODE.(is_numeric($value[1]) ? $value[1] + $number : $value[1]);
		}
		return $out."\n";
	}
	function fixNode($code,$calculation = 0, $number = 1) {
		$dataCode = explode("\n", $code);
		$out = array();
		foreach ($dataCode as $line) {
			if(substr(trim($line), 0,2) == '//' && checkNodeInLine($line) == true) {
				$line = preg_replace("/ {2,}/", " ",formatNode($line,false,false));
				$line = calcuNode($line, $calculation, $number);
			} 
			$out[] = $line;
		}
		return $out;
	}
	function formatNode($string,$repLine, $repSpace) {
		$string = str_replace('//', '', $string);
		$string = str_replace(LINKNODE.' ', LINKNODE, $string);
		$string = str_replace(' '.LINKNODE, LINKNODE, $string);
		$string = str_replace('||', ' ', $string);
		$string = trim($string);
		if($repLine == true)
			$string = preg_replace('/\s+/', ' ', $string);
		if($repSpace == true)
			$string = str_replace(' ', '', $string);
		$string = strip_tags ($string);
		return $string;
	}
	function getPathImpossible($allPath, $arrLinkImpossible) {
		$pathImpossible = array();
		foreach ($arrLinkImpossible as $keyLink => $valueLink) {
			foreach ($allPath as $path) {
				$checkImpossible = checkImpossible($path, $valueLink);
				if($checkImpossible == true)
					$pathImpossible[] = $path;
			}
		}
		array_multisort(array_map('strlen', $pathImpossible), $pathImpossible);
		return array_unique($pathImpossible);
	}
	function checkImpossible($path, $valueLink) {
		if (strpos($path, str_replace(LINKNODE, '-', $valueLink[0])) !== false && strpos($path, str_replace(LINKNODE, '-', $valueLink[1])) !== false)  {
			$dataPath = explode('-', $path);
			$substr = array();
			$nodeFirst = explode(LINKNODE, $valueLink[0]);
			$nodeLast = explode(LINKNODE, $valueLink[1]);
			for ($i=0; $i < count($dataPath); $i++) { 
				if($dataPath[$i] == $nodeFirst[0]) {
					$substr[0] = $i;
				}
			}
			for ($j=count($dataPath) - 1; $j >= 0; $j--) { 
				if($dataPath[$j] == $nodeLast[1]) {
					$substr[1] = $j;
				}
			}
			if($path == '1-2-3-4-6-3-7-8-9-10-11-12-13-14-15-16-8-17-18-19-20-1-21-E') {
				dump($nodeFirst);
				dump($nodeLast);
		// dump($substr);
		dump($dataPath);

			}

			if(isset($substr[0]) && isset($substr[1])) {
				for ($i=$substr[0]; $i <= $substr[1] ; $i++) { 
					$arr[] = $dataPath[$i];
				}
				$count = 0;
				if(isset($arr))
				foreach ($arr as $node) {
					if($node == $nodeFirst[0])
						$count++;
					if($node == $nodeFirst[1])
						$count++;
					if($node == $nodeLast[0])
						$count++;
					if($node == $nodeLast[1])
						$count++;
				}
				if($count == 4)
					return true;
				return false;
			}
		}
		return false;
	}
	function getAbnormal() {
		if(!isset($_COOKIE['abnormal']))
			return array();
		$abnormalCookie = explode('||', trim($_COOKIE['abnormal'],'||'));
		$abnormalCookie = array_unique($abnormalCookie);
		foreach ($abnormalCookie as $key => $value) {
			$abnormalCookie[$key] = explode(' ', $value);
		}
		return $abnormalCookie;
	}