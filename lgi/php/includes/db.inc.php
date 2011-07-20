<?php
/**
 *	This file stores the Database access details. This file should be have restricted access using .htaccess or should be stored outside webroot.The absolute path to this file should be specified in lgi.config.php. 
 */	
	
	/**
	 * string $mysql_server - address of mysql database where user accounts are stored
	 * string $mysql_user - username to access mysql database. This user should have read access to the "Users" table.
	 * string $mysql_password - password of mysql_user
	 * string $mysql_dbname - the name of the database where Users table is stored.
	 */
	$mysql_server='localhost';
	$mysql_user="root";
        $mysql_password="";
        $mysql_dbname="lgi";	

?> 
