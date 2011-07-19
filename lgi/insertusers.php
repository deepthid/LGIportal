<?php
/*
 * To delete
 */
 
 
function hashPassword($password,$salt)	
	{
		//return md5($password.$salt);
		return hash("sha512",$password.$salt);
	}
 function insertUser($user,$password)
	{
		
               global $connection;		
		$salt= substr(md5(uniqid(rand(), true)),0,19);
		$pswd=hashPassword($password,$salt);
		echo "<br/> Pswd ********** <br/>";
		echo $pswd;
		echo "<br/> salt  ********** <br/>";
		echo $salt; 
		
		$query="insert into Users values('".$user."','".$pswd."','".$salt."')";
		mysql_query($query) or die('Invalid query: ' . mysql_error().$query);		
	}
	
 function insertUsers()
 {
     $user='deep';
     $pswd='deep1';
     insertUser($user,$pswd);
     $user='dev';
     $pswd='dev1';
     insertUser($user,$pswd);
     $user='wil';
     $pswd='wil1';
     insertUser($user,$pswd);     
 }
 
 $mysql_server='localhost';
	$mysql_user="root";
        $mysql_password="";
        $mysql_dbname="sample";	
 $connection = mysql_connect($mysql_server, $mysql_user, $mysql_password) or die(mysql_error());
 mysql_select_db($mysql_dbname, $connection) or die(mysql_error());
 
 insertUsers();
 echo "Done";
?>
