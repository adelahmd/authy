$(document).ready(function(data){
	$("#tabs").tabs();
	$("#loginForm1").validate({
		rules:{
			email:{required:true,email:true},
			password:{required:true,minlength:7}
		},
		submitHandler:function(form){loginUser();}
		
	});		
	$("#regForm1").validate({
		rules:{
			email:{required:true,email:true},
                        password:{required:true,minlength:8},
			name:{required:true},
			mobnum:{required:true,minlength:7}

		},
                submitHandler:function(form){regUser();}

	});
});

function loginUser(){
	var email,password;
	email1=$("#email1").val();
	password1=$("#password1").val();
	$.post("api.php",{email:email1,password:password1,login:1},function(data){
                        procLoginOP(data);
        });
	return false;
}
function regUser(){
	var name,name1,email,email1,mobnum,mobnum1,password,password1;	
	name1=$("#name").val();
	email1=$("#email").val();
	password1=$("#password").val();
	mobnum1=$("#mobnum").val();
	cc1=$("#countries-input-0").val();
	$.post("api.php",{name:name1,password:password1,email:email1,mobnum:mobnum1,register:1,cc:cc1},function(data){
			procRegOP(data);
	});


}

function procRegOP(data){
	data=data.split("|");
	console.log(data);
	if(data[0]=="error"){
		errors=JSON.parse(data[1]);
		for(e in errors){
			console.log(e);
			for(e1 in errors[e]){
				$("#"+e).after("<label  class='error "+e+"-add-error'>"+errors[e][e1]+"</label>");
				$("#"+e).click(function(){$("."+e+"-add-error").remove()});
			}
		}
	}else{
		alert("Registration complete");
		$("#ui-id-1").trigger("click");
	}
	return false;
}

function procLoginOP(data){
	console.log(data);
	data=data.split("|");
	console.log(data);
	if(data[0]!='error'){
		$.prompt(postLogin);
	}else{
		data=JSON.parse(data[1]);
		alert(data["email"]);
	}
	return false;
}
var postLogin= {
	state0: {
		title: 'SoftToken',
		html:'<input style="width:200px;" type="text" name="token" id="token" value="" placeholder="Please input the sms token code here"></label>',
		buttons: { Check: 1 },
		submit:function(e,v,m,f){
			tokenCheck=false;
			token1=$("#token").val();
		$.post("api.php",{token:token1,tokenSub:1},function(data){
	
			if(data=="success"){ 
				$.post("api.php",{token2:1},function(data){
				
				});
				$.prompt.goToState('state1');
				
				updateT2State(data);
			}else{
				alert("Incorrect token");
			}
			
        	});

			e.preventDefault();
		}
	},
	state1:{
		title: 'OneTouch',
		html: '<p id="t2state"></p>'
	}
}

function procTokOP(data){
	$.get('api.php?home',function(data){
		$("#content").html(data);
	});	
}
var t2state=false;
function updateT2State(data){

	  $.post("api.php",{token2:1},function(data){
					console.log(data);
                                	$("#t2state").html((data=='pending'?'Pending':(data=='denied'?'Denied':'Approved')));
					if(data=='approved'){
						alert("You've been successfully logged in");
						$.prompt.close();
						procTokOP("done");
					}
				       if(data=="pending"){
						window.setTimeout(updateT2State(data),1000);
					
					}
                                });

	console.log(data);
}
function logout(){
	window.location="logout.php";
}
