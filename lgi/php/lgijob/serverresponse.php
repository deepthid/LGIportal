<?php

/**
 *
 * @author deepthi
 * @package lgijob
 */

/**
 *
 */

require_once dirname(__FILE__).'/errors.php';;
require_once "XML/Unserializer.php";
require_once "XML/Serializer.php";

class ServerResponse
{
	/**
	 * The name of project server to which job was submitted
	 * @var string
	 */
	private $server;
	/**
	 * username of user
	 * @var string
	 */
	private $user;
	/**
	 * Group of user
	 * @var string
	 */
	private $groups;
	/**
	 * The project to which job was submitted
	 * @var string
	 */
	private $project;
	/**
	 * An array of jobDetails returnes by server. @see JobDetails
	 * @var array JobDetails
	 */
	private $jobs;
	/**
	 * Number of jobs in server response
	 * @var integer
	 */
	private $noofjobs;

	private $noofresources;
	private $resources;
	/**
	 * The error number in server response. It will not be initialized if there is no error.
	 * @var string
	 */
	private $errorno;
	/**
	 * The error message in server response. It will not be initializes if there is no error.
	 * @var unknown_type
	 */
	private $errormessage;

	public function getServer()
	{
		return $this->server;
	}
	public function getUser()
	{

		return $this->user;
	}
	public function getGroups()
	{
		return $this->groups;
	}
	public function getProject()
	{
		return $this->project;
	}
	public function getJobs()
	{
		return $this->jobs;
	}
	public function getNoOfJobs()
	{
		return $this->noofjobs;
	}
	public function getResources()
	{
		return $this->resources;
	}
	public function getNoOfResources()
	{
		return $this->noofresources;
	}
	public function getErrorNo()
	{

		return $this->errorno;
	}
	public function getErrorMessage()
	{
		return $this->errormessage;
	}

	/**
	 * This function initializes this object by parsing the xml string returned from server. Returns true if $xml could be parsed successfully. Otherwise returns false.
	 * @return boolean
	 * @param string $xml
	 */
	public function parseFromXml($xml)
	{
		$us = new XML_Unserializer();
		$result;
		if($us->unserialize($xml))	//if xml could be unserialized.
		{
			$result=$us->getUnserializedData ( );


			if(isset($result['error']))	//if there is error
			{
				$this->errorno=$result['error']['errorno'];
				$this->errormessage=$result['error']['message'];

			}

			else	//if there is no error
			{
				$this->project=$result['project'];
				$this->server=$result['project_master_server'];
				$this->user=$result['user'];
				$this->groups=$result['groups'];
				$this->noofjobs=intval($result['number_of_jobs']);
				$this->noofresources=intval($result['number_of_resources']);


				if($this->noofjobs==1 || $this->noofjobs==0) //if there is only one job in the response
				{
					/*
					 * if no.of jobs is 0, either the response is for LGI_qsub.
					 * Then it contains one job details.
					 * Otherwise it can be response for LGI_qstat for resources. Hence check whether job is set.
					 */
					if(isset($result['job']))
					{
						$job=$result['job'];
						$jobid=$job['job_id'];
						$target=$job['target_resources'];
						$owners=$job['owners'];
						$readaccess=$job['read_access'];
						$writeaccess=$job['write_access'];
						$application=$job['application'];
						$state=$job['state'];
						$statetimestamp=$job['state_time_stamp'];
						$jobspecifics=$job['job_specifics'];
						$input=$job['input'];
						$output=$job['output'];
							

						$jobdetails=new JobDetails($jobid,$application,$owners,$state,$statetimestamp,$target,$jobspecifics,$readaccess,$writeaccess,$input,$output);
						$this->jobs[0]=$jobdetails;
					}
				}
				else		//if there are more jobs, then result contains an array of jobs.
				{
					$jobs=$result['job'];

					$j=0;
					foreach ($jobs as $i => $value) {
						$jobid=$jobs[$i]['job_id'];
						$target=$jobs[$i]['target_resources'];
						$owners=$jobs[$i]['owners'];
						$readaccess=$jobs[$i]['read_access'];
						$writeaccess=$jobs[$i]['write_access'];
						$application=$jobs[$i]['application'];
						$state=$jobs[$i]['state'];
						$statetimestamp=$jobs[$i]['state_time_stamp'];
						$jobspecifics=$jobs[$i]['job_specifics'];
						$input=$jobs[$i]['input'];
						$output=$jobs[$i]['output'];

						$job=new JobDetails($jobid,$application,$owners,$state,$statetimestamp,$target,$jobspecifics,$readaccess,$writeaccess,$input,$output);
						$this->jobs[$j]=$job;
						$j=$j+1;
					}

				}

				if($this->noofresources==1 ) //if there is only one resource in the response
				{
					if(isset($result['resource']))
					{
						$resource=$result['resource'];
						$resourcename=$resource['resource_name'];
						$ser=new XML_Serializer();
						$ser->serialize($resource['resource_capabilities']);
						$capabilities=htmlspecialchars($ser->getSerializedData());

						$lastcalltime=$resource['last_call_time'];

						$newresource=new Resource($resourcename,$capabilities,$lastcalltime);
						$this->resources[0]=$newresource;
					}
				}
				else		//if there are more resources, then result contains an array of jobs.
				{
					$resourcelists=$result['resource'];

					$j=0;
					foreach ($resourcelists as $i => $value) {
						$resource=$result['resource'];
						$resourcename=$resource['resource_name'];
						$ser=new XML_Serializer();
						$ser->serialize($resource['resource_capabilities']);
						$capabilities=$ser->getSerializedData();

						$lastcalltime=$resource['last_call_time'];

						$newresource=new Resource($resourcename,$capabilities,$lastcalltime);
						$this->resources[j]=$newresource;
						$j=$j+1;
					}

				}
			}

			return true;
		}
		else
		return false;

	}

}

/**
 * This class is for job details returned by server.
 * @author deepthi
 * @todo Add setter functions
 *
 */
class JobDetails
{

	private $application;
	private $targetresources;
	private $jobspecifics;
	//private $inputfile;
	private $readaccesslist;
	private $writeaccesslist;
	private $job_id;
	private $owners;
	private $state;
	private $statetimestamp;
	private $input;
	private $output;

	function __construct($jobid,$application,$owners,$state,$statetimestamp,$targetresources,$jobspecifics,$readaccesslist,$writeaccesslist,$input,$output)
	{

		$this->jobid=$jobid;
		$this->application=$application;
		$this->owners=$owners;
		$this->state=$state;
		$this->statetimestamp=$statetimestamp;
		$this->targetresources=$targetresources;
		$this->jobspecifics=$jobspecifics;
		$this->readaccesslist=$readaccesslist;
		$this->writeaccesslist=$writeaccesslist;
		$this->input=$input;
		$this->output=$output;
	}

	public function getJobId()
	{
		return $this->jobid;
	}
	public function getApplication()
	{
		return $this->application;
	}

	public function getTargetResources()
	{
		return $this->targetresources;
	}

	public function getJobSpecifics()
	{
		return $this->jobspecifics;
	}
	public function getReadAccess()
	{
		return $this->readaccesslist;
	}
	public function getWriteAccess()
	{
		return $this->writeaccesslist;
	}

	public function getOwners()
	{
		return $this->owners;
	}
	public function getState()
	{
		return $this->state;
	}
	public function getTimeStamp()
	{
		return $this->statetimestamp;
	}
	public function getInput()
	{
		return $this->input;
	}
	public function getOutput()
	{
		return $this->output;
	}
}

class Resource
{
	private $name;
	private $capabilities;
	private $lastcalltime;

	function __construct($resourcename,$capabilities,$lastcalltime)
	{
		$this->name=$resourcename;
		$this->capabilities=$capabilities;
		$this->lastcalltime=$lastcalltime;
	}

	public function getResourceName()
	{
		return $this->name;
	}
	public function getCapabilities()
	{
		return $this->capabilities;
	}
	public function getLastCallTime()
	{
		return $this->lastcalltime;
	}
}
?>