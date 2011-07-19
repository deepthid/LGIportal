<?php 

/**
 * This files handles common data shared between pages.
 * @package utilities
 * @author Deepthi
 */

require_once 'Dwoo/dwooAutoload.php';
require_once 'errors.php'; 

/**
 * create a dwoo data with some common data to be used in all pages.
 * This function should be called instead of standard Dwoo_Data().
 * @return Dwoo_Data
 */
function createDwooData()
{
	
	session_start();
	$data=new Dwoo_Data(); 
	
	//Add name of user
	if(isset($_SESSION['user']))
		$data->assign('user',$_SESSION['user']);
	
	//Add error message if there is any
	$data->assign('errormessage',getErrorMessage());
	clearErrorMessage();	//error message has to be cleared, otherwise it will be shown in firther pages	
		
	//Add more data here
	
	
	return $data;
}
?>