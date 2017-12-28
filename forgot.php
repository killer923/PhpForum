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
<title>RESET PASSWORD</title>
</head>
<body>
<?php
	if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
?>
<form action=verify.php method=post>
ENTER USERNAME:<input type=text name=user size=45>
<input type=Submit name=Send value=RESET>
</body>
</html>