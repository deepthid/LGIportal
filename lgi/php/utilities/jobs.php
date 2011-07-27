<?php

/**
 * This file handles job related function such submission and deletion.
 * @package utilities
 * @author Deepthi
 */

/**
 *
 */
require_once 'Dwoo/dwooAutoload.php';
require_once dirname(__FILE__).'/../../lgi.config.php';
require_once dirname(__FILE__).'/../lgijob/jobmanage.php';
require_once dirname(__FILE__).'/../lgijob/errors.php';
require_once dirname(__FILE__).'/../utilities/errors.php';
require_once dirname(__FILE__).'/../utilities/login_utilities.php';
require_once dirname(__FILE__).'/../utilities/data.php';

//require_once dirname(__FILE__).'/../lgijob/curltest.php';

/**
 * Function for submitting job. It takes parameters from POST variables and use the class Job for submitting job.
 * @return string output
 */

function submitJob()
{

	//Set authentication parameters
	global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);
	if(empty($cert) || empty($key))
	{
		pushErrorMessage("You donot have a valid certificate or key. Please contact your system administrator");
		header("Location: submit.php");
		die();
	}
	$group=$user;
	$CA=$CA_FILE;

	//Get inputs from user
	$application=$_POST['application'];
	$server=$_POST['server'];
	$project=$_POST['project'];
	$readaccess=$_POST['readaccess'];
	$writeaccess=$_POST['writeaccess'];
	$target=$_POST['target'];
	$jobspecifics=$_POST['jobspecifics'];

	//Create instance of Job
	$newjob=new Job($key,$cert,$CA,$user,$group);
	$newjob->setApplication($application);
	$newjob->setServer($server);
	$newjob->setProject($project);
	$newjob->setReadAccessList($readaccess);
	$newjob->setWriteAccessList($writeaccess);
	$newjob->setTargetResource($target);
	$newjob->setJobSpecifics($jobspecifics);

	//submit job
	$errorset=$newjob->submitJob();

	if($errorset)
	{
		$error=$newjob->getError();
		handleError($error,"submit.php");
	}
	else
	{
		$result=$newjob->getResults(); // Return an object of serverREsponse
		$jobs=$result->getJobs();
		$job=$jobs[0];
		$output=array();
		$output['jobId']=$job->getJobId();
		$output['jobStatus']=$job->getState();
		$output['application']=$job->getapplication();
		$output['target']=$job->getTargetResources();
		$output['jobOwner']= $job->getOwners();
		$output['readAccess']= $job->getReadAccess();
		return $output;
	}
}

/**
 * Function for deleting job. It takes parameters from POST variables and use the class Job for submitting job.
 * @return string output
 */

function deleteJob()
{
	//session_start();
	global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);
	if(empty($cert) || empty($key))
	{
		pushErrorMessage("You donot have a valid certificate or key. Please contact your system administrator");
		header("Location: delete.php");
		die();
	}
	$group=$user;
	$CA=$CA_FILE;


	$server=$_POST['server'];
	$project=$_POST['project'];
	$jobid=$_POST['jobid'];


	$newjob=new Job($key,$cert,$CA,$user,$group);

	$newjob->setServer($server);
	$newjob->setProject($project);

	//delete job

	$errorset=$newjob->deleteJob($jobid);

	if($errorset)
	{
		$error=$newjob->getError();
		handleError($error,"delete.php");
	}
	else
	{
		$output=$newjob->getResults();
		$jobs=$output->getJobs();
		$job=$jobs[0];
		return "Job ".$job->getJobId()." is ".$job->getState()." from server ".$output->getServer();
	}
}

/**
 * Function for viewing status of job. It takes parameters from POST variables and use the class Job for submitting job.
 * @return array output
 */
function viewJob()
{
	//session_start();
	global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);
	if(empty($cert) || empty($key))
	{
		pushErrorMessage("You donot have a valid certificate or key. Please contact your system administrator");
		header("Location: viewjob.php");
		die();
	}
	$group=$user;
	$CA=$CA_FILE;


	$server=$_POST['server'];
	$project=$_POST['project'];
	$jobid=$_POST['jobid'];


	$newjob=new Job($key,$cert,$CA,$user,$group);

	$newjob->setServer($server);
	$newjob->setProject($project);

	//delete job

	$errorset=$newjob->statusJob($jobid);


	if($errorset)
	{
		$error=$newjob->getError();
		handleError($error,"viewjob.php");
	}
	else
	{
		$result=$newjob->getResults(); // Return an object of serverREsponse
		$jobs=$result->getJobs();
		$job=$jobs[0];
		$output=array();

		//Add here to give more details of jobs.
		$output['jobId']=$job->getJobId();
		$output['jobStatus']=$job->getState();
		$output['application']=$job->getapplication();
		$output['target']=$job->getTargetResources();
		$output['jobOwner']= $job->getOwners();
		$output['readAccess']= $job->getReadAccess();
		return $output;

	}
}

function listJobs()
{
	global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);

	if(empty($cert) || empty($key))
	{
		pushErrorMessage("You donot have a valid certificate or key. Please contact your system administrator");
		header("Location: listjobs.php");
		die();
	}
	$group=$user;
	$CA=$CA_FILE;


	$server=$_POST['server'];
	//$project=$_POST['project'];
	//$jobid=$_POST['jobid'];


	$newjob=new Job($key,$cert,$CA,$user,$group);

	$newjob->setServer($server);
	//$newjob->setProject($project);

	//delete job

	$errorset=$newjob->listJobs();

	if($errorset)
	{
		$error=$newjob->getError();
		handleError($error,"listjobs.php");
	}
	else
	{

		$result=$newjob->getResults(); // Return an object of serverREsponse
		$jobs=$result->getJobs();
		$output=array();
		$j=0;
		foreach($jobs as $i=>$value)
		{
			$output[$j]['jobId']=$jobs[$i]->getJobId();
			$output[$j]['jobStatus']=$jobs[$i]->getState();
			$output[$j]['application']=$jobs[$i]->getapplication();
			$output[$j]['target']=$jobs[$i]->getTargetResources();
			$output[$j]['jobOwner']= $jobs[$i]->getOwners();
			$output[$j]['readAccess']= $jobs[$i]->getReadAccess();
			$j=$j+1;
		}
		return $output;

	}

}

function listResources()
{
	global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);

	if(empty($cert) || empty($key))
	{
		pushErrorMessage("You donot have a valid certificate or key. Please contact your system administrator");
		header("Location: listjobs.php");
		die();
	}
	$group=$user;
	$CA=$CA_FILE;


	$server=$_POST['server'];
	//$project=$_POST['project'];
	//$jobid=$_POST['jobid'];

	$newjob=new Job($key,$cert,$CA,$user,$group);

	$newjob->setServer($server);
	//$newjob->setProject($project);

	//delete job

	$errorset=$newjob->listResources();

	if($errorset)
	{
		$error=$newjob->getError();
		handleError($error,"listresources.php");
	}
	else
	{
		$result=$newjob->getResults(); // Return an object of serverREsponse
		$resources=$result->getResources();
		$output=array();
		$j=0;
		foreach($resources as $i=>$value)
		{
			$output[$j]['name']=$resources[$i]->getResourceName();
			$output[$j]['capabilities']=$resources[$i]->getCapabilities();
			$output[$j]['lastcalltime']=$resources[$i]->getLastCallTime();
			$j=$j+1;
		}
		return $output;
	}


}



function handleError($error,$redirect)
{

	$errormessage=$error->getErrorString();
	pushErrorMessage($errormessage);
	header("Location: ".$redirect);
}
?>