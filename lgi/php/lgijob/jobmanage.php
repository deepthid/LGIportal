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
	 * Output from executing the curl
	 * @var string xml
	 */
	private $output;

	/**
	 * This contains the response from server.Initialized by function @see parseResults
	 * @var ServerResponse
	 */
	private $response;

	/**
	 * Contains the Error details occured during execution. Error numbers are described in class Error. 
	 * The error should be set by each operation. Each error will overwrite previous error. 
	 * @var Error
	 */
	private $error; //instance of Error

	/**
	 * Handle for CURL operations
	 * @var unknown_type
	 */
	private $cURLhandle;


	function __construct($keyfile,$certificate,$CA,$user,$groups)
	{
		$this->keyfile=$keyfile;
		$this->certificate=$certificate;
		$this->CA=$CA;
		//$this->server=$server;
		$this->user=$user;
		$this->groups=$groups;
		//$this->project=$project;
		$this->error=new Error();
		$this->targetresources="any";
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
	 * @return Error
	 */
	public function getError()
	{
		return $this->error;

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
		$this->response=new ServerResponse();
		return($this->response->parseFromXml($this->output));
			
	}


	/**
	 * Returns the output as string, without parsing the xml.
	 */
	public function getResponseString()
	{
		
		return $this->output;
	}


	/**
	 * Setup cURL for operations. It sets necessary curl options for contacting a project server. 
	 * @param string $posturl
	 */
	private function setUpcURL($posturl)
	{
		$this->cURLhandle=curl_init();
		curl_setopt($this->cURLhandle,CURLOPT_URL,$this->server.$posturl);
		curl_setopt($this->cURLhandle,CURLOPT_SSLCERT,$this->certificate);
		curl_setopt($this->cURLhandle,CURLOPT_SSLKEY,$this->keyfile);
		curl_setopt($this->cURLhandle,CURLOPT_CAINFO,$this->CA);
		curl_setopt($this->cURLhandle,CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt($this->cURLhandle,CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->cURLhandle,CURLOPT_NOSIGNAL, true );
		curl_setopt($this->cURLhandle,CURLOPT_VERBOSE, false );
		curl_setopt($this->cURLhandle,CURLOPT_NOPROGRESS, true);
			
		
	}

	/**
	 * call back function for writing the response from server 
	 * @param $cURLhandle
	 * @param $responsedata
	 */
	private function responseWrite($cURLhandle,$responsedata)
	{
		$this->output .= $responsedata;
		return strlen($responsedata);
	}

	/**
	 * submit request to server using curl
	 * @param array $postArray array of data to be posted
	 * @return boolean false if curl_exec failed
	 */
	private function cURLpost($postArray)
	{
		$postlist="";
		foreach($postArray as $key=>$value) { $postlist .= $key.'='.urlencode($value).'&'; }
		rtrim($postlist,'&');
		curl_setopt($this->cURLhandle,CURLOPT_POST,count($postArray));
		curl_setopt($this->cURLhandle,CURLOPT_POSTFIELDS,$postlist);

		curl_setopt($this->cURLhandle, CURLOPT_WRITEFUNCTION, array($this, "responseWrite"));

		$result = curl_exec($this->cURLhandle);
		return $result;
		//check what is the result
	}

	/**
	 * set common data to be posted in $postArray
	 * @param array $postArray
	 */
	private function setCommonPostData(&$postArray)
	{
		$postArray["user"]=$this->user;
		$postArray["groups"]=$this->groups;
		if(isset($this->project))
		$postArray["project"]=$this->project;
	}

	/**
	 * Deletes the job with given jobid
	 * @param int $jobid
	 * @return int type of error occured while deleting the job
	 */

	public function deleteJob($jobid)
	{
		$errortype=0;
		$errortype=$this->validateCommonData();
		if($errortype)
		{
			return $errortype;
		}
		if(empty($jobid))
		{
			$this->error->setError(Error::NOJOBID,ErrorType::INPUTERROR);
			return ErrorType::INPUTERROR;
		}
		$postArray=array("job_id" => $jobid);
		$this->setUpcURL("/interfaces/interface_delete_job.php");
		$this->setCommonPostData($postArray);
		$ret=$this->cURLpost($postArray);
		//echo htmlspecialchars($this->output);
		//die();
		if(!$ret)
		{
			$errortype=ErrorType::CURLERROR;
			$this->error->setError(curl_errno($this->cURLhandle),ErrorType::CURLERROR,curl_error($this->cURLhandle));
		}
		else
		{
			$this->parseResults();
			$errorno=$this->response->getErrorNo();
			if(!empty($errorno))  //error in response message
			{
				$this->error->setError(Error::RESPONSE,ErrorType::RESPONSEERROR,$this->response->getErrorMessage());
				return ErrorType::RESPONSEERROR;
			}
		}
		return $errortype;

	}


	public function statusJob($jobid)
	{
		$errortype=0;
		$errortype=$this->validateCommonData();
		if($errortype)
		{
			return $errortype;
		}
		if(empty($jobid))
		{
			$this->error->setError(Error::NOJOBID,ErrorType::INPUTERROR);
			return ErrorType::INPUTERROR;
		}
		$postArray=array("job_id" => $jobid);
		$this->setUpcURL("/interfaces/interface_job_state.php");
		$this->setCommonPostData($postArray);
		$ret=$this->cURLpost($postArray);
		//echo htmlspecialchars($this->output);
		//die();
		if(!$ret)
		{
			$errortype=ErrorType::CURLERROR;
			$this->error->setError(curl_errno($this->cURLhandle),ErrorType::CURLERROR,curl_error($this->cURLhandle));
		}
		else
		{
			$this->parseResults();
			$errorno=$this->response->getErrorNo();
			if(!empty($errorno))  //error in response message
			{
				
				$this->error->setError(Error::RESPONSE,ErrorType::RESPONSEERROR,$this->response->getErrorMessage());
				return ErrorType::RESPONSEERROR;
			}
		}
		return $errortype;

	}

	public function submitJob()
	{
		$errortype=0;
		$errortype=$this->validateCommonData();
		if($errortype)
		{
			return $errortype;
		}
		if(empty($this->application))
		{
			$this->error->setError(Error::NOAPPLICATION,ErrorType::INPUTERROR);
			return ErrorType::INPUTERROR;
		}	
		$postArray=array("application" => $this->application);
		if( isset($this->targetresources))
		{
			$postArray["target_resources"] = $this->targetresources;
		}		
		if( isset($this->jobspecifics))
		{
			$postArray["job_specifics"]= $this->jobspecifics;
		}
		if( isset($this->inputefile))
		{
			//$command=$command." -s ".$;
		}
		if( isset($this->readaccesslist))
		{
			$postArray["read_access"]= $this->readaccesslist;
		}
		if( isset($this->writeaccesslist))
		{
			$postArray["write_access"]= $this->writeaccesslist;
		}		
		$this->setUpcURL("/interfaces/interface_submit_job.php");
		$this->setCommonPostData($postArray);
		$ret=$this->cURLpost($postArray);
		//echo htmlspecialchars($this->output);
		//die();
		if(!$ret)
		{
			$errortype=ErrorType::CURLERROR;
			$this->error->setError(curl_errno($this->cURLhandle),ErrorType::CURLERROR,curl_error($this->cURLhandle));
		}
		else
		{
			$this->parseResults();
			$errorno=$this->response->getErrorNo();
			if(!empty($errorno))  //error in response message
			{
				
				$this->error->setError(Error::RESPONSE,ErrorType::RESPONSEERROR,$this->response->getErrorMessage());
				return ErrorType::RESPONSEERROR;
			}
		}
		return $errortype;
	}

	
public function listJobs()
	{
		$errortype=0;
		$errortype=$this->validateCommonData();
		if($errortype)
		{
			return $errortype;
		}
		
		$this->setUpcURL("/interfaces/interface_job_state.php");
		$this->setCommonPostData($postArray);
		$ret=$this->cURLpost($postArray);
		//echo htmlspecialchars($this->output);
		//die();
		if(!$ret)
		{
			$errortype=ErrorType::CURLERROR;
			$this->error->setError(curl_errno($this->cURLhandle),ErrorType::CURLERROR,curl_error($this->cURLhandle));
		}
		else
		{
			$this->parseResults();
			$errorno=$this->response->getErrorNo();
			if(!empty($errorno))  //error in response message
			{
				$this->error->setError(Error::RESPONSE,ErrorType::RESPONSEERROR,$this->response->getErrorMessage());
				return ErrorType::RESPONSEERROR;
			}
		}
		return $errortype;

	}
	
public function listResources()
	{
		$errortype=0;
		$errortype=$this->validateCommonData();
		if($errortype)
		{
			return $errortype;
		}
		
		$this->setUpcURL("/interfaces/interface_project_resource_list.php");
		$this->setCommonPostData($postArray);
		$ret=$this->cURLpost($postArray);
		//echo htmlspecialchars($this->output);
		//die();
		if(!$ret)
		{
			$errortype=ErrorType::CURLERROR;
			$this->error->setError(curl_errno($this->cURLhandle),ErrorType::CURLERROR,curl_error($this->cURLhandle));
		}
		else
		{
			$this->parseResults();
			$errorno=$this->response->getErrorNo();
			if(!empty($errorno))  //error in response message
			{
				$this->error->setError(Error::RESPONSE,ErrorType::RESPONSEERROR,$this->response->getErrorMessage());
				return ErrorType::RESPONSEERROR;
			}
		}
		return $errortype;

	}
	private function validateCommonData()
	{
		$errortype=0;
		if( !isset($this->keyfile)or empty($this->keyfile))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOKEY;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
		if( !isset($this->certificate) or empty($this->certificate))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOCERT;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
		if( !isset($this->CA) or empty($this->CA))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOCA;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
		if( !isset($this->user)or empty($this->user))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOUSER;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
		if( !isset($this->groups)or empty($this->groups))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOGROUP;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
		if( !isset($this->server)or empty($this->server))
		{
			$errortype=ErrorType::INPUTERROR;;
			$errorno=Error::NOSERVER;
			$this->error->setError($errorno,$errortype);
			return $errortype;
		}
			
		return $errortype;
	}
}


?>