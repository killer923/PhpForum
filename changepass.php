<?php
session_start();
if($_SESSION['log_status']!=1){//already logged in
	echo "<script language=javascript>window.location='index.php?msg=You are not logged in';</script>";
	exit(1);
	}
require_once('class.phpmailer.php');

$ques=strip_tags($_REQUEST['ques']);
$ans=strip_tags($_REQUEST['ans']);

echo "Please Wait<br>";

$dbc= mysql_connect('db_host','username','password')
	or die('ERROR:Problem Connecting server');
	mysql_select_db("db_name", $dbc);
	
$user=$_SESSION['uname'];
$currentpass=md5(strip_tags($_REQUEST['ppass']));
	$query=sprintf("SELECT `uname`,`psswd`,`name`,`email` FROM users WHERE uname='%s'",mysql_real_escape_string($user));
	$check=mysql_query($query) or die("ERROR:Retry Later1");
	$row=mysql_fetch_array($check, MYSQL_ASSOC);

if((strip_tags($_REQUEST['pass'])==0)&&(strip_tags($_REQUEST['question'])==0)){
	echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=No Changes made'>";
	mysql_close($dbc);
	exit();
	}
else{
	if(strcmp($currentpass,$row["psswd"])){	
			echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=You do not seem to be the owner'>";
			mysql_close($dbc);
			exit();
			}
	else{
		if(strip_tags($_REQUEST['pass'])==1){
			$newpass1=strip_tags($_REQUEST['npass1']);
			if(strlen($newpass1)>40){	//checks length of password
				echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=Password must be less than 40 characters long'>";
				mysql_close($dbc);
				exit(1);
				}
			if(ctype_alnum($newpass1)==0){	//checks if alphanumeric
				echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=Password must be alphanumeric only'>";
				mysql_close($dbc);
				exit(1);
				}
			$newpass2=$_REQUEST['npass2'];
			if(strcmp($newpass1,$newpass2)){ // compares the passwods
				echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=Passwords do not match'>";
				mysql_close($dbc);
				exit();
				}
			

				$query=sprintf("UPDATE `db_name`.`users` SET `psswd`='%s' WHERE uname='%s' LIMIT 1",mysql_real_escape_string(md5($newpass1)),mysql_real_escape_string($user));
				mysql_query($query) or die("ERROR:Retry Later2");
				mysql_close($dbc);
			}
		if(strip_tags($_REQUEST['question'])==1){
			if(strlen($ans)>10){	//checks if alphanumeric
				echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=ANSWER must be less than 10 characters long'>";
				mysql_close($dbc);
				exit(1);
				}
			if(ctype_alnum($ans)==0){	//checks if alphanumeric
				echo "<meta http-equiv='refresh' content='0; URL=newpass.php?msg=Answer must be alphanumeric only'>";
				mysql_close($dbc);
				exit(1);
				}
			$ques=strip_tags($_REQUEST['ques']);
			$ans=strip_tags($_REQUEST['ans']);
			$query=sprintf("UPDATE `db_name`.`users` SET `question`='%s', `ans`='%s' WHERE uname='%s' LIMIT 1",mysql_real_escape_string($ques),mysql_real_escape_string(md5($ans)),mysql_real_escape_string($user));
			mysql_query($query) or die("ERROR:Retry Later2");
			mysql_close($dbc);
				}
		}

				$mesg="Hi ".$row["name"]."\n<br>Recently your account details were changed\nWe thought you must know...<br><br>Regards<br>Forum";
				$msg=wordwrap($mesg,70);
				/*$mailer = new PHPMailer();  // create a new object
				$mailer->IsSMTP(); // enable SMTP

				$mailer->Host = '172.31.1.22:25';
				$mailer->SMTPAuth = TRUE;
				$mailer->Username = "abhatia";  
				$mailer->Password = "TinyWord";
				$mailer->From = "forum@abhatia"; 
				$mailer->FromName = "FORUM"; // This is the from name in the email, you can put anything you like here
				$mailer->Body = "".$msg."";
				$mailer->Subject = "Account Details Changed";
				$mailer->AddAddress($row["email"]);  // This is where you put the email adress of the person you want to mail
				if(!$mailer->Send())
				{
				   echo "Message was not sent<br/ >";
				   echo "Mailer Error: " . $mailer->ErrorInfo;
				   echo "Please go back and try again";
				}*/
				mail($row['email'],"Account Details Changed",$msg,"From:FORUM<forum@abhibhatia.co.cc>\r\nContent-type: text/html; charset=iso-8859-1");
		echo "<meta http-equiv='refresh' content='0; URL=home.php?msg=Your Account Details Were Changed Successfully'>";
		exit();
			
	}
?>