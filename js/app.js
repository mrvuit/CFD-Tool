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
	      alert('Copied !');
	  }

	  function prettifyCode() {
	      $.ajax({
	          url: "prettify-php.php",
	          type: "POST",
	          data: "ajax=true&beautify_code=" + $("#beautify_code").val() + "&indent_format=" + $("#indent_format").val() + "&indent_number=" + $("#indent_number").val(),
	          beforeSend: function() {
	              $('#result').html('<div style="text-align:center;"><img src="/img/loading.gif" style="width:150px;"><h1>Loading ...</h1></div>');
				$('html,body').animate({
				            scrollTop: 0
				        }, 700);
	          },
	          success: function(response) {
	              $('#result').html(response);
	              $('#btn-copy').show();
	          },
	          error: function(response) {
	              alert('error');
	          }
	      });


	  }
	  function runCode() {
	      $.ajax({
	          url: "run-php.php",
	          type: "POST",
	          data: "ajax=true&editor_code=" + $("#editor_code").val(),
	          beforeSend: function() {
	              $('#result').html('<div style="text-align:center;"><img src="/img/loading.gif" style="width:150px;"><h1>Loading ...</h1></div>');
				$('html,body').animate({
				            scrollTop: 0
				        }, 700);
	          },
	          success: function(response) {
	              $('#result').html(response);
	              $('#btn-copy').show();
	          },
	          error: function(response) {
	              alert('error');
	          }
	      });


	  }
	  $(function() {
	      $(".textarea").linedtextarea({
	          selectedLine: 1
	      });
	  });