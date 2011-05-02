function Ajax(){
	var ajax_box;
	var req;
	
	this.Init=function (){
	    try{
	        req=new ActiveXObject("Msxml2.XMLHTTP");
	    }
	    catch(e){
	        try{
	            req=new ActiveXObject("Microsoft.XMLHTTP");
	        }
	        catch(oc){
	            req=null;
	        }
	    }
	    if(!req&&typeof XMLHttpRequest!="undefined"){
	        req = new XMLHttpRequest();
		}
		return req;
	}
	
	this.get=function(url,div){
	  	this.Init();
	  	if(req!=null){
	  		ajax_box=div;
		    req.onreadystatechange = this.getResult;
		    if(ajax_box)document.getElementById(ajax_box).innerHTML="<div style=\"text-align:center\"><img alt=\"Loading...\" src=\"images/loading.gif\" /></div>";
		    url=url.replace(/&amp;/g,'&');
		    req.open("get", baseurl+url, true);
		    try {
		       req.send(null);
		    } catch (ex) { if(ajax_box)document.getElementById(ajax_box).innerHTML="Your browser does not support this feature!";}
		}
	}
	
	/*get2 - imagen alternativa loadingB.gif*/
	this.get2=function(url,div){
	  	this.Init();
	  	if(req!=null){
	  		ajax_box=div;
		    req.onreadystatechange = this.getResult;
		    if(ajax_box)document.getElementById(ajax_box).innerHTML="<div style=\"text-align:center\"><img alt=\"Loading...\" src=\"images/loadingB.gif\" /></div>";
		    url=url.replace(/&amp;/g,'&');
		    req.open("get", baseurl+url, true);
		    try {
		       req.send(null);
		    } catch (ex) { if(ajax_box)document.getElementById(ajax_box).innerHTML="Your browser does not support this feature!";}
		}
	}
	
	this.post=function(url,data,div){
		this.Init();
	  	if(req!=null){
	  		ajax_box=div;
		    req.onreadystatechange = this.getResult;
		    if(ajax_box != 'comments_box') 
				document.getElementById(ajax_box).innerHTML="<div style=\"text-align:center\"><img alt=\"Loading...\" src=\"images/loading.gif\" /></div>";
		    url=url.replace(/&amp;/g,'&');
		    req.open("post", baseurl+url, true);
		    try {
			   req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		       req.send(data);
		    } catch (ex) { document.getElementById(ajax_box).innerHTML="Your browser does not support this feature!";}
		}
	}
	
	this.post2=function(url,data,div){
		this.Init();
	  	if(req!=null){
	  		ajax_box=div;
		    req.onreadystatechange = this.getResult;
		    document.getElementById(ajax_box).innerHTML="<div style=\"text-align:center\"><img alt=\"Loading...\" src=\"images/loading.gif\" /></div>";
		    url=url.replace(/&amp;/g,'&');
		    req.open("post", baseurl+url, true);
		    try {
		       //req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");	
		       req.send(data);
		    } catch (ex) { document.getElementById(ajax_box).innerHTML="Your browser does not support this feature!";}
		}
	}
	
	this.getResult=function (){
	  if (req.readyState == 4){
	    if (req.status == 200){
	      if(ajax_box!='comments_box'){
		      changeOpacity(0,ajax_box);
		      fadeIn(ajax_box);
	      }
	      document.getElementById(ajax_box).innerHTML=req.responseText;
	    }
	    else {
	        document.getElementById(ajax_box).innerHTML="Internal Server Error!";
	    }
	  }
	}
}

function Init(){
    try{
        req=new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e){
        try{
            req=new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(oc){
            req=null;
        }
    }
    if(!req&&typeof XMLHttpRequest!="undefined"){
        req = new XMLHttpRequest();
	}
}

function get_url_contents(url,div){
	var ajax=new Ajax();
	ajax.get(url,div);
}

/*
function rate(id,rate){
	var ajax=new Ajax();
	ajax.get("index.php?m=rate&id="+id+"&rate="+rate+"&asynch",'ajax_rate');
}
*/
function rate(id,rate){
	var ajax=new Ajax();
	ajax.get2("index.php?m=rate&id="+id+"&rate="+rate+"&asynch",'ajax_rate');
}

function get_video(id, next, p, is_adviced, qua, exists_hd){
	var ajax=new Ajax();
	ajax.get2("player.php?m=video&id="+id+"&next="+next+"&p="+p+"&is_adviced="+is_adviced+"&qua="+qua+"&exists_hd="+exists_hd,'player_shadow');
}

function add_to_favorites(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=favorites_add&id="+id+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function add_to_playlist(id, plid){
  	Init();
  	if(req!=null && plid!=0 && plid!="new"){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=favorites_add&id="+id+"&p="+plid+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}else if (plid=="new"){
		location.href=baseurl+"index.php?m=users.create_playlist";
	}
}

function report_video(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=report_video&id="+id+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function report_comment(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=report_comment&id="+id, true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function show_playlist(id, pub, title){
	    
    document.getElementById('playlist_id').value=id;
    document.getElementById('edit_playlist').style.display="block";
    
    //if(pub==1) document.getElementById('public').checked=true;
    //else document.getElementById('public').checked=false;
    
    var ajax=new Ajax();
	ajax.get("index.php?m=playlist&p="+id,'ajax_box');
    
    document.getElementById('pl_description').value=title.getAttribute('title');

}

function post_comment(url, form){
	var send="";
    var i;
	for(i=0; i<form.elements.length; i++){
	  var el=form.elements[i];
	  send+=form.elements[i].name+"="+escape(el.value)+"&";
	}
	var ajax=new Ajax();
	ajax.post(url,send,'comments_box');
}
 
/*Funcion que recarga el ajax_box con el comentario recien hecho en navegador cliente aunque no est� aprobado*/
function post_comment2(url, form){
	var send="";
    var i;
	for(i=0; i<form.elements.length; i++){
	  var el=form.elements[i];
	  send+=form.elements[i].name+"="+escape(el.value)+"&";
	}
	var ajax=new Ajax();
	ajax.post2(url,send,'comments_box');
}


function getResult_alert(){
  if (req.readyState == 4){
    if (req.status == 200){
      alert(req.responseText);
    }
    else {
      alert("Internal Server Error!");
    }
  }
}

function validate(form, validators){
	var send="";
    var i;
	for(i=0; i<validators.length; i++){
	  var el=document.getElementById(validators[i].field);
	  send+="fields["+i+"]="+escape(validators[i].field)+"&values["+i+"]="+escape(el.value)+"&validators["+i+"]="+escape(validators[i].validator)+"&";
		
	  el.style.borderColor="#000000";
	  el.style.borderStyle="solid";
	  el.style.borderWidth="1px";
	  el.style.backgroundColor="#ffffff";
	  if(el.nextSibling)el.parentNode.removeChild(el.nextSibling);
	}
	Init();
  	if(req!=null){
  		validate_form=form;
  		validate_validators=validators;
	    req.onreadystatechange = getResult_validate;
	    req.open("post", baseurl+"index.php?m=validate", true);
	    try {
	       req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	       req.send(send);
	    } catch (ex) { }
	}
	return false;
}

function getResult_validate(){
   	if (req.readyState == 4 && req.status == 200){
		if(req.responseText!=''){
			var result = eval('('+req.responseText+')');
			var i=0;
			for(i=0; i<result.length; i++){
			  var el=document.getElementById(result[i]);
			  el.style.borderColor="#ff0000";
			  el.style.backgroundColor="#ffeeee";
			  
			  var msg='';
			  //find the fields's error message
			  var j=0;
			  for(j=0; j<validate_validators.length; j++){
			  	if(validate_validators[j].field==result[i]){
			  		msg=validate_validators[j].message;
			  		break;
			  	}
			  }
			  
			  var notice=document.createElement("span");
			  notice.style.color="#ff0000";
			  notice.innerHTML='&nbsp;'+msg;
			  el.parentNode.insertBefore(notice, el.nextSibling);
			  
			}
		}else{
			validate_form.submit();
		}
	}
}