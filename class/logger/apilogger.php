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

defined('API_ROOT_PATH') || exit('Restricted access');

/**
 * Collects information for a page request
 *
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 *
 * @package kernel
 */
class APILogger
{
    /**
     * *#@+
     *
     * @var array
     */
    public $queries    = array();
    public $blocks     = array();
    public $extra      = array();
    public $logstart   = array();
    public $logend     = array();
    public $errors     = array();
    public $deprecated = array();
    /**
     * *#@-
     */

    public $usePopup  = false;
    public $activated = false;

    /**
     * *@access protected
     */
    public $renderingEnabled = false;

    /**
     * APILogger::__construct()
     */
    public function __construct()
    {
    }

    /**
     * APILogger::APILogger()
     */
    public function APILogger()
    {
    }

    /**
     * Deprecated, use getInstance() instead
     */
    public function instance()
    {
        return APILogger::getInstance();
    }

    /**
     * Get a reference to the only instance of this class
     *
     * @return object APILogger  reference to the only instance
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new APILogger();
            // Always catch errors, for security reasons
            set_error_handler('APIErrorHandler_HandleError');
            // grab any uncaught exception
            set_exception_handler(array($instance, 'handleException'));
        }

        return $instance;
    }

    /**
     * Enable logger output rendering
     * When output rendering is enabled, the logger will insert its output within the page content.
     * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
     * replace it, otherwise it will be inserted after all the page output.
     */
    public function enableRendering()
    {
        if (!$this->renderingEnabled) {
            ob_start(array(&$this, 'render'));
            $this->renderingEnabled = true;
        }
    }

    /**
     * Returns the current microtime in seconds.
     *
     * @return float
     */
    public function microtime()
    {
        $now = explode(' ', microtime());

        return (float)$now[0] + (float)$now[1];
    }

    /**
     * Start a timer
     *
     * @param string $name name of the timer
     */
    public function startTime($name = 'API')
    {
        if ($this->activated) {
            $this->logstart[$name] = $this->microtime();
        }
    }

    /**
     * Stop a timer
     *
     * @param string $name name of the timer
     */
    public function stopTime($name = 'API')
    {
        if ($this->activated) {
            $this->logend[$name] = $this->microtime();
        }
    }

    /**
     * Log a database query
     *
     * @param string $sql   SQL string
     * @param string $error error message (if any)
     * @param int    $errno error number (if any)
     * @param null   $query_time
     */
    public function addQuery($sql, $error = null, $errno = null, $query_time = null)
    {
        if ($this->activated) {
            $this->queries[] = array('sql' => $sql, 'error' => $error, 'errno' => $errno, 'query_time' => $query_time);
        }
    }

    /**
     * Log display of a block
     *
     * @param string $name      name of the block
     * @param bool   $cached    was the block cached?
     * @param int    $cachetime cachetime of the block
     */
    public function addBlock($name, $cached = false, $cachetime = 0)
    {
        if ($this->activated) {
            $this->blocks[] = array('name' => $name, 'cached' => $cached, 'cachetime' => $cachetime);
        }
    }

    /**
     * Log extra information
     *
     * @param string $name name for the entry
     * @param int    $msg  text message for the entry
     */
    public function addExtra($name, $msg)
    {
        if ($this->activated) {
            $this->extra[] = array('name' => $name, 'msg' => $msg);
        }
    }

    /**
     * Log messages for deprecated functions
     *
     * @deprecated
     *
     * @param int $msg text message for the entry
     *
     */
    public function addDeprecated($msg)
    {
        if ($this->activated) {
            $this->deprecated[] = $msg;
        }
    }

    /**
     * Error handling callback (called by the zend engine)
     *
     * @param integer $errno
     * @param string  $errstr
     * @param string  $errfile
     * @param string  $errline
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        if ($this->activated && ($errno & error_reporting())) {
            // NOTE: we only store relative pathnames
            $this->errors[] = compact('errno', 'errstr', 'errfile', 'errline');
        }
        if ($errno == E_USER_ERROR) {
            $trace = true;
            if (substr($errstr, 0, '8') === 'notrace:') {
                $trace  = false;
                $errstr = substr($errstr, 8);
            }
            echo sprintf('Fatal: %s ~ (%s:%s)', $errstr, basename($errfile), $errline);
            if ($trace && function_exists('debug_backtrace')) {
                echo "<div style='color:#f0f0f0;background-color:#f0f0f0;'>" . _API_FATAL_BACKTRACE . ':<br>';
                $trace = debug_backtrace();
                array_shift($trace);
                foreach ($trace as $step) {
                    if (isset($step['file'])) {
                        echo $this->sanitizePath($step['file']);
                        echo ' (' . $step['line'] . ")\n<br>";
                    }
                }
                echo '</div>';
            }
            exit();
        }
    }

    /**
     * Exception handling callback.
     *
     * @param \Exception|\Throwable $e uncaught Exception or Error
     *
     * @return void
     */
    public function handleException($e)
    {
        if ($this->isThrowable($e)) {
            $msg = get_class($e) . ': ' . $e->getMessage();
            $this->handleError(E_USER_ERROR, $msg, $e->getFile(), $e->getLine());
        }
    }

    /**
     * Determine if an object implements Throwable (or is an Exception that would under PHP 7.)
     *
     * @param mixed $e Expected to be an object related to Exception or Throwable
     *
     * @return bool true if related to Throwable or Exception, otherwise false
     */
    protected function isThrowable($e)
    {
        $type = interface_exists('\Throwable', false) ? '\Throwable' : '\Exception';
        return $e instanceof $type;
    }

    /**
     *
     * @access protected
     *
     * @param string $path
     *
     * @return mixed|string
     */
    public function sanitizePath($path)
    {
        $path = str_replace(array('\\', API_ROOT_PATH, str_replace('\\', '/', realpath(API_ROOT_PATH))), array('/', '', ''), $path);

        return $path;
    }

    /**
     * Output buffering callback inserting logger dump in page output
     * @param $output
     * @return string
     */
    public function render($output)
    {
        global $apiUser;
        if (!$this->activated) {
            return $output;
        }

        $log                    = $this->dump($this->usePopup ? 'popup' : '');
        $this->renderingEnabled = $this->activated = false;
        $pattern                = '<!--{xo-logger-output}-->';
        $pos                    = strpos($output, $pattern);
        if ($pos !== false) {
            return substr($output, 0, $pos) . $log . substr($output, $pos + strlen($pattern));
        } else {
            return $output . $log;
        }
    }

    /**
     * *#@+
     *
     * @protected
     * @param string $mode
     * @return
     */
    public function dump($mode = '')
    {
        include API_ROOT_PATH . '/class/logger/render.php';

        return $ret;
    }

    /**
     * get the current execution time of a timer
     *
     * @param  string $name  name of the counter
     * @param  bool   $unset removes counter from global log
     * @return float  current execution time of the counter
     */
    public function dumpTime($name = 'API', $unset = false)
    {
        if (!$this->activated) {
            return null;
        }

        if (!isset($this->logstart[$name])) {
            return 0;
        }
        $stop  = isset($this->logend[$name]) ? $this->logend[$name] : $this->microtime();
        $start = $this->logstart[$name];

        if ($unset) {
            unset($this->logstart[$name]);
        }

        return $stop - $start;
    }

    /**
     * APILogger::triggerError()
     *
     * @deprecated
     * @param  int     $errkey
     * @param  string  $errStr
     * @param  string  $errFile
     * @param  string  $errLine
     * @param  integer $errNo
     * @return void
     */
    public function triggerError($errkey = 0, $errStr = '', $errFile = '', $errLine = '', $errNo = 0)
    {
        $GLOBALS['apiLogger']->addDeprecated('\'$apiLogger->triggerError();\' is deprecated since API 2.5.4');

        if (!empty($errStr)) {
            $errStr = sprintf($errStr, $errkey);
        }
        $errFile = $this->sanitizePath($errFile);
        $this->handleError($errNo, $errStr, $errFile, $errLine);
    }

    /**
     * *#@+
     *
     * @deprecated
     */
    public function dumpAll()
    {
        $GLOBALS['apiLogger']->addDeprecated('\'$apiLogger->dumpAll();\' is deprecated since API 2.5.4, please use \'$apiLogger->dump(\'\');\' instead.');

        return $this->dump('');
    }

    /**
     * dnmp Blocks @deprecated
     *
     * @return dump
     */
    public function dumpBlocks()
    {
        $GLOBALS['apiLogger']->addDeprecated('\'$apiLogger->dumpBlocks();\' is deprecated since API 2.5.4, please use \'$apiLogger->dump(\'blocks\');\' instead.');

        return $this->dump('blocks');
    }

    /**
     * dumpExtra @deprecated
     *
     * @return dimp
     */
    public function dumpExtra()
    {
        $GLOBALS['apiLogger']->addDeprecated('\'$apiLogger->dumpExtra();\' is deprecated since API 2.5.4, please use \'$apiLogger->dump(\'extra\');\' instead.');

        return $this->dump('extra');
    }

    /**
     * dump Queries @deprecated
     *
     * @return unknown
     */
    public function dumpQueries()
    {
        $GLOBALS['apiLogger']->addDeprecated('\'$apiLogger->dumpQueries();\' is deprecated since API 2.5.4, please use \'$apiLogger->dump(\'queries\');\' instead.');

        return $this->dump('queries');
    }
    /**
     * *#@-
     */
}

/**
 * PHP Error handler
 *
 * NB: You're not supposed to call this function directly, if you don't understand why, then
 * you'd better spend some time reading your PHP manual before you hurt somebody
 *
 * @internal : Using a function and not calling the handler method directly because of old PHP versions
 * set_error_handler() have problems with the array( obj,methodname ) syntax
 * @param       $errNo
 * @param       $errStr
 * @param       $errFile
 * @param       $errLine
 * @param  null $errContext
 * @return bool
 */
function APIErrorHandler_HandleError($errNo, $errStr, $errFile, $errLine, $errContext = null)
{
    /*
    // We don't want every error to come through this will help speed things up'
    if ($errNo == '2048') {
        return true;
    }
    // API should always be STRICT compliant thus the above lines makes no sense and will be removed! -- Added by Taiwen Jiang
    */
    $logger = APILogger::getInstance();
    $logger->handleError($errNo, $errStr, $errFile, $errLine, $errContext);
    return null;
}
