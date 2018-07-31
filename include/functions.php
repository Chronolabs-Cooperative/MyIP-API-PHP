<?php
/**
 * My IPv4 or IPv6 REST Services API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         myip-api
 * @since           1.0.1
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.0.3
 * @description		A REST Services API that returns either or both or all IPv4, IPv6 addresses of a caller!
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/MyIP-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */



if (!function_exists("getIP")) {

	/* function whitelistGetIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param		boolean		$asString	Whether to return an address or network long integer
	 * 
	 * @return 		mixed
	 */
	function getIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
    		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
    			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
    		} else
        		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
        		} else
            		if (!empty($_SERVER['HTTP_FORWARDED'])) {
            			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
            		} else
                		if (!empty($_SERVER['HTTP_VIA'])) {
                			$proxy_ip = $_SERVER['HTTP_VIA'];
                		} else
                    		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
                    			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
                    		} else
                        		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
                        			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
                        		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}


if (!function_exists("getNetbios")) {
    
    /* function whitelistGetIP()
     *
     * 	get the True IPv4/IPv6 address of the client using the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @param		boolean		$asString	Whether to return an address or network long integer
     *
     * @return 		mixed
     */
    function getNetbios() {
        // Gets the proxy ip sent by the user
        $proxy_ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else
            if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
            } else
                if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                    $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
                } else
                    if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
                    } else
                        if (!empty($_SERVER['HTTP_VIA'])) {
                            $proxy_ip = $_SERVER['HTTP_VIA'];
                        } else
                            if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
                                $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
                            } else
                                if (!empty($_SERVER['HTTP_COMING_FROM'])) {
                                    $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
                                }
        if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
            $the_IP = $regs[0];
        } else {
            $the_IP = $_SERVER['REMOTE_ADDR'];
        }
        return gethostbyaddr($the_IP);
    }
}


/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */

if (!function_exists("validateMD5")) {
    function validateMD5($md5) {
        if(preg_match("/^[a-f0-9]{32}$/i", $md5)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * validateEmail()
 * Validates an Email Address
 *
 * @param string $email
 * @return boolean
 */
if (!function_exists("validateEmail")) {
    function validateEmail($email) {
        if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$", $email)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * validateDomain()
 * Validates a Domain Name
 *
 * @param string $domain
 * @return boolean
 */
if (!function_exists("validateDomain")) {
    function validateDomain($domain) {
        if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
            return false;
        }
        return $domain;
    }
}

/**
 * validateIPv4()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
if (!function_exists("validateIPv4")) {
    function validateIPv4($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
        {
            return false;
        } else {
            return true;
        }
    }
}
/**
 * validateIPv6()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
if (!function_exists("validateIPv6")) {
    function validateIPv6($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
        {
            return false;
        } else {
            return true;
        }
    }
}
/**
 * get A + AAAA record for $host
 * 
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 * 
 * @param   host    string      netbios networking name\
 * @param   try_a   boolean     try for A Record inclusive of AAAA Records
 * 
 * return array
 */
if (!function_exists("getHostByName6")) {
    function getHostByName6($host, $try_a = false) {   
        $dns6 = dns_get_record($host, DNS_AAAA);
        if ($try_a == true) {
            $dns4 = getHostByName($host);
            $dns = array_merge($dns4, $dns6);
        }
        else { $dns = $dns6; }
        if ($dns == false) { return false; }
        else { return $dns; }
    }
}

/**
 * get A + AAAA record IPv4/IPv6 Addresses for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 * @param   try_a   boolean     try for A Record inclusive of AAAA Records
 *
 * return array
 */
if (!function_exists("getHostByNamel6")) {
    function getHostByNamel6($host, $try_a = false) {
        
        $dns6 = dns_get_record($host, DNS_AAAA);
        if ($try_a == true) {
            $dns4 = getHostByName($host);
            $dns = array_merge($dns4, $dns6);
        }
        else { $dns = $dns6; }
        $ip6 = array();
        $ip4 = array();
        foreach ($dns as $record) {
            if ($record["type"] == "A") {
                $ip4[] = $record["ip"];
            }
            if ($record["type"] == "AAAA") {
                $ip6[] = $record["ipv6"];
            }
        }
        if (count($ip6) < 1) {
            if ($try_a == true) {
                if (count($ip4) < 1) {
                    return false;
                }
                else {
                    return $ip4;
                }
            }
            else {
                return false;
            }
        }
        else {
            return $ip6;
        }
    }
}

/**
 * get A record for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 *
 * return array
 */
if (!function_exists("getHostByName")) {
    function getHostByName($host) {
        $dns = dns_get_record($host, DNS_A);
        if ($dns == false) { return false; }
        else { return $dns; }
    }
}

/**
 * get A record IPv4 Addresses for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 *
 * return array
 */
if (!function_exists("getHostByNamel")) {
    function getHostByNamel($host) {
        $dns4 = getHostByName($host);
        $ip4 = array();
        foreach ($dns4 as $record) {
            if ($record["type"] == "A") {
                $ip4[] = $record["ip"];
            }
        }
        if (count($ip4) < 1) {
            return false;
        }
        else {
            return $ip4;
        }
    }
}

if (!function_exists("getURIData")) {
    
    /* function yonkURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
            else {
                $uploadfile = false;
                foreach($post as $field => $value)
                    if (substr($value , 0, 1) == '@' && !file_exists(substr($value , 1, strlen($value) - 1)))
                        unset($post[$field]);
                    else
                        $uploadfile = true;
                curl_setopt($btt, CURLOPT_POST, true);
                curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
                
                if (!empty($headers))
                    foreach($headers as $key => $value)
                        if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                            unset($headers[$key]);
                if ($uploadfile==true)
                    $headers[]  = 'Content-Type: multipart/form-data';
            }
            if (count($headers)==0 || empty($headers))
                curl_setopt($btt, CURLOPT_HEADER, false);
                else {
                    curl_setopt($btt, CURLOPT_HEADER, true);
                    curl_setopt($btt, CURLOPT_HTTPHEADER, $headers);
                }
                curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
                curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($btt, CURLOPT_VERBOSE, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
                $data = curl_exec($btt);
                $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
                $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
                $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
                $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
                $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
                $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
                curl_close($btt);
                return $data;
    }
}

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 * 
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {
	
		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 * 
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {
	
			$domElement = is_null($domElement) ? $this : $domElement;
	
			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {
	
					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}
					 
					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}
					 
					$this->fromMixed($mixedElement, $node);
					 
				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}
			 
		}
		 
	}
}

?>
