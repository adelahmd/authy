<!DOCTYPE html>
<html>
<head>
<title>User</title>
<script
			  src="https://code.jquery.com/jquery-1.12.4.min.js"
			  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
			  crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel='stylesheet' href='ninja.css'>
<script src='ninja.js'></script>

<link href="https://www.authy.com/form.authy.min.css" media="screen" rel="stylesheet" type="text/css">
<script src="https://www.authy.com/form.authy.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.min.js"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-impromptu/6.2.3/jquery-impromptu.min.css'>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-impromptu/6.2.3/jquery-impromptu.min.js'></script>
</head>
<body>
	<div id='content'>
<?php
include "templ.php";
	if(!isset($_SESSION['user'])){
		  loginRegForm();
	}else{
		homePage();
	}

?>
	</div>
</body>
</html>
