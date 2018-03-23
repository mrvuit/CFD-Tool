<?php include 'functions.php'; ?>
<?php 
	if(isset($_POST['ajax'])) {
		echo phpbeautifier($_POST['beautify_code'], $_POST['indent_format'], $_POST['indent_number']);
		exit();
	}

?>

<?php include 'header.php'; ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li class="breadcrumb-item active">
			    <h3>
			      <i class="fa fa-braille" aria-hidden="true"></i> 
			     Prettify Code (with PHP)
			    </h3>
			  </li>
			</ol>
			<div class="row">
				<div class="col-md-12" id="#output">
					<h4>Output</h4>
					<div id="result"></div>
					<hr>
					<button style="display: none;" id="btn-copy" onclick="copyText('result');" class="btn btn-block btn-sm btn-outline-primary">Copy to clipboard</button>
				</div>
				<div class="col-md-12">
					<h4>Input</h4>
					<form method="POST">
					  <div class="form-group">
						<textarea class="textarea" placeholder="Paste your code..." name="beautify_code" id="beautify_code" rows="11" style="width: 100%;"><?php echo htmlentities('<?php'); ?></textarea>
						<h5 class="text-muted">Please include <?php echo htmlentities('<?php'); ?> tag at first</h5>
					  </div>
					  <div class="form-group">
					  	<h4>Language</h4>
					    <label>PHP <input type="radio" name="type" value="php" checked="checked"></label><br>
					  </div>
					  <div class="form-group">
					  	<h4>Indent Format/Style</h4>
					    <select name="indent_format" class="form-control" id="indent_format">
			              <option value="GNU">GNU</option>
			              <option value="PEAR" selected="selected">PEAR</option>
					    </select>
					  </div>
					  <div class="form-group">
					  	<h4>Indent Number</h4>
					    <input maxlength="10" name="indent_number" id="indent_number" value="4" size="10" onblur="val=parseInt(this.value); this.value=(val<0)?0:((val>10)?10:(isNaN(val)?0:val))" class="form-control">
					  </div>
					  <button href="#output" type="button" class="btn btn-primary btn-block" onclick="prettifyCode();"> Prettify Code</button>
					</form>
				</div>

			</div>
			<hr>
		</div>
	</div>
</div>

<?php 
	include 'footer.php';
?>