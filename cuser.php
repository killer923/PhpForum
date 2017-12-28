<?php
session_start();
//Things to add:security
require_once('class.phpmailer.php');
echo 'Please Wait';
$fullname=strip_tags($_REQUEST['name']);
if(strlen($fullname)>40){	//checks if alphanumeric
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=Full name must be less than 40 characters long'>";
	exit(1);
	}
$username=strip_tags($_REQUEST['uname']);
if(strlen($username)>15){	//checks if alphanumeric
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=Username must be less than 15 characters long'>";
	exit(1);
	}
if(ctype_alnum($username)==0){	//checks if alphanumeric
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=Username must be alphanumeric only'>";
	exit(1);
	}
$emailid=strip_tags($_REQUEST['email']);
if(strlen($emailid)>50){	//checks if alphanumeric
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=Emailid must be less than 50 characters long'>";
	exit(1);
	}
if($_SESSION['captcha']!=md5(strip_tags($_REQUEST['code']))){
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=Verification Code incorrect'>";
	exit(1);
	}

$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);
$check=mysql_query("SELECT * FROM users WHERE uname='".$username."'")or die('ERROR:Problem during first query');
$number=mysql_num_rows($check);
echo $number;
if($number!=0) {
	mysql_close($dbc);
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=ERROR:Username Already Taken...Please Select a newone'>";	
	exit();
	}
$check=mysql_query("SELECT * FROM users WHERE email='".$emailid."'")or die('ERROR:Problem during  query');
$number=mysql_num_rows($check);
if($number!=0) {
	mysql_close($dbc);
	echo "<meta http-equiv='refresh' content='0; URL=nuser.php?msg=ERROR:This email id is already associated with some other account...Please enter different email address'>";	
	exit();
	}
$password=rand(100000,9999999999);
$hash=md5($password);

$query=sprintf("INSERT INTO `db_name`.`users` (name,uname,psswd,email,acc_status,log_status) VALUES ('%s','%s','%s','%s','0','0')",mysql_real_escape_string($fullname),mysql_real_escape_string($username),mysql_real_escape_string($hash),mysql_real_escape_string($emailid));
$result=mysql_query($query) or die('ERROR:Problem during registering');
mysql_close($dbc);

$mesg="Hi ".$fullname."\nThanks for registering on the forum.Your account details are<br>username:&nbsp;&nbsp;&nbsp;&nbsp;".$username."<br>password:&nbsp;&nbsp;&nbsp;&nbsp;".$password."<br>See you around";
	$msg=wordwrap($mesg,70);
	/*$mailer = new PHPMailer();  // create a new object
	$mailer->IsSMTP(); // enable SMTP
	$mailer->Host = '172.31.1.22:25';
	$mailer->SMTPAuth = TRUE;
	$mailer->Username = "abhatia";  
	$mailer->Password = "TinyWord";
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
	}
*/
mail($emailid,"Account Details",$msg,"From:FORUM<forum@abibhatia.co.cc>\r\nContent-type: text/html; charset=iso-8859-1");
echo '<br>';
echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Thanks for registering.Your account details have been mailed'>";	
exit();

?>