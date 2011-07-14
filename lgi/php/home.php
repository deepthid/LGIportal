<?php 

/**
 * This is the page a user sees after logging in.
 * @author Deepthi 
 */
 
 /**
  *
  */
include 'Dwoo/dwooAutoload.php';
include 'utilities/sessions.php';
include 'utilities/login_utilities.php';
require_once 'utilities/data.php';

	session_start();
	//authenticate User. If user is not logged in, request for log in.
	authenticateUser();
		
	//Display home page
	$dwoo = new Dwoo(); 
	$data=createDwooData();	
    $dwoo->output('../dwoo/home.tpl', $data);      
	

?>