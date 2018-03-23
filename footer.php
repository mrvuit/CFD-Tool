<?php $dataRecord = getRecord(); ?>
    <div class="container">
    	<hr>
	    	<div class="row">
	    		<div class="col-md-12">
	    			<i class="fa fa-check"></i> All diagram be created: <?php echo $dataRecord[0]; ?><br>
	    			<i class="fa fa-clock-o"></i> Last used: <?php echo convertTime($dataRecord[1]); ?>
	    		</div>
	    	</div>
      	<hr>
      <footer>
        <p>&copy; vutl</p>
      </footer>
    </div>
  </body>
</html>
