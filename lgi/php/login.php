<?php
/**
 * This file handles user login. 
 * @author Deepthi
 */
 
 /**
  *
  */
// Include the main class, the rest will be automatically loaded
include 'Dwoo/dwooAutoload.php';
include 'utilities/login_utilities.php';
include 'utilities/sessions.php';

	
	$dwoo = new Dwoo(); 

	session_start();
	//if user has already logged in redirect to home page. 
        if(checkValidSession())
        {		
		header("Location: home.php");

        }
	else   //authenticate user
	{
		$user=strip_tags($_POST['name']); //HTML tags are stripped for preventing cross site scripting. $user is later stored in session.
		$paswd=$_POST['password'];
                if(verifyUserPassword($user,$paswd))
                {
			//Create a new session for the user
			setValidSession($user);	
			//user has logged in. Got to home		
			header("Location: home.php");
                }
                else
                {
                	//Username or password does not match a valid user. So request for relogin.
			relogin();				
                }
	}
?>