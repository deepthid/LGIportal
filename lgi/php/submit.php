<?php

/**
 * This is the page for submitting a job.
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

if(!isset($_POST['submitrequest']))
{
	//display form
	$dwoo = new Dwoo();
	$data= createDwooData();
	$dwoo->output('../dwoo/submit.tpl', $data);
}
else //request for submit job.
{
	$dwoo = new Dwoo();
	$data=createDwooData();
	$output=submitJob();
		
    $data->assign('jobId',$output['jobId']);
	$data->assign('jobStatus',$output['jobStatus']);
	$data->assign('application',$output['application']);
	$data->assign('target',$output['target']);
	$data->assign('jobOwner',$output['jobOwner']);
	$data->assign('readAccess',$output['readAccess']);
	
	//$data->assign('message',$output);
	
	$dwoo->output('../dwoo/submitsuccess.tpl', $data);

}


?>