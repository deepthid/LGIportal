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

	session_start();
	//authenticate User. If user is not logged in, request for log in.
	authenticateUser();
		
	//Display home page
	$dwoo = new Dwoo(); 
	$data=new Dwoo_Data();
	$data->assign('user',$_SESSION['user']);
        $dwoo->output('../dwoo/submit.tpl', new Dwoo_Data());      
	

?>