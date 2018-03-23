<?php
	require 'functions.php';
	include 'header.php';
?>


	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <h3>
                      <i class="fa fa-braille" aria-hidden="true"></i> 
                      Fix number node
                    </h3>
                  </li>
                </ol>
				<?php if(isset($_POST['submit'])) : ?>
					<button class="btn btn-sm" onclick="CopyToClipboard('resultCode');">Copy To Clipboard</button>
<pre id="resultCode">
	<?php foreach (fixNode($_POST['code'],$_POST['calculation'],$_POST['number']) as $line) { ?><?php echo $line; ?><?php } ?>
</pre>
				<?php endif; ?>
				<form method="POST" action="">
					<div class="form-gorup">
						<textarea class="form-control" name="code" rows="20" placeholder="Enter your code !"></textarea>
                        <br>
                        <select name="calculation" class="form-control">
                        	<option value="1">Increased (+)</option>
                        	<option value="0">Reduced (-)</option>
                        </select>
                        <br>
                        <input name="number" class="form-control" min="1" max="100" placeholder="Enter number">
					</div>
					<br>
					<div class="form-gorup">
						<button class="btn btn-primary btn-block" type="submit" name="submit">Submit</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<script type="text/javascript">
function CopyToClipboard(containerid) {
if (document.selection) { 
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("Copy"); 

} else if (window.getSelection) {
    var range = document.createRange();
     range.selectNode(document.getElementById(containerid));
     window.getSelection().addRange(range);
     document.execCommand("Copy");
     alert('Okey !');
}}
</script>
<?php 
	include 'footer.php'; 
?>