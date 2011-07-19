<?php

     class Error
     {
          const NOAPPLICATION=0;
          const NOSERVER   =1;
          const NOKEY=2;
          const NOCERT=3;
          const NOCA=4;
          const NOUSER=5;
          const NOGROUP=6;
          
          static $errormessage=array(
               Error::NOAPPLICATION => "No Application specified",
               Error::NOSERVER => "No Project server specified",
               Error::NOKEY => "No user key specified",
               Error::NOCERT => "No user certificate specified",
               Error::NOCA => "No CA certificate specified",
               Error::NOUSER => "No User specified",
               Error::NOGROUP => "No group specified"
          );
          
     
     }
     
     class ErrorType
     {
          const NOERROR=0;
          const INPUTERROR=1;
          const EXECERROR=2;
     
     }

?>