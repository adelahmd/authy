<?php
session_start();
session_destroy();
?>
<script>alert("You've been successfully logged out")</script>
<?php
header("Location: index.php");
?>
