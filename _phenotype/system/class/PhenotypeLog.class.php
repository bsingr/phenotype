<?php
/*
 * class PhenotypeLog
 * logs system and application stuff
 *
 */
class PhenotypeLog {


	// Logs application stuff
	// currently, facility is ignored
	function log($message, $facility=PT_LOGFACILITY_APP, $level=PT_LOGLVL_INFO) {
		global $mySUser;

	
		if (PT_LOG_LEVEL >= $level || $level == 1) // log errors always
		{		
			if (PT_LOG_METHOD == PT_LOGMTH_FILE) {
				// log to file
				
				if (strlen(PT_LOG_CLIENTINFO_HEADER)) {
					$headers = apache_request_headers();
					$remote_addr = $headers[PT_LOG_CLIENTINFO_HEADER];
				} else {
					$remote_addr = $_SERVER[PT_LOG_CLIENTINFO_SERVER];
				}
				$userId = (int)$mySUser->id;
				switch($level)
				{
					case PT_LOGLVL_ERROR:
						$levelName = "ERROR";
						break;
					case PT_LOGLVL_WARNING:
						$levelName = "WARNING";
						break;
					case PT_LOGLVL_INFO:
						$levelName = "INFO";
						break;
					case PT_LOGLVL_DEBUG:
						$levelName = "DEBUG";
						break;
				}
				if (!$facility) 
				{
					$facility=PT_LOGFACILITY_APP;
				}
				
				$logMsg = $remote_addr ."\t". date(PT_LOG_TIMEFORMAT, time()) ."\t$userId\t$facility\t$levelName\t". $message ."\n";
				
				return error_log($logMsg, 3, PT_LOG_LOGFILE);
			}
		}
	}
	
}
?>