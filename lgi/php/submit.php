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
	//To prevent cross site request forgery
	$nonce=uniqid(rand(), true);
	$data->assign("nonce",$nonce);
	$_SESSION["submitnonce"]=$nonce;
	$dwoo->output('../dwoo/submit.tpl', $data);
}
else //request for submit job.
{
	$dwoo = new Dwoo();
	$data=createDwooData();

	//To prever cross site request forgery
	$nonce=$_POST["nonce"];
	if(strcmp($_SESSION["submitnonce"],$nonce)==0)
	{
		unset($_SESSION["submitnonce"]);
		$output=submitJob();
			
		//To display details, pass it to the dwoo template
		$data->assign('jobId',$output['jobId']);
		$data->assign('jobStatus',$output['jobStatus']);
		$data->assign('application',$output['application']);
		$data->assign('target',$output['target']);
		$data->assign('jobOwner',$output['jobOwner']);
		$data->assign('readAccess',$output['readAccess']);

		$dwoo->output('../dwoo/submitsuccess.tpl', $data);
	}
	else
	{
		header("Location: submit.php");
	}

}


?>