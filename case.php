<?php include 'functions.php'; ?>
<?php if(isset($_POST['submit'])) : ?>
	<?php
		
		function getDataTestCase($dataPost) {
			$data['node'] = array();
			$data['tc'] = array();
			foreach ($dataPost as $line) {
				$lineEx = explode(' ', trim($line));
				if(checkNodeInLine($lineEx[0]) == true) {
					$data['node'][] = $lineEx[0];
					$data['tc'][$lineEx[0]] = str_replace($lineEx[0], '', trim($line));
				}
			}
			return $data;
		}
		function checkNodeExistInPath($node,$path,$k) {
			$dataPath = explode('-', $path);
			$count = 0;
			for ($i=0; $i <= $k; $i++) { 
				if($dataPath[$i] == $node)
					$count ++;
			}
			return $count;
		}


		function getTestCase($allRoute,$dataTestCase) {
			$out = array();
			foreach ($allRoute as $key => $value) {
				$routeEx = explode('-', $value);
				$out[$key] = null;
				for ($i=0; $i < count($routeEx); $i++) { 
					if(isset($routeEx[$i+1])) {
						if(isset($dataTestCase[$routeEx[$i].'->'.$routeEx[$i+1]])) {
							if($dataTestCase[$routeEx[$i].'->'.$routeEx[$i+1]] != null) {
								// echo 'i: '.$i .' @ '.$routeEx[$i].'@'.$value.'<br>';
								if(checkNodeExistInPath($routeEx[$i],$value, $i) == 1) {
									$out[$key] .= $dataTestCase[$routeEx[$i].'->'.$routeEx[$i+1]].PHP_EOL;
								}
							}
						}
					}

				}
				
				
			}
			return $out;
		}
		$dataPost = explode("\n", $_POST['code']);
		$data = getDataTestCase($dataPost);
		$allRoute = getAllRoute($data['node']);
		$testCase = getTestCase($allRoute,$data['tc']);

require_once('HtmlExcel.php');

$numbers = '
<table>
<tr>
<td>
</td>
</tr>
</table>
<table style="border:2px solid #000;width: 1500px;table-layout: fixed;">

    <tr>
      <td style="border: 1px solid #000;text-align:center;background:#eee;font-weight: bold;font-size:14pt;font-family: Arial;">Code</th>
      <td style="border: 1px solid #000;background:#eee;font-weight: bold;font-size:14pt;font-family: Arial;">Node</th>
    </tr>

  <tbody style="max-width:1500px;">
  	<tr>
  		<td style="border: 1px solid #000;"></td>
  	</tr>';

	foreach ($allRoute as $key => $value) {
    $numbers .= '<tr><td style="border: 1px solid #777;font-style: italic;font-family: Arial;padding:10px; word-wrap: break-word;">';
      		$numbers .= trim($testCase[$key]);
      		$numbers .= '</td></tr>';
      }
      
  $numbers .= '</tbody></table>';


// echo $numbers;
// $xls = new HtmlExcel();
// $xls->setCss($css);
// $xls->addSheet("Overview", $numbers);
// $xls->headers();
// echo $xls->buildFile();

// exit();
			
?>
<?php endif; ?>
<?php include 'header.php'; ?>


<div class="container">
	<div class="row">
		<div class="col-md-12">

<table class="table table-striped">
                  <thead>
                    <tr>
                    	<th>#</th>
                      <th>Path</th>
                      <th>Testcase</th>
                    </tr>
                  </thead>
                  <tbody>
	<?php foreach ($allRoute as $key => $value) { ?>
	<tr>
		<td><?php echo ($key+1); ?></td>
		<td>S-<?php echo $value; ?></td>
		<td><?php echo nl2br(trim($testCase[$key])).'<br>'; ?></td>
	</tr>
	<?php } ?>
</tbody>
</table>



				<hr>
			
			<form method="POST" action="">
				<div class="form-group">
					<label>Input:</label>
					<textarea name="code" placeholder="Enter your code !" rows="20" class="form-control">
1->2 $analyzeMessage = ['data' => '...']
1->22 $analyzeMessage = []
2->3 
3->4
4->5 $graphTargetDate = ['data' => '...']
4->8 $graphTargetDate =  []
5->6 
5->7 
6->4
7->4
8->9
9->10 
9->18 
10->11 
10->14
11->12
12->13
12->14
13->14 
14->15 
14->16
15->16 
16->17 
16->9
17->9 
18->19
19->20
19->21 
20->19
21->1
22->E



					</textarea>
				</div>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-outline-primary btn-block">Get route</button>
				</div>
			</form>
		</div>
	</div>
</div>


<?php include 'footer.php'; ?>
<?php 
/*
1->2 $analyzeMessage = ['data' => ...]
1->22 $analyzeMessage = []
2->3 
3->4
4->5 $graphTargetDate = ['data' => ...]
4->8 $graphTargetDate = []
5->6 
5->7 
6->4
7->4
8->9
9->10 ($graphData = [])
9->18 ($graphData = ['data' => ...])
10->11 ($graphData[$i] != null)
10->14 ($graphData[$i] = null)
11->12
12->13 ($count = 1)
12->14 ($count != 1)
13->14 
14->15 ($count > 21)
14->16 ($count <= 21)
15->16 
16->17 ($count <= 21 && $graphData[$i] != null)
16->9  ($count > 21 && $graphData[$i] != null)
17->9 
18->19
19->20 ($dateStart <= $dateEnd)
19->21 ($dateStart > $dateEnd)
20->19
21->1
22->E