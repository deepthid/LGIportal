<?php

/**
 * A class for job. It has function for job handling such as submit job, delete job.
 * @author deepthi
 * @package lgijob
 */

/**
 *
 */

require_once dirname(__FILE__).'/errors.php';;
require_once "XML/Unserializer.php";
require_once dirname(__FILE__)."/serverresponse.php";

/**
 * A class for handling job related function such as submitting job, deleteing job etc.
 * @author deepthi
 * TODO: parse xml results
 */
class Job
{
	private $application;
	private $targetresources;
	private $jobspecifics;
	private $inputefile;
	private $readaccesslist;
	private $writeaccesslist;
	private $keyfile;
	private $certificate;
	private $CA;
	private $server;
	private $user;
	private $groups;
	private $project;
	private $job_id;

	/**
	 * Output from executing the command
	 * @var array
	 */
	private $output;
	
	/**
	 * Contains the error numbers of errors occured during execution. Error numbers are described in class Error
	 * @var array
	 */
	private $errorno;
	private $errortype;	
	
	/**
	 * This contains the response from server.Initialized by function @see parseResults
	 * @var ServerResponse
	 */
	private $response;


	function __construct($keyfile,$certificate,$CA,$user,$groups)
	{
		$this->keyfile=$keyfile;
		$this->certificate=$certificate;
		$this->CA=$CA;
		//$this->server=$server;
		$this->user=$user;
		$this->groups=$groups;
		//$this->project=$project;
	}

	public function setGroups($groups)
	{
		if(!empty($groups))
		$this->groups=$groups;
	}
	public function setUser($user)
	{
		if(!empty($user))
		$this->user=$user;
	}
	public function setServer($server)
	{
		if(!empty($server))
		$this->server=$server;
	}

	public function setCA($CA)
	{
		if(!empty($CA))
		$this->CA=$CA;
	}

	public function setCertificate($certificate)
	{
		if(!empty($certificate))
		$this->certificate=$certificate;
	}

	public function setKeyfile($keyfile)
	{
		if(!empty($keyfile))
		$this->keyfile=$keyfile;
	}

	public function setApplication($application)
	{
		if(!empty($application))
		$this->application=$application;
	}
	public function setTargetResource($target)
	{
		if(!empty($target))
		$this->targetResources=$target;
	}

	public function setJobSpecifics($specifics)
	{
		if(!empty($specifics))
		$this->jobspecifics=$specifics;
	}

	public function setReadAccessList($readaccess)
	{
		if(!empty($readaccess))
		$this->readaccesslist=$readaccess;
	}

	public function setWriteAccessList($writeaccess)
	{
		if(!empty($writeaccess))
		$this->writeaccesslist=$writeaccess;
	}

	public function setProject($project)
	{
		if(!empty($project))
		$this->project=$project;
	}

	/**
	 * Get errors occured in last operation.
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errorno;

	}

	/**
	 *	Get results of the last operation
	 *	@return ServerResponse
	 */
	public function getResults()
	{

		return $this->response;
	}

	/**
	 * This function parses the xml output from server and sets the results of this object.
	 * @return boolean
	 */
	public function parseResults()
	{
		$out="<LGI>";
		foreach ($this->output as $i => $value) {
			$out=$out.$this->output[$i];
		}
		$out=$out."</LGI>";
		$this->response=new ServerResponse();
		return($this->response->parseFromXml($out));		
		 
	}
	
	
	/**
	 * Returns the output as string, without parsing the xml.
	 */
	public function getResponseString()
	{
		$out="";
	foreach ($this->output as $i => $value) {
			$out=$out.$this->output[$i];
		}
		return $out;
	}
	/*public function getJobId()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['job_id'];
	}
	public function getJobStatus()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['state'];
	}
	public function getJobName()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['application'];
	}
	public function getJobTarget()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['target_resources'];
	}
	public function getJobOwner()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['owners'];
	}
	public function getJobReadAccess()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['job']['read_access'];
	}
	public function getErrorMessage()
	{
		if(!isset($this->parsedResults))
		return NULL;
		return $this->parsedResults['error']['message'];
	}*/
	
	/**
	 * Submit job to the server specified
	 * @return int - type of error occured. 0 means no error occured while submitting the job
	 */
	public function submitJob()
	{
		$errortype=0;
		//check whether required inputs are there
		$errortype=$this->validateInputsForSubmit($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
			
		//check whether all credentials are set
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}

		//generate the command to execute
			
		$command="/usr/bin/LGI_qsub -x ";
			
		if(isset($this->application))
		{
			// echo "*".$this->application."*";
			$command=$command." -a ".escapeshellcmd($this->application);
		}
		if( isset($this->targetresources))
		{
			$command=$command." -t ".escapeshellcmd($this->targetresources);
		}
		if( isset($this->jobspecifics))
		{
			$command=$command." -s ".escapeshellcmd($this->jobspecifics);
		}
		if( isset($this->inputefile))
		{
			//$command=$command." -s ".$;
		}
		if( isset($this->readaccesslist))
		{
			$command=$command." -r ".escapeshellcmd($this->readaccesslist);
		}
		if( isset($this->writeaccesslist))
		{
			$command=$command." -w ".escapeshellcmd($this->writeaccesslist);
		}
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
			
		// execute the command
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			//if it returns a non zero value, there was some execution error.
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;

	}


	/**
	 * Deletes the job with given jobid
	 * @param int $jobid
	 * @return int type of error occured while deleting the job
	 */

	public function deleteJob($jobid)
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qdel ";
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
		$command=$command." ".escapeshellcmd($jobid);
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;
	}

	/**
	 * Get details of the job with given jobid
	 * @param int $jobid
	 * @return int type of error occured, 0 if there is no error
	 */
	public function statusJob($jobid)
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qstat -x";
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
		$command=$command." ".escapeshellcmd($jobid);
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ");
		}
		return $errortype;
	}

	public function statusApplication($application)
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qstat ";

		//TODO : Check whether application is specified, if not raise an error
		if( isset($this->application))
		{
			$command=$command." -a ".escapeshellcmd($this->application);
		}
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
		//$command=$command." ".escapeshellcmd($jobid);
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;
	}

	public function listJobs()
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qstat -x";

		//TODO : Check whether application is specified, if not raise an error
		if( isset($this->application))
		{
			$command=$command." -a ".escapeshellcmd($this->application);
		}
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
		//$command=$command." ".escapeshellcmd($jobid);
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;
	}

	public function listServers()
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qstat -l";

		//TODO : Check whether application is specified, if not raise an error
		if( isset($this->application))
		{
			$command=$command." -a ".escapeshellcmd($this->application);
		}
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		if( isset($this->project))
		{
			$command=$command." -P ".escapeshellcmd($this->project);
		}
		//$command=$command." ".escapeshellcmd($jobid);
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;
	}

	public function listResources()
	{
		$errortype=0;
		$errortype=$this->validateCredentials($this->errorno);
		if($errortype)
		{
			//$this=>errortype=ErrorType::INPUTERROR;
			return $errortype;
		}
		$command="/usr/bin/LGI_qstat -x -L";

		
		if( isset($this->keyfile))
		{
			$command=$command." -K ".escapeshellcmd($this->keyfile);
		}
		if( isset($this->certificate))
		{
			$command=$command." -C ".escapeshellcmd($this->certificate);
		}
		if( isset($this->CA))
		{
			$command=$command." -CA ".escapeshellcmd($this->CA);
		}
		if( isset($this->server))
		{
			$command=$command." -S ".escapeshellcmd($this->server);
		}
		if( isset($this->user))
		{
			$command=$command." -U ".escapeshellcmd($this->user);
		}
		if( isset($this->groups))
		{
			$command=$command." -G ".escapeshellcmd($this->groups);
		}
		
		$res=exec($command,$this->output,$ret);
		if($ret!=0)
		{
			$errortype=ErrorType::EXECERROR;
			error_log("LGI_Job Error: Exec '".$command."' failed. Return code: ".$ret);
		}
		return $errortype;
	}


	private function validateInputsForSubmit(&$errorno)
	{
		$errortype=0;
		if(!isset($this->application) or empty($this->application))
		{
			$errortype=ErrorType::INPUTERROR;
			$errorno[]=Error::NOAPPLICATION;
		}
		if( !isset($this->server) or empty($this->server))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOSERVER;
		}
		return $errortype;
	}

	private function validateCredentials(&$errorno)
	{
		$errortype=0;
		if( !isset($this->keyfile)or empty($this->keyfile))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOKEY;
		}
		if( !isset($this->certificate) or empty($this->certificate))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOCERT;
		}
		if( !isset($this->CA) or empty($this->CA))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOCA;
		}
		if( !isset($this->user)or empty($this->user))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOUSER;
		}
		if( !isset($this->groups)or empty($this->groups))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno[]=Error::NOGROUP;
		}
			
		return $errortype;
	}
}


?>