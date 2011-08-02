<?php

/**
 * This class for representing error in lgijob
 * @author deepthi
 * @package lgijob
 *
 */
class Error
{
	const NOAPPLICATION=0;
	const NOSERVER   =1;
	const NOKEY=2;
	const NOCERT=3;
	const NOCA=4;
	const NOUSER=5;
	const NOGROUP=6;
	const NOJOBID=7;
	const RESPONSE=20;

	static $errormessage=array(
	Error::NOAPPLICATION => "No Application specified",
	Error::NOSERVER => "No Project server specified",
	Error::NOKEY => "No user key specified",
	Error::NOCERT => "No user certificate specified",
	Error::NOCA => "No CA certificate specified",
	Error::NOUSER => "No User specified",
	Error::NOGROUP => "No group specified",
	Error::NOJOBID => "No Job specified"
              
	);

	private $lasterrorno;
	private $lasterrortype=ErrorType::NOERROR;
	private $lasterrormessage;
	 
	/**
	 * set error number,error type and error message of this instance. 
	 * It overwrites the previous error.
	 * @param $errorno
	 * @param $errortype
	 * @param $message
	 */
	function setError($errorno,$errortype,$message="No Error")
	{
		$this->lasterrorno=$errorno;
		$this->lasterrortype=$errortype;
		$this->lasterrormessage=$message;
	}

	function getErrorType()
	{
		return $this->lasterrortype;
	}

	function getErrorNo()
	{
		return $this->lasterrorno;
	}

	/**
	 * Clear errors recorded.
	 */
	function initError()
	{
		$this->lasterrortype=ErrorType::NOERROR;
		$this->lasterrorno=0;
		$this->lasterrormessage="";
	}

	/**
	 * Returns the error message as string
	 * @return string
	 */
	function getErrorString()
	{
		switch($this->lasterrortype)
		{
			case ErrorType::NOERROR:
				return NULL;
				break;
			case ErrorType::INPUTERROR:
				return Error::$errormessage[$this->lasterrorno];
				break;
			case ErrorType::CURLERROR:
				return $this->lasterrormessage;
				break;
			case ErrorType::RESPONSEERROR:
				return $this->lasterrormessage;
				break;
			case ErrorType::EXECERROR:
				return $this->lasterrormessage;
				break;
			default:
				return NULL;
				break;

		}
		 
	}

}
 
class ErrorType
{
	const NOERROR=0;
	const INPUTERROR=1;
	const EXECERROR=2;
	const RESPONSEERROR=3;
	const CURLERROR=4;
	 
}

?>