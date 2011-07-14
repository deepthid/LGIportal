<?php
/**
 *	This file has utility functions for user authentication
 *	@author Deepthi
 *	@package utilities
 */

/**
 *
 */

//include necessary files
$root=realpath($_SERVER["DOCUMENT_ROOT"]);
require_once dirname(__FILE__).'/sessions.php';
require_once dirname(__FILE__).'/errors.php';
//include the global configration file. It contains $DB_CONFIG_FILE which is the file that has database access details.
//require $root.'/lgi/lgi.config.php';
require_once dirname(__FILE__).'/../../lgi.config.php';
//$DB_CONFIG_FILE is set by the administrator. Hence check whether the file exists or not. If file doesnot exists we cannot access database. Hence report error!
if(!file_exists($DB_CONFIG_FILE))
{
	//given path to the DB_configuration file is invalid. Generate an error.
	error_log("Error: in lgi.config.php: File not found ".$DB_CONFIG_FILE);
	//Redirect to an error page. Set an error message. This message is seen by user.
	setErrorMessage("Server Configuration Error.");
	showErrorPage();

}
//include the file having database access details
require $DB_CONFIG_FILE;

/**
 *	Checks whether username and passwords corresponds to a valid user. Returns True if credentials are valid otherwise returns false.
 *	@param string $user	plaintext username to be checked
 *	@param string $password	plaintext password
 *	@return boolean
 */
function verifyUserPassword($user,$password)	//input plain text username and password
{
	global $mysql_server,$mysql_user,$mysql_password,$mysql_dbname;
	$connection = mysql_connect($mysql_server, $mysql_user, $mysql_password) or die(mysql_error());
	if(!mysql_select_db($mysql_dbname, $connection))
	{
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}

	//validate username and password for preventing SQL injection
	$username=mysql_real_escape_string($user);
	$pswd=mysql_real_escape_string($password);

	$query="SELECT passwordHash,salt FROM Users WHERE userId='".$username."'";
	$result=mysql_query($query);
	if (!$result) {
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}

	$row= mysql_fetch_row($result);
	mysql_close($connection);
	if($row)	//if there is a record in the database for the user
	{
		$password_hash=$row[0];
		$salt=$row[1];
		return(strcmp(hashPassword($password,$salt),$password_hash)==0);	//check whether passwords match

	}
	else	//if there is no record in the database for the user
	{
		return false;
	}
}

/**
 *	Finds the hash of concatenated string of two parameters passed. Returns the resulting hash. Used for password hashing with salt.
 *	@param string $password
 *	@param string $salt
 *	@return string
 */
function hashPassword($password,$salt)
{
	//return md5($password.$salt);
	return hash("sha512",$password.$salt);
}

/**
 *	Requests user for relogin
 */
function relogin()
{
	header("Location: "._LGI_ROOT_."/index.php");
}

/**
 *	Checks whether current session belongs to an authenticated user. If not request for log in. To be called before doing any function where user authentication is required.
 */
function authenticateUser()
{
	if(checkValidSession())
	{
		return true;
	}
	else
	{
		relogin();
	}
}

/**
 * Retrieves the reference to certificate of the user
 * @param string $user
 * @return string
 */
function getCertificateFile($user)
{
	global $mysql_server,$mysql_user,$mysql_password,$mysql_dbname;
	$connection = mysql_connect($mysql_server, $mysql_user, $mysql_password) or die(mysql_error());
	if(!mysql_select_db($mysql_dbname, $connection))
	{
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}
	$username=mysql_real_escape_string($user); //$user will already be escaped. But this is for extra safety
	$query="SELECT certificate FROM Users WHERE userId='".$username."'";
	$result=mysql_query($query);
	if (!$result) {
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}

	$row= mysql_fetch_row($result);
	mysql_close($connection);
	//if there is a record in the database for the user	
	if($row)
		return $row[0];
	else return NULL;	
}

/**
 * Retreives the reference to key of the user stored in database
 * @param string $user
 * @return string
 */
function getKeyFile($user)
{
	global $mysql_server,$mysql_user,$mysql_password,$mysql_dbname;
	$connection = mysql_connect($mysql_server, $mysql_user, $mysql_password) or die(mysql_error());
	if(!mysql_select_db($mysql_dbname, $connection))
	{
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}
	$username=mysql_real_escape_string($user); //$user will already be escaped. But this is for extra safety
	$query="SELECT userkey FROM Users WHERE userId='".$username."'";
	$result=mysql_query($query);
	if (!$result) {
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error.");
		showErrorPage();
	}

	$row= mysql_fetch_row($result);
	mysql_close($connection);
	//if there is a record in the database for the user	
	if($row)
		return $row[0];
	else return NULL;	
}
?>
