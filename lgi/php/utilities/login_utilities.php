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
	setErrorMessage("Server Configuration Error. Please report to web-administrator.");
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
		setErrorMessage("Server Error. Please contact web administrator");
		showErrorPage();
	}

	//validate username and password for preventing SQL injection
	$username=mysql_real_escape_string($user);
	$pswd=mysql_real_escape_string($password);

	$query="SELECT passwordHash,salt FROM users WHERE userId='".$username."'";
	$result=mysql_query($query);
	if (!$result) {
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error. Please contact web administrator.");
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
	if(strcmp($_SESSION['user'],$user)==0)
	{
		if(isset($_SESSION['certificate']))          //if reference to certificate is set in session, no need to query database
		{
			return $_SESSION['certificate'];
		}
			
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
		$query="SELECT certificate FROM usercertificates WHERE userId='".$username."'";
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
		{
			$_SESSION['certificate']=$row[0];       //save the reference certificate in session. so next time you dont have to query database again.
			return $row[0];
		}
		else return NULL;
	}
	else
	return NULL;
}

/**
 * Retreives the reference to key of the user stored in database
 * @param string $user
 * @return string
 */
function getKeyFile($user)
{
	if(strcmp($_SESSION['user'],$user)==0)
	{
		if(isset($_SESSION['key']))          //if reference to key is set in session, no need to query database
		{
			return $_SESSION['key'];
		}
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
		$query="SELECT userkey FROM usercertificates WHERE userId='".$username."'";
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
		{
			$_SESSION['key']=$row[0];       //save the reference key in session. so next time you dont have to query database again.
			return $row[0];
		}
	}
	else
	return NULL;
}

/**
 * Authenticate using HTTP DIGEST authentication
 * @return true if successfully authenticated
 */
function authenticateDigest()
{

	$realm = 'Restricted area';

	//user => password
	//TODO: Instead use a database to store usernames and passwords(plain text)
	$users = array('admin' => 'mypass', 'deepthi' => 'deep1');


	if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
		header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Digest realm="'.$realm.
               '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
		//return false;
		die('Could not authenticate');
	}


	// analyze the PHP_AUTH_DIGEST variable
	if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])))
	{
		die('Wrong Credentials!');
		// return false;
	}
	/* *************************************** */

	global $mysql_server,$mysql_user,$mysql_password,$mysql_dbname;
	$connection = mysql_connect($mysql_server, $mysql_user, $mysql_password) or die(mysql_error());
	if(!mysql_select_db($mysql_dbname, $connection))
	{
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error. Please contact web administrator");
		showErrorPage();
	}

	//validate username and password for preventing SQL injection
	$username=$data['username'];

	$query="SELECT password FROM digestusers WHERE userId='".$username."'";
	$result=mysql_query($query);
	if (!$result) {
		error_log("Error:".mysql_error());
		//Set an error message and redirect to an error page. This message is seen by user.
		setErrorMessage("Server Error. Please contact web administrator.");
		showErrorPage();
	}
	$row= mysql_fetch_row($result);
	mysql_close($connection);
	//if there is a record in the database for the user
	if($row)
	{
		$password=$row[0];       //get the password
			
	}
	else
	{
		//invalid user
	}

	/* ************************** */


	// generate the valid response
	//TODO: instead of $users[$data['username']], get password by querying database
	$A1 = md5($data['username'] . ':' . $realm . ':' . $password);
	$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
	$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

	if ($data['response'] != $valid_response)
	die('Wrong Credentials!');
	setValidSession($data['username']);
	return true;

}

/**
 * function to parse the http auth header. To be used by authenticateDigest()
 */
function http_digest_parse($txt)
{
	// protect against missing data
	$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
	$data = array();
	$keys = implode('|', array_keys($needed_parts));

	preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

	foreach ($matches as $m) {
		$data[$m[1]] = $m[3] ? $m[3] : $m[4];
		unset($needed_parts[$m[1]]);
	}
	return $needed_parts ? false : $data;
}
?>
