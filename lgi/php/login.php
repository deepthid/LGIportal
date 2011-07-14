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
		pushErrorMessage("Invalid username or password. Try Again.");
		relogin();
	}
}
?>
