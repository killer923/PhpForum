<?php
session_start();
if($_SESSION['log_status']==1){//already logged in
	echo "<script language=javascript>window.location='home.php';</script>";
	exit(1);
	}
$cache_expire = 60*60*24*365;
 header("Pragma: public");
 header("Cache-Control: max-age=".$cache_expire);
 header('Expires: '. gmdate('D, d M Y H:i:s', time()+$cache_expire).' GMT');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta keywords="forum,email,community">
	<meta name="robots" content="index,follow,archive,imageindex,imageclick" />
	<title>Welcome to Forum</title>
	<script language="javascript" src="data/scripts/capcha.js">	</script>
	<noscript></head><body>We Recommend That You Update Your browser Immediately.</noscript>
	<style>#fb_login{background:url('data/images/fb.jpg');cursor:pointer;background-repeat:no-repeat}
		table{background:none repeat scroll 0 0 #9ABECC;box-shadow:-2px 0 5px -2px #009F82;}
		table:hover{background:none repeat scroll 0 0 #C3C3C3;}
		.twitter_button {border: none;background: url('data/images/twitter.png') no-repeat top left;padding-top:10px;padding-left:100px;cursor:pointer;}
	</style>
	<!-- Facebook Scripts-->
	<script src="//connect.facebook.net/en_US/all.js"></script>
	<div id="fb-root"></div>
	<script>
	  window.fbAsyncInit = function() {
		// init the FB JS SDK
		FB.init({
		  appId      : '152497421494367',                        // App ID from the app dashboard
		  channelUrl : 'killer.heliohost.org/forum.abhibhatia.co.cc', // Channel file for x-domain comms
		  status     : true,                                 // Check Facebook Login status
		  xfbml      : true                                  // Look for social plugins on the page
		});

		// Additional initialization code such as adding Event Listeners goes here
	  };

	  // Load the SDK asynchronously
	  (function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "//connect.facebook.net/en_US/all.js";
		 fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>
</head>
<Body style="background:none repeat scroll 0 0 #ABDA99;">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=152497421494367";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
	if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; }
?>
<p align=right>
<p align=center>HI<br>Bored of frustating forums<br>try us!!!!</p>
<p>Note: You can still view the posts <a href=posts.php>here</a>.</p>
<table align=left style="margin-right:150px;">
	<tr><td>LOG IN</td></tr>
<form action="login.php<?php if(isset($_GET['continue'])) echo "?continue=".$_GET['continue'];?>" method="post">
	<tr>
		<td>Enter username:</td>
		<td><input type="text" name="username" size="45"></td>
	</tr>
	<tr>
		<td>Enter password:</td>
		<td><input type="password" name="password" size="45"></td>
	</tr>
	<tr>
	<td><a href=nuser.php>Register</a></td><td><a href=forgot.php>Forgot Password</a></td>
	<tr>
		<td colspan=2><input type="Submit" name="Send" value="Login"></td>
	</tr>
</form>
</table>

<table align="right">
<tr><td>REGISTER</td></tr>
<form action="cuser.php" method="post" align="left">
	<tr>
		<td>Enter NAME:</td>
		<td><input type="text" name="name" size="45"></td>
	</tr>
	<tr>
		<td>Enter username desired:</td>
		<td><input type="text" name="uname" size="45"></td>
	</tr>
	<tr>
		<td>Enter Valid email id:</td>
		<td><input type="text" name="email" size="45"></td>
	</tr>
	<tr>
		<td><img src="pngimg.php" align="middle" type="image/x-png" name="capcha"><br><a href="#" onClick="new_capcha();" style="font-size:15px;">new image</a></td>
		<td><input type=text name=code size=45></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="send" value="Register"></td>
	</tr>
</form>
</table>
<table>
<tr><td>OTHER LOGIN OPTIONS</td></tr>
<script language=javascript>
	function go(){
		a="login.php<?php if(isset($_GET['continue'])) echo "?continue=".$_GET['continue']."&type=fb"; else echo"?type=fb";?>";
		window.location=a;
	}
	function twit(){
		a="login.php<?php if(isset($_GET['continue'])) echo "?continue=".$_GET['continue']."&type=twitter"; else echo"?type=twitter";?>";
		window.location=a;
	}
</script>
<!--
<input type="button" onclick=go(); id="fb login" value="FB Login">
-->
<!--<tr><td><div class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" size="small" registration-url="login.php?type=fb"></div></td></tr>-->
<tr><td><div data-width="200" data-max-rows="1" id=fb_login><a class="fb_button fb_button_medium" onclick=go();><span class="fb_button_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a></div></td></tr>
<tr><td><input type=button class="twitter_button" onclick=twit(); value="                  "></td></tr>
</table>
</body>
</html>
