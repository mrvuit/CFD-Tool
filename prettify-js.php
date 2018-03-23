<?php
	include 'header.php';
	include 'function.php';
?>
<script src="js/run_prettify.js"></script>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li class="breadcrumb-item active">
			    <h3>
			      <i class="fa fa-braille" aria-hidden="true"></i> 
			     Prettify Code (with JS)
			    </h3>
			  </li>
			</ol>
			<?php if(isset($_POST['submit'])) : ?>
			<pre class="prettyprint"><code class="language-<?php if(isset($_POST['type'])) echo $_POST['type']; else echo 'php'; ?>" id="codePre"><?php if(isset($_POST['code'])) echo $_POST['code'];?></code></pre>
			<?php endif; ?>
			<form method="POST">
			  <div class="form-group">
			    <textarea class="form-control" placeholder="Paste your code..." name="code" rows="11"></textarea>
			  </div>
			  <div class="form-group">
			    <label>PHP <input type="radio" name="type" value="php" checked="checked"></label><br>
			    <label>JAVA <input type="radio" name="type" value="java"></label><br>
			    <label>JS <input type="radio" name="type" value="js"></label><br>
			    <label>HTML <input type="radio" name="type" value="html"></label><br>
			  </div>
			  <button type="submit" name="submit" class="btn btn-outline-primary btn-block"> Prettify Code</button>
			</form>
			<hr>
			
		</div>
	</div>
</div>

<script type="text/javascript">
  function copyText(id) {
      let textarea = document.createElement('textarea');
      textarea.id = 't';
      textarea.style.height = 0;
      document.body.appendChild(textarea);
      textarea.value = document.getElementById(id).innerText;
      let selector = document.querySelector('#t');
      selector.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      alert('Copyted !');
  }
</script>
<?php 
	include 'footer.php';
?>