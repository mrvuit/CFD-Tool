<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>
	<?php 
		if(isset($_POST['submit'])) {
			
			if(isset($_POST['file'])) {
				$arrFile = $_POST['file'];
				foreach ($arrFile as $value) {
					unlink('upload/'.$value);
				}
				header("Refresh:0");
			}
		}
	?>
	<div class="container">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li class="breadcrumb-item active">
			    <h3>
			      <i class="fa fa-braille" aria-hidden="true"></i> 
			      List upload
			    </h3>
			  </li>
			</ol>
			<form method="POST">
				<table class="table table-striped">
				  <thead>
				    <tr>
				      <th><input type="checkbox" onchange="checkAll(this)"></th>
				      <th>File name</th>
				      <th>Time</th>
				      <th>Size</th>
				      <th>Preview</th>
				      <th>Download</th>
				    </tr>
				  </thead>
				  <tbody>
					<?php
					$files = array();
					if ($handle = opendir('upload/')) {
					    while (false !== ($file = readdir($handle))) {
					        if ($file != "." && $file != "..") {
					           $files[filemtime('upload/'.$file)] = $file;
					        }
					    }
					    closedir($handle);
					    rsort($files);
					    foreach($files as $file) {
					        $lastModified = date('y/m/d h:i',filemtime('upload/'.$file));
					    	echo '<tr>';
					    	echo '<th scope="row"><input type="checkbox" name="file[]" value="'.$file.'"></th>'; 
					    	echo '<td><a href="/cfd.php?file='.$file.'">'.$file.'</a></td>';
					        echo '<td> '.$lastModified.'</td>';
					        echo '<td>'.FileSizeConvert(filesize("upload/".$file)).'</td>';
					        echo '<td><a class="btn btn-primary btn-sm" href="preview.php?file='.$file.'">pre/edit</a></td>';
					        echo '<td><a class="btn btn-primary btn-sm" href="download.php?file='.$file.'">download</a></td>';
					        echo '</tr>';
					    }
					}
					?>
				  </tbody>
				</table>
				<button name="submit" type="submit" class="btn btn-outline-danger btn-block">Delete file is selected</button>
			</form>
		</div>
    </div>
    <script type="text/javascript">
	 function checkAll(ele) {
	     var checkboxes = document.getElementsByTagName('input');
	     if (ele.checked) {
	         for (var i = 0; i < checkboxes.length; i++) {
	             if (checkboxes[i].type == 'checkbox') {
	                 checkboxes[i].checked = true;
	             }
	         }
	     } else {
	         for (var i = 0; i < checkboxes.length; i++) {
	             console.log(i)
	             if (checkboxes[i].type == 'checkbox') {
	                 checkboxes[i].checked = false;
	             }
	         }
	     }
	 }
    </script>
<?php include 'footer.php'; ?>