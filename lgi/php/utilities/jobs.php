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
	
	if($errorset) //if there is error
	{
		$errors=$newjob->getErrors();
		if($errorset==ErrorType::INPUTERROR)
		{
			foreach ($errors as $i => $value) {				
				
				pushErrorMessage(Error::$errormessage[$errors[$i]]);
				
			}	
			//redirect to the form with error message					
			header("Location: submit.php");
		}
		if($errorset==ErrorType::EXECERROR)
		{
			if($newjob->parseResults())
			  pushErrorMessage($newjob->getErrorMessage());
			else
			  pushErrorMessage("Could not submit job. Execution Error :". $newjob->getResults());
			//redirect to the form with error message					
			header("Location: submit.php");
		}
	}
	$newjob->parseResults();
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
		$errors=$newjob->getErrors();
		if($errorset==ErrorType::INPUTERROR)
		{
			foreach ($errors as $i => $value) {				
				
				pushErrorMessage(Error::$errormessage[$errors[$i]]);
				
			}	
			//redirect to the form with error message					
			header("Location: delete.php");
		}
		if($errorset==ErrorType::EXECERROR)
		{
			
			pushErrorMessage("Could not Delete job. Execution Error :". $newjob->getResponseString());
			//redirect to the form with error message					
			header("Location: delete.php");
		}
	}
	$output=$newjob->getResponseString();
	return $output;
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
		$errors=$newjob->getErrors();
		if($errorset==ErrorType::INPUTERROR)
		{
			foreach ($errors as $i => $value) {				
				
				pushErrorMessage(Error::$errormessage[$errors[$i]]);
				
			}	
			//redirect to the form with error message					
			header("Location: viewjob.php");
		}
		if($errorset==ErrorType::EXECERROR)
		{
		        if($newjob->parseResults())
			  pushErrorMessage($newjob->getErrorMessage());
			else
			  pushErrorMessage("Execution Error :". $newjob->getResults());
			//showErrorPage();
			//redirect to the form with error message					
			header("Location: viewjob.php");
		}
	}
	$newjob->parseResults();
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

function listJobs()
{
     global $CA_FILE;
	authenticateUser();
	$user=$_SESSION['user'];
	$cert= getCertificateFile($user);
	$key= getKeyFile($user);
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
		$errors=$newjob->getErrors();
		if($errorset==ErrorType::INPUTERROR)
		{
			foreach ($errors as $i => $value) {				
				
				pushErrorMessage(Error::$errormessage[$errors[$i]]);
				
			}	
			//redirect to the form with error message					
			header("Location: home.php");
		}
		if($errorset==ErrorType::EXECERROR)
		{
		        if($newjob->parseResults())
			  pushErrorMessage($newjob->getErrorMessage());
			else
			  pushErrorMessage("Execution Error :". $newjob->getResults());
			//showErrorPage();
			//redirect to the form with error message					
			header("Location: home.php");
		}
	}
	$newjob->parseResults();
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
?>