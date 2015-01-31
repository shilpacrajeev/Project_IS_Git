<?php
		session_start();
		session_unset();
		$_SESSION=array();
		if(session_destroy())
		{
			header("Location: Login.php");
		}
		else
			die("Logout Failed");
?>