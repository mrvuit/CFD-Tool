<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>
    <div class="container">
      <div class="col-md-12">
      	<?php if(isset($_GET['file']) && file_exists('upload/'.$_GET['file'])) { ?>
      	<?php
      	$file_link = 'upload/'.$_GET['file'];

      	//write file
      	if(isset($_POST['submitEdit'])) {
      		$file = fopen($file_link, "w") or die("Unable to open file!");
      		fwrite($file, $_POST['content']);
      		fclose($file);
			echo '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			  <strong>Edit file success!</strong>
			</div>
			';
      	}
      	// read file
      	$file = fopen($file_link, "r") or die("Unable to open file!");
      	$data = filesize($file_link) != 0 ? fread($file,filesize($file_link)) : '';
      	fclose($file);
      	?>

	    <form method="POST" action="cfd.php">
	      <div class="form-group">
	        <p class="text-center">
	        	<h4>Preview</h4>
	        </p>
	       <p>
	       	 <textarea class="form-control" name="node" rows="15" placeholder="Do not have node..."><?php echo formatNode(preg_replace("/ {2,}/", " ", getNodeInFile($file_link)),false,false); ?></textarea>
	       </p>
	       <p>
	        	<button type="submit" name="submit" class="btn btn-outline-primary btn-block">Get CFD</button>
	       </p>
	      </div>
	    </form>

	    <form method="POST" action="">
	      <div class="form-group">
	        <p class="text-center">
	        	<h4>Edit file</h4>
	        </p>
	       <p>
	       	 <textarea class="form-control" name="content" rows="15" placeholder="File null..."><?php echo $data; ?></textarea>
	       </p>
	       <p>
	        	<button type="submit" name="submitEdit" class="btn btn-outline-primary btn-block">Save</button>
	       </p>
	      </div>
	    </form>
	    <?php } else { ?>

	    <h3>File not exist</h3>
	    <?php } ?>
      </div>
    </div>
<?php include 'footer.php'; ?>