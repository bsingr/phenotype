<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-##!BUILD_YEAR!## Nils Hagemann, Paul Sellinger,
// Peter Sellinger, Michael Krämer.
//
// Open Source since 11/2006, I8ln since 11/2008
// -------------------------------------------------------
// Thanks for your support:
// Markus Griesbach, Alexander Wehrum, Sebastian Heise,
// Dominique Boes, Florian Gehringer, Jens Bissinger
// -------------------------------------------------------
// www.phenotype-cms.com - offical homepage
// www.sellinger-design.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------


/**
 * class PhenotypeLog
 * logs system and application stuff
 * 
 * @package phenotype
 * @subpackage system
 *
 */
class PhenotypeLog {


	// Logs application stuff
	// currently, facility is ignored
	function log($message, $facility=PT_LOGFACILITY_APP, $level=PT_LOGLVL_INFO)
	{
		global $mySUser;
		$userId=0;
		if (is_object($mySUser))
		{
			$userId = (int)$mySUser->id;
		}

		if (PT_LOG_LEVEL >= $level || $level == 1) // log errors always
		{

			if (strlen(PT_LOG_CLIENTINFO_HEADER)) {
				$headers = apache_request_headers();
				$remote_addr = $headers[PT_LOG_CLIENTINFO_HEADER];
			} else {
				$remote_addr = $_SERVER[PT_LOG_CLIENTINFO_SERVER];
			}

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

			if (PT_LOG_METHOD == PT_LOGMTH_FILE OR PT_LOG_METHOD == PT_LOGMTH_FILEANDFIREBUG)
			{
				// log to file

				$logMsg = $remote_addr ."\t". date(PT_LOG_TIMEFORMAT, time()) ."\t$userId\t$facility\t$levelName\t". $message ."\n";

				error_log($logMsg, 3, PT_LOG_LOGFILE);
			}


			if (PT_LOG_METHOD == PT_LOGMTH_FIREBUG OR PT_LOG_METHOD == PT_LOGMTH_FILEANDFIREBUG)
			{

				// log to firebug console

				$cookie = md5("on".PT_SECRETKEY);

				if (PT_DEBUG==1 AND $_COOKIE["pt_debug"]==$cookie)
				{
					require_once(SYSTEMPATH."firephp/FirePHP.class.php");

					$logMsg = date(PT_LOG_TIMEFORMAT, time()) . " ".$facility . " - ". $message;

					$logMsg = "a\tb\n".str_replace('<br/>',"\n",$logMsg);
					$logMsg = str_replace('<br>',"\n",$logMsg);
					$firephp = FirePHP::getInstance(true);

					switch($level)
					{
						case PT_LOGLVL_ERROR:
							$firephp->error($logMsg);
							break;
						case PT_LOGLVL_WARNING:
							$firephp->warn($logMsg);
							break;
						case PT_LOGLVL_INFO:
							$firephp->info($logMsg);
							break;
						case PT_LOGLVL_DEBUG:
							$firephp->log($logMsg);
							break;
					}
				}
			}


		}
	}

}
?>