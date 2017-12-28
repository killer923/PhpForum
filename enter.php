<?php
session_start();
if($_SESSION['log_status']==1){//already logged in
	echo "<meta http-equiv='refresh' content='0; URL=home.php'>";
	exit(1);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<link rel="stylesheet" href="data/css/not_logged_in.css">
	<meta name="robots" content="index,follow,archive,imageindex,imageclick" />
	<title>LOG IN</title>
</head>
<body>
	<div id="top">
		<div id="logo">
			<img src="data/images/logo.jpg" alt="Forum">
		</div>
	</div>
	<div id="main">
<?php
	if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; }
	?>
<form action="login.php<?php if(isset($_GET['continue'])) echo "?continue=".$_GET['continue'];?>" method="post">
Enter username:<input type="text" name="username" size="45"><br>
Enter password:<input type="password" name="password" size="45"><br>
<input type="Submit" name="Send" value="Login"><br>
<a href=nuser.php>Register</a>
<a href=forgot.php>FORGOT PASSWORD</a>
</form><table>	<tr width=100%>		<td>			<script type="text/javascript">			google_ad_client = "ca-pub-9432378994594271";			/* forum1 */			google_ad_slot = "2546906457";			google_ad_width = 728;			google_ad_height = 90;			</script>			<script type="text/javascript"			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">			</script>		</td>	</tr></table>
</div>
</body>
</html>

