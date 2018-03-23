<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>

    <div class="container">
    	<div class="row">
      <div class="col-md-12">
      	<?php if(isset($_POST['submitCode'])) { ?>
      	<?php $dataCode = explode("\n", $_POST['code']); ?>
		<?php $nodeString = getNodeInCode($dataCode); ?>
		<?php $nodeString = preg_replace("/ {2,}/", " ",$nodeString); ?>
		<?php $dataTable = getTableToExcel($dataCode); ?>

		<?php $checkErr = checkFormatAllRoute($nodeString); ?>

	    <form method="POST" action="excel.php">
	        <p class="text-center">
	        	<h4>Excel (File excel chứa bảng code & node)</h4>
	        </p>
	      <input type="hidden" name="dataTable" id="dataExcel" value="<?php print base64_url_encode(serialize($dataTable)); ?>" />
	      <input type="hidden" name="dataNode" id="dataNode" value="<?php print base64_url_encode(serialize(explode(' ', formatNode($nodeString, true, false)))); ?>" />
	      <div class="row">
	      	<div class="col-md-2">
				<label>Code Color</label><br>
		      	<label><input type="radio" name="format" value="true" checked="checked"> Yes</label><br>
		      	<label><input type="radio" name="format" value="false"> No</label>
	      	</div>
	      	<div class="col-md-2">
		      	<label>Code style</label><br>
		      	<label><input type="radio" name="style" value="none" checked="checked"> None</label><br>
		      	<label><input type="radio" name="style" value="italic"> Italic</label>
	      	</div>
	      	<div class="col-md-3">
		      	<label>Emtpy line</label><br>
		      	<label><input type="radio" name="lineemtpy" value="yes"> Remove emtpy line</label><br>
		      	<label><input type="radio" name="lineemtpy" value="no"  checked="checked"> Keep</label>
	      	</div>
	      	<div class="col-md-2">
		      	<label>Comment</label><br>
		      	<label><input type="radio" name="comment" value="yes"> Remove</label><br>
		      	<label><input type="radio" name="comment" value="no" checked="checked"> Keep</label>
	      	</div>
	      	<div class="col-md-3">
		      	<label>Path</label><br>
		      	<label><input type="radio" name="path" value="yes"> Add column path</label><br>
		      	<label><input type="radio" name="path" value="no" checked="checked"> No</label>
	      	</div>
	      </div>

	       <p>
	        	<div class="row">
	        		<div class="col-md-6">
	        			<button type="button" class="btn btn-primary btn-block" onclick="prExcel();"><i class="fa fa-refresh"></i> Preview Excel File</button>
	        		</div>
	        		<div class="col-md-6">
	        			<button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-cloud-download"></i> Download Excel File</button>
	        		</div>
	        	</div>
	       </p>
	    </form>
	   	<div class="row">
	   		<div class="col-md-12">
	   			<div id="button-hidden-excel">
	   				<button class="btn btn-block btn-outline-primary btn-sm" onclick="hiddenPreviewExcel();"><i class="fa fa-angle-double-up"></i> Hidden Preview</button>
	   			</div>
	   			<div id="preview-excel" style="max-width: 100%;overflow: auto;border: 3px solid #ddd;"></div>
	   		</div>
	   	</div>
		<?php if(count($checkErr) > 0) : ?>
	        <p class="text-center">
	        	<h4>Error list</h4>
	        </p>
			<ul class="list-group">
			  <?php foreach ($checkErr as $key => $value) { ?>
			  	<li class="list-group-item"><?php echo $value; ?></li>
			  <?php } ?>
			</ul>
    	<?php endif; ?>

		<?php if(count($checkErr) == 0) { ?>
		    <form method="POST" action="cfd.php">
		      <div class="form-group">
		        <p class="text-center">
		        	<h4>Route</h4>
		        </p>
		        <p>
		        	<button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-circle-o"></i> Control Flow Diagram </button>
		        </p>
		       <p>
		       	 <textarea class="form-control" name="node" rows="15" readonly><?php echo formatNode($nodeString,false,false); ?></textarea>
		       </p>
		   		      </div>
		    </form>
		<?php } else {  ?>
		    <form method="POST" action="get.php">
		      <div class="form-group">
		        <p class="text-center">
		        	<h4>Edit node</h4>
		        </p>
		       <p>
		       	 	<textarea class="form-control" name="code" rows="15"><?php echo $_POST['code']; ?></textarea>
		       </p>
		        <p>
		        	<button type="submit" name="submitCode" class="btn btn-outline-primary"><i class="fa fa-paper-plane-o"></i> Try it</button>
		        </p>
		      </div>
		    </form>
		<?php } ?>



	    <?php } ?>
	    <?php 

	          	if(isset($_POST['submitUpload'])) {
	      			$uploadOk = 1;
					$target_dir = "upload/";
					$ext = pathinfo($_FILES["filePhp"]["name"],PATHINFO_EXTENSION);
					$target_file = $target_dir . $_FILES["filePhp"]["name"].'_'.time().'.txt';

					// Allow certain file formats
					if($ext != "php" && $ext != "txt") {
					    $note = "Sorry, only txt, php files are allowed.";
					    $uploadOk = 0;
					}
					if ($uploadOk == 0) {
					    $note = "Sorry, your file was not uploaded.";
					} else {
					    if (move_uploaded_file($_FILES["filePhp"]["tmp_name"], $target_file)) {
					        $note = "The file <strong>". basename( $_FILES["filePhp"]["name"]). "</strong> has been uploaded.";
					    } else {
					        $note = "Sorry, there was an error uploading your file.";
					    }
					}
		?>
			<div class="alert alert-<?php echo ($uploadOk == 1 ? 'success' : 'danger'); ?> alert-dismissible fade show" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span>&times;</span>
			  </button>
			  <strong>Upload file:</strong> <?php echo $note; ?>
			</div>
		<?php
				if($uploadOk == 1) {
					$nodeString = getNodeInFile($target_file);
					$nodeString = preg_replace("/ {2,}/", " ",$nodeString);
					$dataCode = getDataCodeInFile($target_file);
					$dataTable = getTableToExcel($dataCode);
					$checkErr = checkFormatAllRoute($nodeString);
				}
      	?>
      	<?php if($uploadOk == 1) { ?>
		<form method="POST" action="excel.php">
	        <p class="text-center">
	        	<h4>Excel</h4>
	        </p>
	      <input type="hidden" name="dataTable" id="dataExcel" value="<?php print base64_url_encode(serialize($dataTable)); ?>" />
	      <input type="hidden" name="dataNode" id="dataNode" value="<?php print base64_url_encode(serialize(explode(' ', formatNode($nodeString, true, false)))); ?>" />
	      <div class="row">
	      	<div class="col-md-2">
				<label>Code Color</label><br>
		      	<label><input type="radio" name="format" value="true" checked="checked"> Yes</label><br>
		      	<label><input type="radio" name="format" value="false"> No</label>
	      	</div>
	      	<div class="col-md-2">
		      	<label>Code style</label><br>
		      	<label><input type="radio" name="style" value="none" checked="checked"> None</label><br>
		      	<label><input type="radio" name="style" value="italic"> Italic</label>
	      	</div>
	      	<div class="col-md-3">
		      	<label>Emtpy line</label><br>
		      	<label><input type="radio" name="lineemtpy" value="yes"> Remove emtpy line</label><br>
		      	<label><input type="radio" name="lineemtpy" value="no"  checked="checked"> Keep</label>
	      	</div>
	      	<div class="col-md-2">
		      	<label>Comment</label><br>
		      	<label><input type="radio" name="comment" value="yes"> Remove</label><br>
		      	<label><input type="radio" name="comment" value="no" checked="checked"> Keep</label>
	      	</div>
	      	<div class="col-md-3">
		      	<label>Path</label><br>
		      	<label><input type="radio" name="path" value="yes"> Add column path</label><br>
		      	<label><input type="radio" name="path" value="no" checked="checked"> No</label>
	      	</div>
	      </div>

	       <p>
	        	<div class="row">
	        		<div class="col-md-6">
	        			<button type="button" class="btn btn-primary btn-block" onclick="prExcel();"><i class="fa fa-refresh"></i> Preview Excel File</button>
	        		</div>
	        		<div class="col-md-6">
	        			<button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-cloud-download"></i> Download Excel File</button>
	        		</div>
	        	</div>
	       </p>
	    </form>
	   	<div class="row">
	   		<div class="col-md-12">
	   			<div id="button-hidden-excel" style="display: none;">
	   				<button class="btn btn-block btn-outline-primary btn-sm" onclick="hiddenPreviewExcel();"><i class="fa fa-angle-double-up"></i> Hidden Preview</button>
	   			</div>
	   			<div id="preview-excel" style="display: none;max-width: 100%;overflow: auto;border: 3px solid #ddd;"></div>
	   		</div>
	   	</div>
		<?php if(count($checkErr) > 0) : ?>
	        <p class="text-center">
	        	<h4>Error list</h4>
	        </p>
			<ul class="list-group">
			  <?php foreach ($checkErr as $key => $value) { ?>
			  	<li class="list-group-item"><?php echo $value; ?></li>
			  <?php } ?>
			</ul>
    	<?php endif; ?>
		<?php if(count($checkErr) == 0) { ?>
		    <form method="POST" action="cfd.php">
		      <div class="form-group">
		        <p class="text-center">
		        	<h4>Review</h4>
		        </p>
		        <p>
		        	<button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-circle-o"></i> Control flow diagram </button>
		        </p>
		       <p>
		       	 <textarea class="form-control" name="node" rows="15" readonly><?php echo formatNode($nodeString,false,false); ?></textarea>
		       </p>
		      </div>
		    </form>
		<?php } else { ?>
			<a href="preview.php?file=<?php echo $_FILES["filePhp"]["name"].'_'.time().'.txt'; ?>" class="btn btn-block btn-outline-primary"><i class="fa fa-pencil-square-o"></i> Edit file</a>
		<?php } ?>
	    <?php } ?>
	    <?php } ?>
      </div>
      </div>
    </div>
    <script type="text/javascript">
    	function hiddenPreviewExcel() {
    		$('#preview-excel').hide();
			$('#button-hidden-excel').hide();
    	}
		function prExcel() {
			$('#preview-excel').show();
			$('#button-hidden-excel').show();
			var dataExcel = $('#dataExcel').val();
			var dataNode = $('#dataNode').val();
			var formatColor = $("input[name=format]:checked").val();
			var formatStyle = $("input[name=style]:checked").val();
			var formatLine = $("input[name=lineemtpy]:checked").val();
			var formatComment = $("input[name=comment]:checked").val();
			var pathColumn = $("input[name=path]:checked").val();
			var preview = 'true';
			$.ajax({
		        url: "excel.php",
		        type: "POST",
		        data: "dataTable=" + dataExcel + "&dataNode=" + dataNode  + "&format=" + formatColor + "&style=" + formatStyle + "&path=" + pathColumn + "&lineemtpy=" + formatLine + "&comment=" + formatComment + "&preview=" + preview,
				beforeSend: function() {
				    $('#preview-excel').html('Loading ...');
				},
		        success: function (response) {
		            $('#preview-excel').html(response);
		        },
		        error: function (response) {
		            alert('error');
		        }
		    });
		}
		$( document ).ready(function() {
   			prExcel();
		});

    </script>
<?php include 'footer.php'; ?>