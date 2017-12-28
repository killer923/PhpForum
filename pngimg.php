<?php
session_start();session_cache_limiter("nocache");

header ("Content-type: image/png");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1

$rno = rand(1000,99999);
$_SESSION['captcha'] = md5($rno);
$img_handle = imageCreateFromPNG("data/images/bg1.PNG");
$r=rand(0,256);
$b=rand(0,256);
/*if($r-$b<50&&$r-$b>0){
		$g=rand(0,$b);
		}
else{if($b-$r<50&&$r-$b<0){
		$g=rand(0,$r);
		}
	else
		{*/
			$g=rand(0,256);
		/*}	
	}*/
$color = ImageColorAllocate ($img_handle, $r,$g,$b);
ImageString ($img_handle, 5, 20, 13, $rno, $color);
ImagePng ($img_handle);
ImageDestroy ($img_handle);?>