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

require_once dirname(__DIR__) . DS . 'include' . DS . 'functions.php';

/**
 * MyIP REST Service Class Factory
 *
 * @author     Simon Roberts <simon@snails.email>
 * @package    myip
 * @subpackage api
 */
class myip {
    
    /**
     * Current Key for the item
     *
     * @var string
     */
    var $key = 'MYIP0000000AAAA0XXXAAAAX000XAA';
    
    /**
     * Current Callers IP Address
     * 
     * @var string
     */
    var $ip = NULL;
    
    /**
     * Current Callers NetBIOS Address
     * 
     * @var string
     */
    var $netbios = NULL;
    
	/**
	 *  __construct()
	 *  
	 *  Constructor
	 */
	function __construct() 
	{
		session_start();
		$this->ip = getIP(true);
		$this->netbios = getNetbios();
	}
	
	/**
	 * Querying Function for retrieving api Results
	 * 
	 * @param unknown $mode
	 * @param unknown $format
	 * @return number[]|array|NULL[]|][[]|boolean[]|[[]|array|][[[]|][[]|][][[]|boolean|][[]|array|boolean|][[]|array|][][[]|array
	 */
	public function query($mode, $format)
	{
	    switch ($mode)
	    {
	        default:
	        case "myip":
	            return self::yonkMyIP($format);
	            break;
	        case "allmyip":
	            return self::yonkAllMyIP($format);
	            break;
	        case "myipv4":
	            return self::yonkMyIPv4($format);
	            break;
	        case "myipv6":
	            return self::yonkMyIPv6($format);
	            break;
	    }
	    return array();
	}
	
	/**
	 * Gets all the NetBIOS IPv4/Ipv6 Addresses of the caller to the api
	 * 
	 * @param unknown $format
	 * @return number[]|string[][]|NULL[]|string[][]|string[][][]|number[][]|NULL[][]
	 */
	private function yonkMyIP($format)
	{
	    $typal = validateDomain($this->netbios)?'netbios':(validateIPv4($this->netbios)?'ipv4':(validateIPv6($this->netbios)?'ipv6':'unknown'));
	    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('history') . "` WHERE `typal` LIKE '$typal' and `value` LIKE '" . $this->netbios . '"';
	    $result = $GLOBALS['APIDB']->queryF($sql);
	    if (!$record = $GLOBALS['APIDB']->fetchArray($result))
	    {
	        $field = validateIPv4($this->ip)?'ipv4':'ipv6';
	        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('history') . "` (`hits`, `typal`, `value`, `$field`, `created`, `queried`) VALUES('1', '$typal', '".$this->netbios."','".$this->ip."', UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
	        if (!$GLOBALS['APIDB']->queryF($sql))
	            die("SQL Failed: $sql");
	        $record = array('hits' => 1, 'hid' => $GLOBALS['APIDB']->getInsertId());
	    } else {
	        $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('history') . "` `hits` = `hits` + 1, `queried` = UNIX_TIMESTAMP() WHERE `hid` = '". $record['hid'] . "'";
	        if (!$GLOBALS['APIDB']->queryF($sql))
	            die("SQL Failed: $sql");
	        $record['hits']++;
	    }
	    switch ($format)
	    {
	        case "txt":
	        case "html":
	            return array('key' => ($this->key + $record['hid']), 'hits' => $record['hits'], 'ip-address'=>array($this->ip), 'ip-type' => array(validateIPv4($this->ip)?'IPv4':'IPv6'), 'netbios' => array($this->netbios));
	            break;
	        default:
	            return array(($this->key + $record['hid'])=>array('hits' => $record['hits'], 'ip'=>array('address'=>$this->ip, 'type' => validateIPv4($this->ip)?'IPv4':'IPv6'), 'netbios' => $this->netbios));
	            break;
	    }
	    
	}

	/**
	 * Gets all the NetBIOS DNS IPV4 + Ipv6 Addresses as well as the Calling Address
	 * 
	 * @param unknown $format
	 * @return boolean[]|][[][]|array[]|][][[][]|string[][][]|number[][][]|string[][][][]|number[][]|NULL[][]|boolean[]|][[][]|array[]|][][[][]|string[][][]|number[][][]|string[][][][]|number[][]|NULL[][]
	 */
	private function yonkAllMyIP($format)
	{
	    $return = array();
	    $return['caller'] = self::yonkMyIP($format);
	    $return['ipv4'] = self::yonkMyIPv4($format);
	    $return['ipv6'] = self::yonkMyIPv6($format);
	    switch ($format)
	    {
	        case "txt":
	        case "html":
	            $result = array();
	            foreach($return as $key => $values)
	                foreach($values as $field => $value)
	                    $result["$key-$field"] = $value;
	            return $result;
	            break;
	        default:
	            return $return;
	            break;
	    }
	}
	
	/**
	 * Gets all the NetBIOS DNS Ipv4 Addresses only
	 * 
	 * @param unknown $format
	 * @return boolean|unknown[][][]|string[][][]|string[][]|number[][]|NULL[][]
	 */
	private function yonkMyIPv4($format)
	{
	    $return = array();
	    
	    foreach(getHostByNamel($this->netbios) as $ipv4)
	    {
	        $field = validateIPv4($ipv4)?'ipv4':'ipv6';
	        $typal = validateDomain($this->netbios)?'netbios':(validateIPv4($this->netbios)?'ipv4':(validateIPv6($this->netbios)?'ipv6':'unknown'));
    	    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('history') . "` WHERE `$field` LIKE '$ipv4' AND `typal` LIKE '$typal' and `value` LIKE '" . $this->netbios . '"';
    	    $result = $GLOBALS['APIDB']->queryF($sql);
    	    if (!$record = $GLOBALS['APIDB']->fetchArray($result))
    	    {
    	        
    	        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('history') . "` (`hits`, `typal`, `value`, `$field`, `created`, `queried`) VALUES('1', '$typal', '".$this->netbios."','".$ipv4."', UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
    	        if (!$GLOBALS['APIDB']->queryF($sql))
    	            die("SQL Failed: $sql");
	            $record = array('hits' => 1, 'hid' => $GLOBALS['APIDB']->getInsertId());
    	    } else {
    	        $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('history') . "` `hits` = `hits` + 1, `queried` = UNIX_TIMESTAMP() WHERE `hid` = '". $record['hid'] . "'";
    	        if (!$GLOBALS['APIDB']->queryF($sql))
    	            die("SQL Failed: $sql");
	            $record['hits']++;
    	    }
    	    switch ($format)
    	    {
    	        case "txt":
    	        case "html":
    	            $return[] = array('key' => ($this->key + $record['hid']), 'hits' => $record['hits'], 'ip-address'=>array($ipv4), 'ip-type' => array(validateIPv4($ipv4)?'IPv4':'IPv6'), 'netbios' => array($this->netbios));
    	            break;
    	        default:
    	            $return[($this->key + $record['hid'])] = array('hits' => $record['hits'], 'ip'=>array('address'=>$ipv4, 'type' => validateIPv4($ipv4)?'IPv4':'IPv6'), 'netbios' => $this->netbios);
    	            break;
    	    }
	    }
	    return empty($return)?false:$return;
	}
	
	/**
	 * Gets all the NetBIOS DNS Ipv6 Addresses only
	 * 
	 * @param string $format
	 * @return boolean|unknown[][][]|string[][][]|string[][]|number[][]|NULL[][]|boolean[][][]|unknown[][][][]
	 */
	private function yonkMyIPv6($format)
	{
	    $return = array();
	    foreach(getHostByNamel6($this->netbios, false) as $ipv6)
	    {
	        $field = validateIPv6($ipv6)?'ipv6':'ipv4';
	        $typal = validateDomain($this->netbios)?'netbios':(validateIPv4($this->netbios)?'ipv4':(validateIPv6($this->netbios)?'ipv6':'unknown'));
	        $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('history') . "` WHERE `$field` LIKE '$ipv6' AND `typal` LIKE '$typal' and `value` LIKE '" . $this->netbios . '"';
	        $result = $GLOBALS['APIDB']->queryF($sql);
	        if (!$record = $GLOBALS['APIDB']->fetchArray($result))
	        {
	            
	            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('history') . "` (`hits`, `typal`, `value`, `$field`, `created`, `queried`) VALUES('1', '$typal', '".$this->netbios."','".$ipv6."', UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
	            if (!$GLOBALS['APIDB']->queryF($sql))
	                die("SQL Failed: $sql");
	            $record = array('hits' => 1, 'hid' => $GLOBALS['APIDB']->getInsertId());
	        } else {
	            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('history') . "` `hits` = `hits` + 1, `queried` = UNIX_TIMESTAMP() WHERE `hid` = '". $record['hid'] . "'";
	            if (!$GLOBALS['APIDB']->queryF($sql))
	                die("SQL Failed: $sql");
	            $record['hits']++;
	        }
	        switch ($format)
	        {
	            case "txt":
	            case "html":
	                $return[] = array('key' => ($this->key + $record['hid']), 'hits' => $record['hits'], 'ip-address'=>array($ipv6), 'ip-type' => array(validateIPv4($ipv6)?'IPv4':'IPv6'), 'netbios' => array($this->netbios));
	                break;
	            default:
	                $return[($this->key + $record['hid'])] = array('hits' => $record['hits'], 'ip'=>array('address'=>$ipv6, 'type' => validateIPv4($ipv6)?'IPv4':'IPv6'), 'netbios' => $this->netbios);
	                break;
	        }
	    }
	    return empty($return)?false:$return;
	}
	
	
	/**
	 *  __destruct()
	 *  Destructor
	 */	
	function __destruct() {
		
	}
	
}
