function new_capcha(){
	if(document.images.capcha){
		document.images.capcha.src="pngimg.php?rand="+Math.random();
		}
	else if(document.images[0]){
		document.images[0].src="pngimg.php?rand="+Math.random();
		}
	}