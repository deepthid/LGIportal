<?php

// Include the main class, the rest will be automatically loaded
include 'Dwoo/dwooAutoload.php';
include 'php/utilities/sessions.php';

session_start();
if(checkValidSession()) //if already logged in redirect it to home
{	
	header("Location:./php/home.php");
	//echo "Hi! You have logged in.";
}
else
{
	$dwoo = new Dwoo(); 
	$dwoo->output('dwoo/login.tpl',new Dwoo_Data());
}
	
?>