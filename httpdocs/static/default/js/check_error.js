$(document).ready(function(){
	
	var inputs = document.forms['register'].getElementsByTagName('input');
	  var run_onchange = false;
	  function valid(){
	   var errors = false;
	   var reg_mail = /^[A-Za-z0-9]+([_\.\-]?[A-Za-z0-9])*@[A-Za-z0-9]+([\.\-]?[A-Za-z0-9]+)*(\.[A-Za-z]+)+$/;
	   for(var i=0; i<inputs.length; i++){
		var value = inputs[i].value;
		var id = inputs[i].getAttribute('id');
	    if(id==null){continue;}
		
		var span = document.createElement('span');
		
		var p = inputs[i].parentNode;
		if(p.lastChild.nodeName == 'SPAN') {p.removeChild(p.lastChild);}
	   
		
		if(value == ''){
		 span.innerHTML =' ';
		}else{
		
		 if(id == 'email'){
		  if(reg_mail.test(value) == false){ span.innerHTML =' ';}
		  var email =value;
		 }
		 if(id == 'confirm_email' && value != email){span.innerHTML ='Email nhập lại chưa đúng';}
		
		 if(id == 'password'){
		  if(value.length <6){span.innerHTML ='Password phải từ 6 ký tự';}
		  var pass =value;
		 }
		
		 if(id == 'confirm_pass' && value != pass){span.innerHTML ='Password nhập lại chưa đúng';}
		 
		 if(id == 'phone' && isNaN(value) == true){span.innerHTML =' ';}
		}
	   
		
		if(span.innerHTML != ''){
		 inputs[i].parentNode.appendChild(span);
		 errors = true;
		 run_onchange = true;
		 inputs[i].style.border = '1px solid #c6807b';
		 inputs[i].style.background = '#fffcf9';
		}
	   }// end for
	  

	   return !errors;
	  }// end valid()
	 
	  
	  var register = document.getElementById('formsubmit');
	  register.onclick = function(){
	  
	   return valid();
	  }
	 
	 
	   for(var i=0; i<inputs.length; i++){
		var id = inputs[i].getAttribute('id');
		inputs[i].onchange = function(){
		 if(run_onchange == true){
		  this.style.border = '1px solid #dedede';
		  this.style.background = '#fff';
		  valid();
		 }
		}
	   }// end for
});