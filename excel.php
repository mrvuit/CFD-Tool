<?php
require_once('HtmlExcel.php');
require_once('functions.php');
if(!isset($_POST['dataTable'])) exit();
	$dataTable = unserialize(base64_url_decode($_POST['dataTable']));
	$dataNode = unserialize(base64_url_decode($_POST['dataNode']));
	if(isset($_POST['lineemtpy']) && $_POST['lineemtpy'] == 'yes') {
		foreach ($dataTable as $key => $value) {
			foreach ($dataTable[$key] as $keyCh => $line) {
				if(trim($line) == '') {
					unset($dataTable[$key][$keyCh]);
				}
			}
		}
	}
	if(isset($_POST['comment']) && $_POST['comment'] == 'yes') {
		foreach ($dataTable as $key => $value) {
			foreach ($dataTable[$key] as $keyCh => $line) {
				if(substr(trim($line), 0, 2) == "//") {
					unset($dataTable[$key][$keyCh]);
				}
			}
		}
	}
	$css = "
		  table {
		  	table-layout: fixed;
		  	word-wrap: break-word;
		  	border: 1px solid #fff;

		  }
		  tr {
		  	border: 1px solid #fff;
		  	padding: 4px;

		  }
		  th {
		  	border: 1px solid #fff;
		  	text-align: center;
		  }
		  td {
		  	max-width: 900px;
		  	padding: 4px;
		  	border: 1px solid #fff;
		  	word-wrap: break-word;
		  }
	";
	$excel = '
<table>
<tr>
<td>
</td>
</tr>
</table>
	<table style="border:2px solid #000;width: 1100px;table-layout: fixed;'.(isset($_POST['format']) && $_POST['format'] == 'true' ? 'color:#003366;' : '').'">
	    <thead>
	      <th style="border: 1px solid #000;text-align:center;background:#eee;font-weight: bold;font-size:14pt;font-family: Arial;">Code</th>
	      <th style="border: 1px solid #000;text-align:center;background:#eee;font-weight: bold;font-size:14pt;font-family: Arial;">Node</th>
	      '.(isset($_POST['path']) && $_POST['path'] == 'yes' ? '<th style="border: 1px solid #000;text-align:center;background:#eee;font-weight: bold;font-size:14pt;font-family: Arial;">Path</th>' : '').'
	    </thead>
		  <tbody style="max-width:1100px;">
		  	<tr>
		  		<td style="border: 1px solid #000;"></td>
		  		<td style="border:1px solid #000; color:red;font-weight: bold;text-align:center;vertical-align: middle;">START</td>
		  		'.(isset($_POST['path']) && $_POST['path'] == 'yes' ? '<td style="border: 1px solid #000;"></td>' : '').'
		  	</tr>';
			foreach ($dataTable as $key => $value) {
		    	$excel .= '<tr><td style="border: 1px solid #777;'.(isset($_POST['style']) && $_POST['style'] == 'italic' ? 'font-style: italic;' : '').'font-family: Arial;padding:10px;word-wrap: break-word;">';
		      foreach ($dataTable[$key] as $line) {
		  			$line = str_replace(' ', '&nbsp;', $line);
					if(isset($_POST['format']) && $_POST['format'] == 'true') {
						if(substr(trim($line), 0, 2) == '//')
						$line = '<span style="color:#777;">'.$line.'</span>';
				      	$line = str_replace(' ', '&nbsp;', $line);
				      	$line = formatLine($line);
				      	$line = replacePHP($line);
					}
		      		$excel .= nl2br($line);
		      }
		      $excel .= '</td>';
		      $excel .= '<td style="border:1px solid #777;color:red;font-weight: bold;font-size:13px;text-align:center;vertical-align: middle;">'.$key.'</td>';
			  if(isset($_POST['path']) && $_POST['path'] == 'yes') {
			  	$excel .= '<td style="border:1px solid #777;font-size:13px;text-align:center;vertical-align: middle;">'.getPathRela($key, $dataNode).'</td>';
			  }
			  $excel .= '</tr>';
		    } 
		  $excel .= '<tr>
		  		<td style="border: 1px solid #000;"></td>
		  		<td style="border:1px solid #000; color:red;font-weight: bold;text-align:center;vertical-align: middle;">END</td>
		  		'.(isset($_POST['path']) && $_POST['path'] == 'yes' ? '<td style="border:1px solid #000; color:red;font-weight: bold;text-align:center;vertical-align: middle;"></td>' : '').'
		  	</tr>';
		  $excel .= '</tbody>';
  		$excel .= '</table>';

if(isset($_POST['preview'])) {
	echo $excel;
	exit();
}

$xls = new HtmlExcel();
$xls->setCss($css);
$xls->addSheet("Overview", $excel);
$xls->headers();
echo $xls->buildFile();
