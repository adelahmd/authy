<?php
	session_start();

	$authykey="ZbHrKcCUoQXIUSOxAVn5tsTxW4zM74SO";
	$authytest="41f3fe0a27e1c9cba05c30933811a2b8";
	$salt="f0sh1zzle";

//Registration API
function register(){
	global $salt;
	$email=filter_var(strtolower($_POST['email']),FILTER_SANITIZE_EMAIL);
	$password=md5("$_POST[password]$salt");
	$name=filter_var($_POST['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$mobnum=filter_var($_POST['mobnum'],FILTER_SANITIZE_NUMBER_INT);
	$errors=array();
	$cc=filter_var($_POST['cc'],FILTER_SANITIZE_NUMBER_INT);
	empty($cc)?$errors['cc'][]="Please provide a country code":"";
	empty($email)?$errors['email'][]="Please provide an email address":"";
	empty($name)?$errors['name'][]="Please provide a name":"";
	empty($mobnum)?$errors['mobnum'][]="Please provide a mobile number":"";
	strlen($mobnum)<8||strlen($mobnum)>15?$errors['mobnum'][]="Please provide a valid mobile number":"";
	strlen($password)<8?$errors['password'][]="Password cannot be shorter than 8 characters":"";
	$conn=getConn();
	$u=$conn->query("select * from ninjas where email='$email' or mobnum='$mobnum'");
	$u->num_rows>0?$errors['email'][]="User already exists":"";
	$u=$u->fetch_assoc();

	if(sizeof($errors)>0)die("error|".json_encode($errors));
	$conn->query("insert into ninjas(name,email,password,mobnum,cc) values('$name','$email','$password','$mobnum','$cc')") or die("error|Database connection failed.");
	$postfields = array('user[email]'=>$email, 'user[cellphone]'=>$mobnum,'user[country_code]'=>$cc);
		
	$result=authyShiz(0,$postfields);
	$result['success']==1?$conn->query("update ninjas set aid='".$result['user']['id']."' where email='$email'"):$conn->query("delete from ninjas where email='$email'")&&die("error|".json_encode(array("email"=>"Failed to add user")));
	
//		
	}
	
//Login API
function login(){
	global $salt;
        $email=filter_var(strtolower($_POST['email']),FILTER_SANITIZE_EMAIL);
        $password=md5("$_POST[password]$salt");
	$errors=array();
	empty($email)?$errors['email'][]="Please provide an email address":"";
        strlen($password)<8?$errors['password'][]="Password cannot be shorter than 8 characters":"";
	if(sizeof($errors)>0)die("error|".json_encode($errors));
	$conn=getConn();
	$u=$conn->query("select * from ninjas where email='$email' and password='$password'");
	if($u->num_rows<1)die("error|".json_encode(array("email"=>"Incorrect login information")));
	$u=$u->fetch_assoc();
	$_SESSION['authyid']=$u['aid'];
	$_SESSION['email']=$u['email'];
	$_SESSION['name']=$u['name'];

	$postfields=array();
	$result=authyShiz(1,$postfields,$u['aid']);
}

//SoftToken API
function checkToken(){

	//https://api.authy.com/protected/{FORMAT}/verify/{TOKEN}/{AUTHY_ID} 
	$token=filter_var($_POST['token'],FILTER_SANITIZE_NUMBER_INT);
	
	$result=authyShiz(2,array(),$_SESSION['authyid'],$token);
	$_SESSION['auth']=1;	
	echo $result['success']==true?"success":"error";
		
	

}
//OneTouch API
function checkToken2(){
	global $authykey;
	if(!isset($_SESSION['onetouch'])||$_SESSION['onetouch']==''){
		$result=authyShiz(3,array("message"=>"Login requested for application. Please note that this token is valid only for 10 minutes.","details[username]"=>$_SESSION['name'],"details[Account Number]"=>$_SESSION['authyid'],"seconds_to_expire"=>600,"api_key"=>$authykey),$_SESSION['authyid']);
		echo $result['success']==true?"success":"error";
		if($result['success'])$_SESSION['onetouch']=$result['approval_request']['uuid'];
	}else{
		$result=authyShiz(4,array());
		if($result['approval_request']['status']!='pending')$_SESSION['onetouch']='';
		echo $result['approval_request']['status'];
	}
}
if(isset($_POST['register'])){
	register();	
}
if(isset($_POST['login'])){
	login();
}
if(isset($_POST['tokenSub'])){
	checkToken();
}
if(isset($_GET['home'])&&isset($_SESSION['auth'])){
	include "templ.php";
	homeTab();
}
function getConn(){
        $conn=new mysqli('localhost','root','f0sh1z','ninja') or die("Database connection error");
	return $conn;
}
function authyShiz($action,$postfields,$authyid=0,$token=0){
	$_SESSION['onetouch']=!isset($_SESSION['onetouch'])?"":$_SESSION['onetouch'];
	global $authykey; 
	$actions=array('https://api.authy.com/protected/json/users/new',"https://api.authy.com/protected/json/sms/$authyid?api_key=$authykey","https://api.authy.com/protected/json/verify/$token/$authyid?api_key=$authykey","https://api.authy.com/onetouch/json/users/$authyid/approval_requests","https://api.authy.com/onetouch/json/approval_requests/$_SESSION[onetouch]");
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $actions[$action]);
      	$action==0||$action==3||$action==4?curl_setopt($ch, CURLOPT_HTTPHEADER,array("X-Authy-API-Key: $authykey")):"";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $action==0||$action==3?curl_setopt($ch, CURLOPT_POST, 1):"";
        $action==0||$action==3?curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields):"";
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        $result = curl_exec($ch);       
	file_put_contents("err",$result);
	$result=json_decode($result,1);
	return $result;
}
if(isset($_REQUEST['token2'])){
	checkToken2();
}
