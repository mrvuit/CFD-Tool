<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>
<?php 
	if(isset($_GET['reset'])) {
		setcookie('linkNode', '', time() - 1, "/"); 
		header("Location: setting.php");	
	}

?>
<?php
	if(isset($_POST['submit'])) {
		$linkNode = trim($_POST['linkNode']);
		if(empty($linkNode)) {
			$notice = 'Format node cannot null !';
		} else if (preg_match('/\s/',$linkNode)) {
			$notice = 'Must not contain spaces !';
		} else if (is_numeric($linkNode)) {
			$notice = 'Not a number!';
		} else if ($linkNode == 'E') {
			$notice = 'Must not be E !';
		} else {
			$setting = true;
			setcookie('linkNode', $linkNode, time() + (86400 * 30), "/"); 
			$notice = 'Setting success !';
		}
	}
 ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li class="breadcrumb-item active">
			    <h3>
			      <i class="fa fa-braille" aria-hidden="true"></i> 
			     Setting
			    </h3>
			  </li>
			</ol>
		<?php if(isset($notice)) : ?>
			<div class="alert alert-<?php echo !isset($setting) ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			  <strong>Notify:</strong> <?php echo $notice; ?>
			</div>
			<hr>
		<?php endif; ?> 
			<form method="POST">
			  <div class="form-group">
			    <label for="formGroupExampleInput">Enter your format link node</label>
			    <input type="text" class="form-control" name="linkNode" placeholder="Default: ->">
				<small id="passwordHelpInline" class="text-muted">
			      Present link node: <?php echo (isset($setting) ? $linkNode : LINKNODE); ?>
			    </small>
			  </div>
			  <div class="form-group">
			    <button class="btn btn-primary" name="submit">Submit</button>
			  </div>
			  <label>The settings here will not affect other users!</label>
			</form>
			<hr>
			<a href="?reset" class="btn btn-outline-primary btn-block"> Reset your setting</a>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>