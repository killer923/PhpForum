<?php
require_once 'data/scripts/pass_gen.php';
session_start();
//print_r($_SESSION);
if(strip_tags($_POST['Submit'])=="Submit"){
	$dbc= mysql_connect('db_host','username','password')
	or die('ERROR:Problem Connecting server');
	mysql_select_db("db_name", $dbc);
	if($_SESSION['uname']===0)
		$uname=strip_tags($_POST['uname']);
	else $uname=$_SESSION['uname'];
	$check=mysql_query("SELECT * FROM users WHERE uname='".$uname."'")or die('ERROR:Problem during query');
	$number=mysql_num_rows($check);
	if($number!=0) { //user name taken...ask to change
		if(isset($_GET['continue'])) $url='twit_email.php?another=1&contine='.strip_tags($_GET['continue']);
			else $url='twit_email.php?another=1';
		echo"<meta http-equiv='refresh' content='0; URL=".$url."'>";
		exit();
	}
		$password=generatePassword(6);
		$hash=md5($password);
		$query=sprintf("INSERT INTO `users` (name,uname,psswd,email,twitter_name,acc_status,log_status) VALUES ('%s','%s','%s','%s','%s','0','0')",mysql_real_escape_string($_SESSION['all']['name']),mysql_real_escape_string($uname),mysql_real_escape_string($hash),mysql_real_escape_string(strip_tags($_POST['email'])),mysql_real_escape_string($_SESSION['all']['screen_name']));
		//echo $query;
		$result=mysql_query($query) or die('ERROR:Problem during registering');
		mysql_close($dbc);

		$mesg="Hi ".$fullname."\n<br>Thanks for registering on the forum.Your account details are\n<br>username:\t".$uname."\n<br>password:\t".$password."\n<br>See you around<br><br>Regards<br>Forum";
		$msg=wordwrap($mesg,70);
		mail(strip_tags($_POST['email']),"Account Details",$msg,"From:FORUM<forum@abibhatia.co.cc>\r\nContent-type: text/html; charset=iso-8859-1\r\n");
		if(isset($_GET['continue'])) echo"<script language=javascript>window.location='index.php?continue=".strip_tags($_GET['continue'])."&msg=Your account details have been mailed.You need to log in atleast once using your username before using Twitter login.';</script>";
		else echo"<script language=javascript>window.location='index.php?msg=Your account details have been mailed.You need to log in atleast once using your username before using Twitter login';</script>";
		session_destroy();
		exit();
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF 8">
		<style>
			@import url("data/css/default.css");
			@import url("data/css/settings.css");
		</style>
		<title>Username?</title>
	</head>
	<body>
	<?php
	if (isset($_GET['msg'])) { 
		$msg=strip_tags($_GET['msg']);
		echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; 
		}
	?>	
	<p>
		<?php if (isset($_GET['another'])){
				if(strip_tags($_GET['another'])==1){echo"This username has also been taken.Please input another one.";}
				else{
					if(isset($_GET['uname'])){
						echo"Sorry your Twitter username is already associated with another account.Please choose a new username for this site.";
						}
					}
			}
			else{
				if(isset($_GET['uname']))echo"Sorry your facebook username is already associated with another account.Please choose a new username for this site.";
				}	
			if(isset($_GET['continue'])) $url='twit_email.php?contine='.strip_tags($_GET['continue']);
			else $url='twit_email.php';
		?>
		<br>Note that the username is permanent and once chosen, it cannot be changed.
	</p>
	<form id="new_uname" action="<?php echo $url;?>" method="POST">
		<?php
			if(isset($_GET['uname'])){
				$character=strip_tags($_GET['uname']);
				if($character===0){
					$_SESSION['uname']=0;
					echo "<label for=\"uname\">Username:</label><input type=\"text\" name=\"uname\" size=\"45\"><br>";	
				}
				
			}
			else echo"Your username is: ".$_SESSION['uname']."<br>";
		?>
		<label for="email" title="Size limit is 30 characters">Email:</label><input type="text" name="email" size="45"><br>
		<input type="submit" name="Submit" value="Submit">
	</form>
	</body>
</html>