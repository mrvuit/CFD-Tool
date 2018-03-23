<?php
	require 'functions.php';
	include 'header.php';
?>
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<label><input type="radio" name="type" value="0" onclick="ajaxFormat();"> TYPE 0</label>
				<label><input type="radio" name="type" value="1" onclick="ajaxFormat();"> TYPE 1</label>
				<label><input type="radio" name="type" value="2" checked="checked" onclick="ajaxFormat();"> TYPE 2</label>
				<label><input type="radio" name="type" value="3" onclick="ajaxFormat();"> TYPE 3</label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<button class="btn btn-block btn-primary">COPY CODE</button>
			</div>
			<div class="col-md-6">
				<button class="btn btn-block btn-primary">COPY CODE FORMAT</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<textarea class="form-control" placeholder="Enter your code..." row="4" id="code">    if (empty($user->getThumbnail())) {
        $thumbnail = '/img/config/member.png';
    } else {
        $thumbnail = $user->getThumbnail();
    }</textarea>
			</div>
			<div class="col-md-6">
				<div id="result"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">

		function ajaxFormat() {
			$.ajax({
		        url: "ajaxFormat.php",
		        type: "POST",
		        data: "code=" + $('#code').val() + "&type=" + $("input[name=type]:checked").val(),
		        success: function (response) {
		            $('#result').html(response);
		            $("#code").css("height", ""+$("#result").height()+"px");
		        },
		        error: function (response) {
		            // alert(response);
		        }
		    });

		    
		}
		$( "#code" ).keyup(function() {
			ajaxFormat();
		});
		ajaxFormat();
	</script>
<?php include 'footer.php'; ?>