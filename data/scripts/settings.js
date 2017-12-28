function show(){
	document.getElementById("dynamic").style.display="block";
	document.getElementById("dynamic").innerHTML="<table><tr>\n\t<td>Enter current password:</td><td><input type=\"password\" name=\"ppass\" size=\"45\"></td></tr><tr>\n<td>Enter new password:</td><td><input type=\"password\" name=\"npass1\" size=\"45\"></td></tr><tr>\n<td>Confirm password:</td><td><input type=\"password\" name=\"npass2\" size=\"45\"></td></tr><tr><td colspan=2><input type=submit value=\"Save\"></tr></table>\n";
	document.setting.question[1].checked=true;
	document.getElementById("dynamic").style.opacity=1;
	}
function hide(){
	if(document.forms.setting.question[0].checked!=true){
		fade("dynamic",50);
		}
	}
function appear(){
	document.getElementById("dynamic").style.display="block";
	document.getElementById("dynamic").style.opacity="1";
	/*document.getElementById("dynamic").style.filter:alpha(opacity=1);
    document.getElementById("dynamic").style.-moz-opacity:1;
    document.getElementById("dynamic").style.-khtml-opacity: 1;
*/
	document.getElementById("dynamic").innerHTML="<table><tr>\n\t<td>Enter password:</td><td><input type=\"password\" name=\"ppass\" size=\"45\"></td></tr><tr><td>Security Question:</td><td><input type=\"text\" name=\"ques\" size=\"100\"></td></tr><tr><td>Answer:</td><td><input type=\"password\" name=\"ans\" size=\"10\"></td></tr><tr><td colspan=2><input type=submit value=Save></tr></table>";
	document.setting.pass[1].checked=true;
	}
function disappear(){
	if(document.forms.setting.pass[1].checked){
		slide("dynamic",50);
		}
	}

function fade(id,time){
	var i=0;
	for(;i<10;i++){
		setTimeout("document.getElementById('dynamic').style.opacity-=0.1;document.getElementById('dynamic').style.filter='progid:DXImageTransform.Microsoft.Alpha(Opacity=((10-i)*10))'",(i*time));
		}
	i--;
	setTimeout("gayab('"+id+"')",(i*time));
	}
function gayab(id){	id=document.getElementById(id);
	id.style.display="none";
	id.innerHTML="";
	id.style.opacity=1;
	}
function slide(id,time){
	var i=1;
	document.getElementById(id).style.height="150px";
	for(;i<15;i++){
		//var j="shorten("+id+","+i+")";
		//var k=i*time;
		setTimeout("shorten('"+id+"',"+i+")",i*time);
		}
	var j="gone('"+id+"')";
	var k=i*time;
	setTimeout(j,k);
	}
function shorten(id,i){	id=document.getElementById(id);
	id.style.height=((15-i)*10)+"px";
	}
function gone(id){	id=document.getElementById(id);
	id.style.display="none";
	id.style.height="150px";
	}