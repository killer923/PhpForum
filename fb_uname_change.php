<?php
session_start();
//var_dump($_SESSION);
if(strip_tags($_POST['Submit'])=="Submit"){
//echo"<script language=javascript>alert('ji');</script>";
	$dbc= mysql_connect('db_host','username','password')
	or die('ERROR:Problem Connecting server');
	mysql_select_db("db_name", $dbc);
	$uname=strip_tags($_POST['uname']);
	$check=mysql_query("SELECT * FROM users WHERE uname='".$uname."'")or die('ERROR:Problem during first query');
		$number=mysql_num_rows($check);
		//echo $number;
		if($number!=0) { //user name taken...ask to change
			if(isset($_GET['continue'])) $url='fb_uname_change.php?another=1&contine='.strip_tags($_GET['continue']);
			else $url='fb_uname_change.php?another=1';
			echo"<meta http-equiv='refresh' content='0; URL=".$url."'>";
			exit();
		}
		$password=rand(100000,9999999999);
		$hash=md5($password);
		$query=sprintf("INSERT INTO `users` (name,uname,psswd,fbmail,acc_status,log_status) VALUES ('%s','%s','%s','%s','0','1')",mysql_real_escape_string($_SESSION['fullname']),mysql_real_escape_string($uname),mysql_real_escape_string($hash),mysql_real_escape_string($_SESSION['fbmail']));
		$result=mysql_query($query) or die('ERROR:Problem during registering');
		mysql_close($dbc);

		$mesg="Hi ".$fullname."\n<br>Thanks for registering on the forum.Your account details are\n<br>username:\t".$uname."\n<br>password:\t".$password."\n<br>See you around<br><br>Regards<br>Forum";
			$msg=wordwrap($mesg,70);
		/*	$mailer = new PHPMailer();  // create a new object
			$mailer->IsSMTP(); // enable SMTP
			$mailer->Host = '172.31.1.22:25';
			$mailer->SMTPAuth = TRUE;
			$mailer->Username = "abhatia";  
			$mailer->Password = "LikePot";
			$mailer->From = "forum@abhatia.co.cc"; 
			$mailer->FromName = "FORUM"; // This is the from name in the email, you can put anything you like here
			$mailer->Body = "".$msg."";
			$mailer->Subject = "Account Details";
			$mailer->AddAddress($emailid);  // This is where you put the email adress of the person you want to mail
			if(!$mailer->Send())
			{
			   echo "Message was not sent<br/ >";
			   echo "Mailer Error: " . $mailer->ErrorInfo;
			   echo "Please go back and try again";
			}*/

		mail($_SESSION['fbmail'],"Account Details",$msg,"From:FORUM<forum@abibhatia.co.cc>\r\nContent-type: text/html; charset=iso-8859-1\r\n");
		$_SESSION['uname']=$uname;
		$_SESSION['log_status']=1;
		$_SESSION['email']=$_SESSION['fbmail'];
		if(isset($_GET['continue'])){
			echo"<meta http-equiv='refresh' content='0;URL=http://forum.abhibhatia.co.cc/".strip_tags($_GET['continue'])."'>";
			}
		else echo"<meta http-equiv='refresh' content='0;URL=http://forum.abhibhatia.co.cc/home.php'>";
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
					if(isset($_GET['uname'])){echo"Sorry your facebook username does not exist.Please choose a username for this site.";}
					else echo"Sorry your facebook username is already associated with another account.Please choose a new username for this site.";
					}
			}
			else{if(isset($_GET['uname'])){echo"Sorry your facebook username does not exist.Please choose a username for this site.";}
				else echo"Sorry your facebook username is already associated with another account.Please choose a new username for this site.";
				}	
			if(isset($_GET['continue'])) $url='fb_uname_change.php?contine='.strip_tags($_GET['continue']);
			else $url='fb_uname_change.php';
		?>
		<br>Note that the username is permanent and once chosen, it cannot be changed.
	</p>
	<form id="new_uname" action="<?php echo $url;?>" method="POST">
		<label for="uname">Username:</label><input type="text" name="uname" size="45"><br>
		<input type="submit" name="Submit" value="Submit">
	</form>
	</body>
</html>