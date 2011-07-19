<?php
/**
 * This is the page for deleting a job.
 * @author Deepthi
 */

/**
 *
 */
include 'Dwoo/dwooAutoload.php';
require_once 'utilities/sessions.php';
require_once 'utilities/login_utilities.php';
require_once 'utilities/jobs.php';
require_once 'utilities/data.php';

session_start();
//authenticate User. If user is not logged in, request for log in.
authenticateUser();

//if request does not have details about job, display the form . post variable 'submitrequest' is set in the form.
if(!isset($_POST['submitrequest']))
{
	//display form
	$dwoo = new Dwoo();
	$data= createDwooData();
	$dwoo->output('../dwoo/delete.tpl', $data);
}
else //request for delete job.
{
	$dwoo = new Dwoo();
	$data=createDwooData();
	$output=deleteJob();	
	$data->assign('message',$output);
	$dwoo->output('../dwoo/deletesuccess.tpl', $data);

}

?>