<?php
function loginRegForm(){

		?> <div id="tabs">
		<ul>
			<li><a href='#loginForm'>Login</a></li>
			<li><a href='#regForm'>Register</a></li>
		</ul>
		<div id="regForm">
	        	<form  action="" class='pure-form' id='regForm1'  method="post">
	                	<input type='text' id='name' name='name' placeholder='Full Name'><br>
				<input type='text' id='email' name='email' placeholder='Email'><br>
	                	<select class='authy-cnt'  id='authy-countries' data-show-as='number' name='mobnumext'></select>
				<input type='text' id='mobnum'  name='mobnum' placeholder='Mobile number' class='mobnum'><br>
	                	<input type='password' id='password'  name='password' placeholder='Password'><br>
				<input class='pure-button pure-button-primary loginReg'  id='regBtn' type='submit' name='register' value='Register'>
	      	  		
			</form>
		</div>
		<div id='loginForm'>
			<form  class='pure-form' action="" id='loginForm1' method="post">	
				<input type='text' id='email1' name='email' placeholder='Email address'><br>
				<input type='password' id='password1' name='password' placeholder='Password'><br>
				<input type='submit' name='login' value='Login' class='pure-button pure-button-primary loginReg' id='regBtn'>
			</form>
		</div>
	</div>	
<?php
	}
function tokenForm(){
?>
	<div id='tokenForm'>
		<form action="" class='pure-form' id='tokenFOrm1' method='post'>
		<input type='text' name='token' placeholder='Token code'><br>
		<input type='submit' name='token' value='Check'>
		</form>
	</div>
<?php
}
function homeTab(){
?>

	 <div id="tabs">
                <ul>
                        <li><a href='#homeTab'>Home</a></li>
			<li><a href='#logout' onclick='logout()'>Logout</a></li>
                </ul>
                <div id="homeTab">
                	<h3>Welcome back <?php echo $_SESSION['name'];?></h3>
		</div>
        </div>
	<script>$("#tabs").tabs();</script>
<?php

}
?>
