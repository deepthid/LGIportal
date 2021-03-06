<?php
/**
	This file has utility functions for session management
*	@author Deepthi
*	@package utilities
*/

	/**
	*	Checks whether current session corresponds to an authenticated user. Returns True if session is valid, otherwise returns false.
	*	@return boolean 
	*/	
	function checkValidSession()
	{
		if(isset($_SESSION['user']))
			return true;
		else
			return false;
	}

	/**
	* Clear previous session and sets a new session. Usefull when a user login. The username of user logged in should be passed.
	* @param string $user username of the user logged in	
	*/
	function setValidSession($user)
	{
		//$user :- expecting a clean username, where it is processed for preventing cross site scripting.
		session_unset();
		session_destroy();
		INI_Set('session.cookie_secure',true);		
		//TODO: check whether https is enabled. Otherwise generate a warning/error
		session_start();
		session_regenerate_id(true);
		$_SESSION['user']=$user;
		return true;
	}
?> 
