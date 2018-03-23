<?php include 'functions.php'; ?>
<?php 
	if(isset($_POST['ajax'])) {
		echo runcodephp($_POST['editor_code']);
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
			     Run Code PHP Online
			    </h3>
			  </li>
			</ol>
			<div class="row">
				<div class="col-md-12" id="#output">
					<h4>Output</h4>
					<div id="result" style="background: #f2fdff;padding: 10px;"></div>
					<hr>
					<button style="display: none;" id="btn-copy" onclick="copyText('result');" class="btn btn-block btn-sm btn-outline-primary">Copy to clipboard</button>
				</div>
				<div class="col-md-12">
					<h4>Input</h4>
					<form method="POST">
					  <div class="form-group">
						<textarea class="textarea" placeholder="Paste your code..." name="editor_code" id="editor_code" rows="11" style="width: 100%;">echo "Atleta Team! " . rand(1,1000);</textarea>
					  </div>
					  <button href="#output" type="button" class="btn btn-primary btn-block" onclick="runCode();"> Run now</button>
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