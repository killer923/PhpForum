<?php
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);
require_once('class.phpmailer.php');
function genRandomString($length=10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
$uname=strip_tags($_GET['user']);
$query=sprintf("SELECT `ans`,`email,`fbmail` FROM `users` WHERE `uname` = '%s' LIMIT 1",mysql_real_escape_string($uname));
$check=mysql_query($query) or die('ERROR:Problem during query');
if(mysql_num_rows($check)==0){
	echo"<meta http-equiv='refresh' content='0; URL=forgot.php?msg=NO SUCH USER'>";
	exit();}
$row=mysql_fetch_array($check,MYSQL_ASSOC);
$ans=strip_tags($_POST['ans']);
if($row['ans']==md5($ans)){
	$password=genRandomString();
	$mesg="Hi ".$fullname."\nYou requested a password change recently.Your account details are\nusername:\t".$uname."\npassword:\t".$password."\nSee you around";
	$msg=wordwrap($mesg,70);
	/*$mailer = new PHPMailer();  // create a new object
	$mailer->IsSMTP(); // enable SMTP
	$mailer->Host = '172.31.1.22:25';
	$mailer->SMTPAuth = TRUE;
	$mailer->Username = "abhatia";  
	$mailer->Password = "LikePot";
	$mailer->From = "forum@abhatia.co.cc"; 
	$mailer->FromName = "FORUM"; // This is the from name in the email, you can put anything you like here
	$mailer->Body = "".$msg."";
	$mailer->Subject = "Account Details";
	$mailer->AddAddress($row['email']);  // This is where you put the email adress of the person you want to mail
	if(!$mailer->Send())
	{
	   echo "Message was not sent<br/ >";
	   echo "Mailer Error: " . $mailer->ErrorInfo;
	   echo "Please go back and try again";
	}*/
	if($row['email']!=NULL&&$row['fbmail']!=NULL){$email=$row['email'].','.$row['fbmail'];}
	elseif($row['email']!=NULL&&$row['fbmail']==NULL){$email=$row['email'];}
	elseif($row['email']==NULL&&$row['fbmail']!=NULL){$email=$row['fbmail'];}
	
	//echo $password;
	$password=md5($password);
	$query=sprintf("UPDATE `db_name`.`users` SET `psswd`='%s' , `acc_status`='0' WHERE uname='%s' LIMIT 1",mysql_real_escape_string($password),mysql_real_escape_string($uname));
	mysql_query($query) or die("ERROR:Retry Later2");
	mail($email,"Account Details",$msg,"From:FORUM<forum@abhibhatia.co.cc>\r\nContent-type: text/html; charset=iso-8859-1");
	echo '<br>';
	echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Your password has been changed and your new account details have been mailed'>";	
	mysql_close($dbc);
	exit();
}
else{
	echo "<meta http-equiv='refresh' content='0; URL=forgot.php?msg=You did not provide the correct answer'>";	
	mysql_close($dbc);
	exit();
	}
?>