<?php
/**
 * This file handles user login.
 * @author Deepthi
 */

/**
 *
 */
// Include the main class, the rest will be automatically loaded
require_once 'Dwoo/dwooAutoload.php';
require_once 'utilities/login_utilities.php';
require_once 'utilities/sessions.php';
require_once 'utilities/errors.php';


$dwoo = new Dwoo();

session_start();
//if user has already logged in redirect to home page.
if(checkValidSession())
{
	header("Location: home.php");

}
else   //authenticate user
{
	$valid=false;
	if(strcmp(_AUTH_MECHANISM_,"DATABASE")==0)
	{
		$user=strip_tags($_POST['name']); //HTML tags are stripped for preventing cross site scripting. $user is later stored in session.
		$paswd=$_POST['password'];
		$valid=verifyUserPassword($user,$paswd);
		if($valid)
			setValidSession($user);

	}
	else if(strcmp(_AUTH_MECHANISM_,"DIGEST")==0)
	{
		$valid=authenticateDigest();
	}
	else
	{
		error_log("Configuration Error: Invalid Authentication Mechanism in lgi.config.php");
		pushErrorMessage("Server Configuration Error. Please contact server administrator");
		showErrorPage();
	}

	if($valid)
	{
		//user has logged in. Go to home
		header("Location: home.php");
	}
	else
	{
		//Username or password does not match a valid user. So request for relogin.
		pushErrorMessage("Invalid username or password. Try Again.");
		relogin();
	}
}
?>
