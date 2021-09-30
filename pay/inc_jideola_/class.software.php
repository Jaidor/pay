<?php
class softwareFunctions{
   
    public $dbx; /* Database connection */
	public $loggedIn = false; /* Login status of user */
	public $user = array(); /* Hold user info */
	public $ip = 0; /* User ip address */
	public $browser; /* Browser */
	public $DB_HOST; /* Database host e.g localhost */
	public $DB_NAME; /* Database name */
	public $DB_USER; /* Database username */
	public $DB_PASS; /* Database password */
	public $DB_PREFIX; /* Database table prefix */
	public $DB_TYPE;
	public $debug = false;

	private $method;
	private $call;
	private $scope;
	private $version;
	private $header;
    private $token;

    private $_privKey; /* Private key */
    private $_pubKey; /* Public key */
    private $_keyPath; /* Save file address */

    /** Public key
    * @var string
    */
    private $_pubKeyLink = "-----BEGIN PUBLIC KEY-----
								MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCF4sz1eu4XgLeIK9Aiu4+rfglt
								k1gmNhUytOtk3kbzPoy2XoR5sQIRXBYnIagwBVOLPWDacVJoqjfeK6xGvL17745u
								Z7RubcZIW62ocgX3swIDAQAB
								-----END PUBLIC KEY-----";
	/** Private Key
	* @var string
	*/
	private $_priKeyLink = "-----BEGIN RSA PRIVATE KEY-----
								MIICXAIBAAKBgQCF4sz1eu4XgLeIK9Aiu4+rfgltk1gmNhUytOtk3kbzPoy2XoR5
								sQIRXBYnIagwBVOLPWDacVJoqjfeK6xGvL17745uwNSw3eKLl1qm+w2z5KhNEnpg
								LWxKxSPMfekt1Aj3Te0Ct652Scr42Coca/ld2mGkZ7RubcZIW62ocgX3swIDAQAB
								AoGAHinbvU6Fx5vDPZWJXdnd42gQ3bP9fxZeLj9ebSo61+B2uTuQIw6DBcA2aXiG
								uNLqYItif7RaOaRn09EJDiLFmYwRBXAGnEdSnxWRy/IMrtKATV+dLnyFDVrIzsn+
								/9l3HQXKhlSqTc4v7o1sWAM9GW2vjB3X432BjzbgqCyplOECQQC7UnvQUZYT+sum
								PStREJt85krUKgeFwyQdji+BdAXhv9xz3PiSWsAvw87zFrpBKcWbTimSH38onKGa
								htuYE08xAkEAtvjx7t05TiVusPcsgABxoABKRKZpcY5QQIXTT3oigvCMuz41nBDm
								EXeot+TXBGwG0QNS7p5BwkrXfCFJJONkIwJAUbcItfZxPqQAJLO4arOQ8KpRaD4x
								a+OVpKL7DEC9tB4LICv773RRNET5yUdX1sdPIZG2Rr0grmmtgYhk0PFTcQJBAI8I
								uv2VL3fMBI4SGWWN/LPSeZkUdPbh0GmRCSo4nPOfxK8=
								-----END RSA PRIVATE KEY-----";							
	

   function __construct($param = array())
   {

    /* Db config */
		if(count($param)){
			$this->DB_HOST = $param['DB_HOST'];
            $this->DB_NAME = $param['DB_NAME'];
            $this->DB_USER = $param['DB_USER'];
            $this->DB_PASS = $param['DB_PASS'];
            $this->DB_PREFIX = $param['DB_PREFIX'];	
            $this->DB_TYPE = $param['DB_TYPE'];
		}

		$this->ip = $this->getIpAddress();
		$this->browser = $this->getBrowser();

       /* Set the php time zone to africa lagos */
       date_default_timezone_set('Africa/Lagos');
       $siteip = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$siteip .= "s";}
		$siteip .= "://";
		if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
			$siteip .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/';
		} else {
			$siteip .= $_SERVER["SERVER_NAME"].'/';
		}
    }

	public function setMethod($data)
    {
        $this->method = $data;
    }

	public function getMethod()
    {
        return $this->method;
    }

	public function setCall($data)
    {
        $this->call = $data;
    }

	public function getCall()
    {
        return $this->call;
    }

	public function setScope($data)
    {
        $this->scope = $data;
    }

	public function getScope()
    {
        return $this->scope;
    }

	public function setVersion($data)
    {
        $this->version = $data;
    }

	public function getVersion()
    {
        return $this->version;
    }

	public function setHeader($data=[])
    {
        $this->header = $data;
    }

	public function getHeader()
    {
        return $this->header;
    }

	public function setToken($data)
    {
        $this->token = $data;
    }

    public function getToken()
    {
        return $this->token;
    }

	public function setUser($data)
    {
        $this->user = $data;
    }

    public function getUser()
    {
        return $this->user;
    }

	function destroy($token)
	{
       /* Session destroy */
        if($token){
            $query = "delete from #__sessions where session_id = '$token' ";
            $this->dbquery($query);
        }
    }


    public function antiHacking($string, $length = null, $html = false, $striptags = true)
	{
		
		$length = 0 + $length;

		if(!$html) return ($length > 0) ? substr(addslashes(trim(preg_replace('/<[^>]*>/', '', $string))),0,$length) : addslashes(trim(preg_replace('/<[^>]*>/', '', $string)));
		$allow  = "<b><h1><h2><h3><h4><h5><h6><br><br /><hr><hr /><em><strong><a><ul><ol><li><dl><dt><dd><table><tr><th><td><blockquote><address><div><p><span><i><u><s><sup><sub><style><tbody>";
		$string = utf8_decode(trim($string)); /* Avoid unicode codec issues */
		if($striptags) $string = strip_tags($string, $allow);
		
		$aDisabledAttributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$string = str_ireplace($aDisabledAttributes,'x',$string);
		
        /* Remove javascript from tagsv */
		while( preg_match("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $string))
		$string = preg_replace("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $string);
			
		/* Dump expressions from contibuted contentv */
		$string = preg_replace("/:expression\(.*?((?>[^(.*?)]+)|(?R)).*?\)\)/i", "", $string);

		while( preg_match("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $string))
		$string = preg_replace("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $string);
		
		/* Convert HTML characters */
		$string = str_replace("#", "#", htmlentities($string));
		$string = addslashes(str_replace("%", "%", $string));

		if($length > 0) $string = substr($string, 0, $length);
		return $string;
	}

    public function array2string ($multidimensional_array)
    {
        return base64_encode(serialize($multidimensional_array));
    }
	
    public function string2array ($encoded_serialized_string)
    {
        return unserialize(base64_decode($encoded_serialized_string));
    }

	// /* Encrypt a password irreversibly */
	// public function hashPass($pass)
    // {
	// 	return crypt($pass);
	// }

	// /* Check if a password is valid */
	// function validatePassword($pass,$hash,$id = 0)
    // {
	// 	if (crypt($pass, $hash) == $hash) {
	// 		return true;
	// 	} else if(md5($pass) == $hash){
	// 		if($id > 0 && is_numeric($id)){
	// 			$query = "UPDATE #__users set password = '".$this->hashPass($pass)."' where id = $id";
	// 			$this->dbquery($query);
	// 		}
	// 		return true;
	// 	}
	// 	return false;
	// }

	/* Encrypt a password irreversibly */
	public function hashPass($pass)
    {
		return password_hash($pass, PASSWORD_DEFAULT);
	}

   /* Check if a password is valid */
	function validatePass($pass,$hash)
    {
		if (password_verify($pass, $hash)) {
			return true;
		}
		return false;
	}

    function feedback($status,$code,$message=[],$response=[])
    {
        header('Content-Type: application/json');
        ob_end_clean();

        $responseArr = ['status' => $status, 'code' => $code, 'message' => $message, 'response' => $response];
		$responseArr = json_encode(['data'=>$responseArr]);
		$responseArr = $this->pubEncrypt($responseArr);

         die(json_encode(['revert' => $responseArr]));
    }

	public function log($err)
    {
		echo $err.'<br />';
		file_put_contents(MVC.'extension/logs/error_log.txt',date('[Y-m-d h:i:s]').' -> '.$err."\r\n", FILE_APPEND);
	}

	public function dberror ()
    {
		$x = $this->dbx->error;
		 return empty($x) ? '<h1>No sql errors detected</h1>' : $x;
	}

	public function dbclose ()
	{
		if(!is_null($this->dbx))$this->dbx->close();
	}
	
	public function dbtransactions ($x = false)
	{
	   $this->dbx->autocommit($x);
	}

	public function dbcommit ()
	{
	   $this->dbx->commit();
	   $this->dbx->autocommit(true);
	}

	public function dbrollback ()
	{
	   $this->dbx->rollback();
	   $this->dbx->autocommit(true);
	}

	private function cleanSQL ($sql){
		return str_replace("#__",$this->DB_PREFIX,$sql);
	}


	/* Database connection and query functions	*/
	private function dbconnect($DB_NAME = "")
    {
		/* This function connects to mysqli and selects a database. */
		$DB_NAME = (empty($DB_NAME)) ? $this->DB_NAME : $DB_NAME;
		$x = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $DB_NAME);  /* mysqli */
		/* check connection */
		if(mysqli_connect_error()) {
			die('Please refresh.' );
		} else $this->dbx = $x;
		$this->dbx->query("SET time_zone = 'TIMEZONE'");
	}

	public function dbrow ($sql="")
	{
		/* 	This function connects and queries a database. It returns a single row from db as a 1d array. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql));
		$x = ($result) ? $result->fetch_assoc() : ''; /* Mysqli */
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $x;
	}

	public function dbval ($sql){
		/* 	This function connects and queries a database. It returns a single record from the db. */
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql));
		$x = ($result) ? $result->fetch_row() : array('','');
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $x[0]; /* Mysqli */
	}

	public function dbarray ($sql="")
	{
		/* 	This function connects and queries a database. It returns all rows from d result as a 2d array. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql));
		$arr = array();
		if($result){
			while($row = $result->fetch_assoc()){ $arr[]=$row; }; /* Mysqli */
		}
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $arr;
	}


	public function dbcountchanges ($sql="")
	{
		/* 	This function connects and queries a database. It returns the number of insert/updated/deleted/replace rows. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql)); /* Mysqli */
		$x = $this->dbx->affected_rows; /* Mysqli */
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $x;
	}

	public function dbquery ($sql="")
	{
		/* 	This function connects and queries a database. It returns the query result identifier.	*/
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		return $this->dbx->query($this->cleanSQL($sql)); /* Mysqli */
	}


	/* Record a folder structure */
    public function mapFolder($path) 
	{
		if(!is_dir($path)) return array();
		$mappedDirectoryArray = array();
		$path = rtrim($path, '/').'/';
		$handle = opendir($path);
		while(false !== ($file = readdir($handle))) {
			if($file != '.' && $file != '..') {
				$fullpath = $path.$file;
				if(is_dir($fullpath)) {
					$xx = $this->mapFolder($fullpath);
					foreach ($xx as $x) $mappedDirectoryArray[] = $x;
				}
				$mappedDirectoryArray[] = $fullpath;
			}
		}
		closedir($handle);
		return $mappedDirectoryArray;
	}


    /*
	* This function returns the extension of the file without the dot.
	*/
	public function getExtension($str) 
	{
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		return substr($str,$i+1,$l); /* jpg, png, php */
    }

	
	/* Validate an email address */ 
	public function isValidEmail($email="",$checkDomain=false)
	{
        if($checkDomain){
            list($userName, $mailDomain) = split("@", $email);
            if (gethostbyname($mailDomain))  {
              /* This is a valid email domain! */
            }
            else {
              return false; 
            }
        }
		if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
			return false; 
		} else {
			return true;
		}
	}


	public function passwordChecks($pwd)
	{

		$r1='/[A-Z]/';  /* Uppercase */
		$r2='/[a-z]/';  /* Lowercase */
		$r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  /* Wwhatever you mean by 'special char' */
		$r4='/[0-9]/';  /* Numbers */
	
		if(preg_match_all($r1,$pwd, $o)<1) return false;
	    if(preg_match_all($r2,$pwd, $o)<1) return false;
	    if(preg_match_all($r3,$pwd, $o)<1) return false;
		if(preg_match_all($r4,$pwd, $o)<1) return false;
	
		return true;
	}

	public function unique(){
        return uniqid();
    }


	/* 
	* Function to get ip address of a client 
	*/
    private function getIpAddress() 
	{
		$x = (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
		$x = explode(',',$x);
		$q = count($x);
		return $x[$q-1]; 
	}

	/*
	* Function to get browser
	*/
    public function getBrowser($short = false)
    {
        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        $ub = "";
    
        /* First get the platform */
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
       
        /* Next get the name of the useragent yes seperately and for good reason */
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
       
        /* Finally get the correct version number */
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            /* We have no matching number just continue */
        }
       
        /* See how many we have */
        $i = count($matches['browser']);
        if ($i != 1) {
            /* 
			* We will have two since we are not using 'other' argument yet
            * see if version is before or after the name
			*/
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= isset($matches['version'][1]) ? $matches['version'][1] : '';
            }
        }
        else {
            $version= $matches['version'][0];
        }
       
        /* Check if we have a number */
        if ($version==null || $version=="") {$version="?";}
       
        return "$bname $version on $platform" ;
    }



	public function adminAlert($message)
	{
        $message = addslashes($message);
        $this->dbquery("insert into #__admin_alerts (message) values ('$message')");
    }

	/* Get details of location */
    public function getLocation($return_array)
	{
		
         return true;
             /* Call to api should be afetr 2 seconds */
            sleep(3);
            $ipdetails = file_get_contents("https://api.ipinfodb.com/v3/ip-city/?key=3ef44451e061ce8a2d28c13e6248fd0a3d4a8db5e867d94b620571f4cd846108&ip=".$this->ip."&format=json");
            $ipdetails = json_decode($ipdetails);

        $out = [];
        if($return_array == true) return $ipdetails;
        else{
            $out[] = 'IP Address - '.$ipdetails->ipAddress;
            $out[] = 'Browser - '.$afrisoft->browser;
            $out[] = 'IP Status - '.$ipdetails->statusCode;
            $out[] = 'Message - '.$ipdetails->statusMessage;
            $out[] = 'Country Code - '.$ipdetails->countryCode;
            $out[] = 'Country Name - '.$ipdetails->countryName;
            $out[] = 'Region - '.$ipdetails->regionName;
            $out[] = 'City - '.$ipdetails->cityName;
            $out[] = 'Zip Code - '.$ipdetails->zipCode;
            $out[] = 'Latitude - '.$ipdetails->latitude;
            $out[] = 'Longitude - '.$ipdetails->longitude;
            $out[] = 'Time Zone - '.$ipdetails->timeZone;
            foreach ($ipdetails as $key => $value) $out[] = $key.': '.$value;
            return implode('<br />',$out);
        }
    }


	public function getCreditCardType($card_no)
    {
        if (empty($card_no)) {
            return false;
        }
        $xbin = substr($card_no, 0, 6);
        $bin = [];
        $bin['bin'] = $xbin;
        $binJson = json_encode($bin);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://lookup.binlist.net/".$xbin,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "cache-control: no-cache"
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $newresponse = json_decode($response,true);

		return $newresponse['scheme'];
    }



	/* 
	* Function to send mail
	*/
    public function sendMail($vars){
		/*
        $vars['message']
        $vars['email']
        $vars['sender']
        $vars['senderName']
        $vars['replyTo']
        $vars['subject']
        $vars['bcc']
        $vars['cc']
        $vars['emails']
        $vars['attachment']
        $vars['template']
        */

		$config = $this->app_settings();
        /* Set the originating email address and name */
		if(!$this->isValidEmail($vars['sender']) || empty($vars['sender'])){
			$vars['sender'] = $config['admin_email'];
			$vars['senderName'] = $config['admin_name'];
		}
		
		// if(isset($vars['template'])){
		// 	$template = $vars['template'];
		// 	if($template['id'] < 1) $template['id'] = DEFAULT_EMAIL_TEMPLATE;
		// 	$template_email = $this->dbval("SELECT et_template FROM #__email_templates WHERE et_id = {$template['id']}");
		// 	$vars['message']  = $this->customizeMsg($template_email,$template);
		// }
		
		
		if(defined( '_JEXEC' )){
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$vars['sender'] = array( 
			    $config->get( 'mailfrom' ),
			    $config->get( 'fromname' ) 
			);
			$mailer->addRecipient($vars['email'],$vars['email']);
			$mailer->setSubject($vars['subject']);
			$mailer->setBody($vars['message']);
			$mailer->setSender($vars['sender']);
			
		} else {
	        /* class.phpmailer is in INC folder, is been included fron index.php */
			$mailer = new PHPMailer();  /* The true param means it will throw exceptions on errors, which we need to catch */
			$mailer->Host       = $config['admin_email_host']; /* SMTP server */
			//$mailer->SMTPDebug  = 2;  /* Enables SMTP debug information (for testing) */
			$mailer->SMTPAuth   = true; /* Enable SMTP authentication */
			$mailer->Port       = $config['admin_email_port'] <= 0 ? 25 : $config['admin_email_port']; /* Set the SMTP port for the GMAIL server */
			$mailer->Username   = $config['admin_email_name']; /* SMTP account username */
			$mailer->Password   = $config['admin_email_pass']; /* SMTP account password */
			$mailer->AddAddress($vars['email']);
			if(!empty($vars['emails'])){
				if(!is_array($vars['emails'])) $mailer->AddAddress($vars['emails']);
				else foreach($vars['emails'] as $e)$mailer->AddAddress($e);
			}
			if(!empty($vars['cc'])){
				if(!is_array($vars['cc'])) $mailer->AddCC($vars['cc']);
				else foreach($vars['cc'] as $cc)$mailer->AddCC($cc);
			}
			if(!empty($vars['bcc'])){
				if(!is_array($vars['bcc'])) $mailer->AddBCC($vars['bcc']);
				else foreach($vars['bcc'] as $bcc)$mailer->AddBCC($bcc);
			}
			$mailer->Subject = $vars['subject'];
			$mailer->MsgHTML($vars['message']);
			$mailer->SetFrom($vars['sender'],$vars['senderName']);
			if(!empty($vars['replyTo']))$mailer->AddReplyTo($vars['replyTo']);
		}
		$mailer->IsSMTP(); /* Telling the class to use SMTP */
		//$mail->IsSendmail(); /* Telling the class to use SendMail transport */
		
		$mailer->isHTML(true);
		//$mailer->Encoding = 'base64';

		if(is_array($vars['attachment']) && count($vars['attachment']) > 0) {
			foreach($vars['attachment'] as $attach)
			$mailer->addAttachment($attach); /* Attachment */
		} else if(!empty($vars['attachment'])) $mailer->AddAttachment($vars['attachment']); /* Attachment */
		
		$x = $mailer->Send();
		if(!$x) echo $mailer->ErrorInfo;
		return $x;
	}


	/* 
	* Write to config file
	*/
	public function write_ini($file="",$array="")
	{
		$res = [];
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}
			else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
		}
		return file_put_contents($file, implode("\r\n", $res));
	}


	/* 
	* Fetch app configuration data
	*/
	public function app_settings()
	{

		$query = $this->dbrow("select * from #__config");
        return $this->string2array($query['config_data']);
		
	}


   /**
    * Create public and private keys
    * 
    */
    public function createKeys()
    {
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,

        ];
		$this->_keyPath = INC."pem/";
        /* Generating private key */
        $rsa = openssl_pkey_new($config);
        openssl_pkey_export($rsa, $privKey, NULL, $config);
        file_put_contents($this->_keyPath . 'priv.key', $privKey);
        $this->_privKey = openssl_pkey_get_public($privKey);
        /* Generating public key */
        $rsaPri = openssl_pkey_get_details($rsa);
        $pubKey = $rsaPri['key'];
        file_put_contents($this->_keyPath . 'pub.key', $pubKey);
        $this->_pubKey = openssl_pkey_get_public($pubKey);
    }

	/** Setting Private Key
	* @return bool
	*/
    private function setupPrivKey()
    {
	   if (is_resource($this->_privKey)) {
		   return true;
	   }
	   /* Get from the file */
	   $this->_keyPath = INC."pem/";
	   $file = $this->_keyPath . 'priv.key';
	   $privKey = file_get_contents($file);

	   if(!$privKey) $privKey = $this->_priKeyLink;
	   $this->_privKey = openssl_pkey_get_private($privKey);
	    
	   return true;
    }


    /** Setting Public Key
    * @return bool
    */
    private function setupPubKey()
    {
	  /* Get from the file */
	  $this->_keyPath = INC."pem/";
	  $file = $this->_keyPath . 'pub.key';
	  $pubKey = file_get_contents($file);
	  /* Data Source */
	  if(!$pubKey) $pubKey = $this->_pubKeyLink;
	  $this->_pubKey = openssl_pkey_get_public($pubKey);

	  return true;
    }


	/** Encryption with Private Key
    * @param $data
    * @return null|string
    */
    public function privEncrypt($data)
    {
		$data = bin2hex($data);
        $this->setupPrivKey();
        $result = openssl_private_encrypt($data, $encrypted, $this->_privKey);
        if ($result) {
            return base64_encode($encrypted);
        }
        return null;
    }

	/** Decryption with Private key解密
    * @param $encrypted
    * @return null
    */
    public function privDecrypt($encrypted)
    {
        if (!is_string($encrypted)) {
            return null;
        }
        $this->setupPrivKey();
        $encrypted = base64_decode($encrypted);
        $result = openssl_private_decrypt($encrypted, $decrypted, $this->_privKey);
        if ($result) {
            return hex2bin($decrypted);
        }
        return null;
    }

	/** Encryption with Public key加密
    * @param $data
    * @return null|string
    */
    public function pubEncrypt($data)
    {
        $data = bin2hex($data);
        $this->setupPubKey();
        $result = openssl_public_encrypt($data, $encrypted, $this->_pubKey);
        if ($result) {
            return base64_encode($encrypted);
        }
        return null;
    }

	/** Decryption with Public key解密
    * @param $crypted
    * @return null
    */
    public function pubDecrypt($crypted)
    {
        if (!is_string($crypted)) {
            return null;
        }
        $this->setupPubKey();
        $crypted = base64_decode($crypted);
        $result = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
        if ($result) {
            return hex2bin($decrypted);
        }
        return null;
    }


		
	/** The function to send a HTTP Request to the
	*  provided url passing the $_data array to the API	
	*/
	public function URLRequest($url_full, $protocol="GET", $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',$timeout = 180) {
		$url = parse_url($url_full);
		$port = (empty($url['port']))? false : true;
		if (!$port) {
			if ($url['scheme'] == 'http') { $url['port']=80; }
			elseif ($url['scheme'] == 'https') { $url['port']=443; }
		}
		$url['query']=empty($url['query']) ? '' : $url['query'];
		$url['path']=empty($url['path']) ? '' : $url['path'];
		
		if(function_exists('curl_init') && function_exists('curl_exec')){
			if($protocol=="GET"){
				$ch = curl_init($protocol.$url['host'].$url['path'].'?'.$url['query']);
				if($ch){
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					if ($port)curl_setopt($ch, CURLOPT_PORT,$url['port']);
					curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
					$content = curl_exec($ch);
					curl_close($ch);
				} else $content = '';
			}else {
				$ch = curl_init($url['protocol'].$url['host'].$url['path']);
				if($ch){
					//use curl if it exists for better speed
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $url['query']);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
					curl_setopt($ch, CURLOPT_HEADER,0);
					curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
					if ($port)curl_setopt($ch, CURLOPT_PORT,$url['port']);
					curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
					$content = curl_exec($ch);
					curl_close($ch);
				} else $content = '';
			}	
		} else if (function_exists('fsockopen')){
			
			$url['protocol']=$url['scheme'].'://';
			$eol="\r\n";
			$h="";
			$postdata_str="";
			$getdata_str="";
			if ($protocol == 'POST'){
				$h = "Content-Type: text/html".$eol.
				"Content-Length: ".strlen($url['query']).$eol;
				$postdata_str = $url['query'];
			} else	$getdata_str = "?".$url['query'];
			
			$headers =  "$protocol ".$url['protocol'].$url['host'].$url['path'].$getdata_str." HTTP/1.0".$eol.
						"Host: ".$url['host'].$eol.
						"Referer: ".$url['protocol'].$url['host'].$url['path'].$eol.$h.
						"Connection: Close".$eol.$eol.
						$postdata_str;
			$fp = fsockopen($url['host'], $url['port'], $errno, $errstr, $timeout);
			if($fp) {
			  fputs($fp, $headers);
			  $content = '';
			  while(!feof($fp)) { $content .= fgets($fp, 128); }
			  fclose($fp);
			  //removes headers
			  $pattern="/^.*\r\n\r\n/s";
			  $content=preg_replace($pattern,'',$content);
			}
		}else {	
			
			try {
				if($protocol=="GET") return file_get_contents($url_full);
				else {
					$site = explode("?",$url_full,2);
					$content = file_get_contents ($site[0], false, stream_context_create (array ('http'=>array ('method'=>'POST', 'header'=>"Connection: close\r\nContent-Length: ".strlen($site[1])."\r\n", 'content'=>$site[1]))));
				}	
			} catch (Exception $g) {
				$content = "";
			}
			
		}
		
		return $content;
	}


	public function protect()
    {
		$access = $this->getToken();

        /* Select table data */
        $sql = "select * from #__sessions where session_id = '$access'";
        $session_row = $this->dbrow($sql);

        /* If session Id is not equal to last session id */
        if($session_row['session_id'] != $access){

            $this->destroy($access);
            $this->feedback(false, 'EXP_000', ['Oops! Your login session has expired, proceed to login']);

        } else if($session_row['session_ip'] != $this->ip) {

            $this->destroy($access);
            $this->feedback(false, 'EXP_001', ['Oops! Your IP address has changed.']);

        } else if($session_row['session_user_agent'] != $this->browser) {

            $this->destroy($access);
            $this->feedback(false, 'EXP_002', ['Oops! Your login session has expired.']);

        } else if($session_row['session_expires'] > 0  && $session_row['session_expires'] < time()) {

            $this->destroy($access);
            $this->feedback(false, 'EXP_003', ['Oops! Your login session has expired, proceed to login']);

        } else {

            /* Reset the session timeout time in the db */
			$config = $this->app_settings();
            $expires = time() + $config['session_logout'];
            $query = "update #__sessions set session_expires = '$expires' where session_id = '$access'";
            $this->dbquery($query);

			$query = $this->dbrow("select * from #__users where id = '{$session_row['session_user_id']}'");
            $this->setUser($query);
        }

    }

	/* 
	* Generate token 
	*/
    public function _generateToken()
	{
		// $token = hash('sha512',$this->unique().rand(0,1000));
		return md5($this->unique() . rand(0, 100));
	}

	/* 
	* Generate RSA keys
	*/
    public function _generateRsaKeys()
    {
		include __DIR__.'/rsa.php';
        $rsa = new Crypt_RSA();

		$_path = __DIR__.'/pem/';
        /* Request pair */
        extract($rsa->createKey(2048)); /* Key for receiving request */
        $publicKeyIn = $publickey;
        $privateKeyIn = $privatekey;

        /* Response pair */
        extract($rsa->createKey(2048)); /* Key for sending response */
        $publicKeyOut = $publickey;
        $privateKeyOut = $privatekey;


		file_put_contents($_path . 'external.public.key', $publicKeyIn);
		file_put_contents($_path . 'internal.private.key', $privateKeyIn);

		file_put_contents($_path . 'external.private.key', $privateKeyOut);
		file_put_contents($_path . 'internal.public.key', $publicKeyOut);

        // return ['internal'=>['public' => $publicKeyOut, 'private' => $privateKeyIn], 'external'=>['public' => $publicKeyIn, 'private' => $privateKeyOut]];

    }

	/* 
	* RSA Encryption with AES
	*/
	public function _RsaEncrypt($data)
    {

        /* Get symmetric key */
        $chars = array_merge(range('a', 'z'), range('0', '9'));
		$symmetrickey = '';
		for ($i = 0; $i < 64; $i++) $symmetrickey.= $chars[rand(0, count($chars) - 1)];
        $iv = substr($this->_generateToken(),0,16); /* Get IV */

        /* Encrypt content */
        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $symmetrickey, 0, $iv);
        $encrypted_data= base64_encode($encrypted_data);

		/* Get public key file */
		$_path = __DIR__.'/pem/';
		$file = $_path . 'internal.public.key';
		$public_key = file_get_contents($file);

        /* Encryt symmetrickey */
		include __DIR__.'/rsa.php';
        $rsa = new Crypt_RSA();
        $rsa->loadKey($public_key);
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $encrypted_key = $rsa->encrypt($symmetrickey);
        $encrypted_key = base64_encode($encrypted_key);

        return bin2hex($encrypted_data).':'.bin2hex($encrypted_key);
    }

	/* 
	* RSA Decryption with AES
	*/
    public function _RsaDecrypt($encrypted_data_raw)
    {

	
        $iv = substr($this->_generateToken(),0,16); /* Get IV */

        $encrypted_data_splitted = explode(':',$encrypted_data_raw);
        $encrypted_data = $encrypted_data_splitted[0];
        $encrypted_key = $encrypted_data_splitted[1];

        /* Decrypt  symmetrickey */
        $symmetrickey = hex2bin($encrypted_key);
        $symmetrickey = base64_decode($symmetrickey);

		/* Get private key file */
		$_path = __DIR__.'/pem/';
		$file = $_path . 'internal.private.key';
		$private_key = file_get_contents($file);

		include __DIR__.'/rsa.php';
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $rsa->loadKey($private_key);
        $decrypted_key = $rsa->decrypt($symmetrickey);

        /* Decrypt encrypted data */
        $decrypted_data = hex2bin($encrypted_data);
        $decrypted_data = base64_decode($decrypted_data);
        $decrypted_data = openssl_decrypt($decrypted_data, 'AES-256-CBC', $decrypted_key, 0, $iv);

        return json_decode($decrypted_data, true);

    }

}
$software = new softwareFunctions();
?>