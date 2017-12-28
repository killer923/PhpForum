<?php
require_once 'data/scripts/pass_gen.php';
session_start();
//print_r($_SESSION);
if(strip_tags($_POST['submit'])=="Associate"){ //on return
	$emailid=$_SESSION['email'];
	$twit_name=$_SESSION['all']['screen_name'];
	$fullname=$_SESSION['all']['name'];
	$dbc= mysql_connect('db_host','username','password')
	or die('ERROR:Problem Connecting server');
	mysql_select_db("db_name", $dbc);
	if(strip_tags($_POST['options'])=="yes"){
		$user=strip_tags($_POST['uname']);
		$pwd=strip_tags($_POST['pwd']);
		$query=sprintf("SELECT * FROM users WHERE uname='%s'",mysql_real_escape_string($user));
		$check=mysql_query($query);
		if(mysql_num_rows($check)!=1){
			if(isset($_GET['continue']))
			echo "<meta http-equiv='refresh' content='0; URL=twitter_connect.php?msg=Username not found&continue=".$_GET['continue']."'>";	
			else echo "<meta http-equiv='refresh' content='0; URL=twitter_connect.php?msg=Username not found'>"; 	
			exit();
		}
		$row=mysql_fetch_array($check, MYSQL_ASSOC);
		if((md5($pwd))!=$row["psswd"]){
			if(isset($_GET['continue']))
				echo "<meta http-equiv='refresh' content='0; URL=twitter_connect.php?msg=Username and Password do not match&continue=".$_GET['continue']."'>";	
			else echo "<meta http-equiv='refresh' content='0; URL=twitter_connect.php?msg=Username and Password do not match'>";	
			exit();
		}
		$query= sprintf("UPDATE users SET twitter_name = '%s' WHERE uname = '%s' LIMIT 1;",mysql_real_escape_string($twit_name),mysql_real_escape_string($row["uname"]));
		mysql_query($query) or die("not able to update");
		$_SESSION['log_status']=1;
		$_SESSION['email']=$row['email'];
		$query= sprintf("UPDATE users SET acc_status = '1', `log_status` = '1' WHERE uname = '%s' LIMIT 1;",mysql_real_escape_string($row["uname"]));
		mysql_query($query) or die("not able to update");
		$_SESSION['uname']=$user;
	}
	else if(strip_tags($_POST['options'])=="no"){
		//create account
		$check=mysql_query("SELECT * FROM users WHERE uname='".$twit_name."'")or die('ERROR:Problem during query');
		$number=mysql_num_rows($check);
		if($number!=0) { //user name taken or not there...ask to change
			if(isset($_GET['continue'])) echo"<script language=javascript>window.location='twit_email.php?uname=0&continue=".strip_tags($_GET['continue'])."';</script>";
			else echo"<script language=javascript>window.location='twit_email.php?uname=0';</script>";
			exit();
		}
		$_SESSION['uname']=$_SESSION['all']['screen_name'];
		if(isset($_GET['continue'])) echo"<script language=javascript>window.location='twit_email.php?continue=".strip_tags($_GET['continue'])."';</script>";
		else echo"<script language=javascript>window.location='twit_email.php';</script>";
		exit();
	}
	if(isset($_GET['continue'])) echo "<meta http-equiv='refresh' content='0; URL=".strip_tags($_GET['continue'])."'>";	
	else echo "<meta http-equiv='refresh' content='0; URL=home.php'>";	
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
		<script language="javascript">
			/*function associate(){
				var a;
				a=document.getElementById('association').options.value;
				if(a==yes){//associate
					var uname,pwd;
					uname=document.getElementById('uname').value;
					pwd=document.getElementById('pwd').value;
					}
				else {//no association needed
					}
				}*/
			function appear(){
				document.getElementById("dynamic").style.display="block";
				document.getElementById("dynamic").style.opacity="1";
				/*document.getElementById("dynamic").style.filter:alpha(opacity=1);
				document.getElementById("dynamic").style.-moz-opacity:1;
				document.getElementById("dynamic").style.-khtml-opacity: 1;
			*/
				document.getElementById("dynamic").innerHTML="<table><tr>\n\t<td>Enter username:</td><td><input type=\"text\" name=\"uname\" size=\"45\"></td></tr><tr><td>Enter password:</td><td><input type=\"password\" name=\"pwd\" size=\"45\"></td></tr></table>";
				//document.setting.options[1].checked=true;
				}
			function disappear(){
					fade("dynamic",50);
					
				}
			function fade(id,time){
				var i=0;
				for(;i<10;i++){
					setTimeout("document.getElementById('dynamic').style.opacity-=0.1;document.getElementById('dynamic').style.filter='progid:DXImageTransform.Microsoft.Alpha(Opacity=((10-i)*10))'",(i*time));
					}
				i--;
				setTimeout("gayab('"+id+"')",(i*time));
				}
			function gayab(id){	
				id=document.getElementById(id);
				id.style.display="none";
				id.innerHTML="";
				id.style.opacity=1;
				}
		</script>
		<noscript></head><body>You are Using an Outdated Browser.<br>Please Update Your Browser</body></noscript>	
		<title>Already a user?</title>
	</head>
<body>
<?php
if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; 
	}
if(isset($_GET['continue'])){
	if(isset($_GET['uname'])){
		if(strip_tags($_GET['uname'])=='none') $url='twitter_connect.php?uname=none&continue='.strip_tags($_GET['continue']).'';
		}
	else $url='twitter_connect.php?continue='.strip_tags($_GET['continue']).'';
	}
else $url='twitter_connect.php';

?>

<form id="association" action='<?php echo $url;?>' method="Post" name="setting">
Do you already have an account on this site that you would like to associate with this id?<br>
<table>
	<tr>
		<td><input type="radio" name="options" onclick="appear();" id="a" value="yes"><label for="a">YES</label></td>
		<td><input type="radio" name="options" onclick="disappear();" id="b" value="no"><label for="b">NO</label></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Associate">
	</tr>
</table>
<div id="dynamic"></div>
</form>
</body>
</html>
