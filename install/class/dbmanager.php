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

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/class/logger/apilogger.php';
require_once dirname(dirname(__DIR__)) . '/class/apiload.php';
require_once dirname(dirname(__DIR__)) . '/class/preload.php';
require_once dirname(dirname(__DIR__)) . '/class/database/databasefactory.php';
require_once dirname(dirname(__DIR__)) . '/class/database/mysqldatabase.php';
require_once dirname(dirname(__DIR__)) . '/class/database/mysqlidatabase.php';
require_once dirname(dirname(__DIR__)) . '/class/database/sqlutility.php';
require_once dirname(dirname(__DIR__)) . '/include/dbconfig.php';

/**
 * database manager for API installer
 *
 * @copyright (c) 2000-2016 API Project (www.api.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Haruki Setoyama  <haruki@planewave.org>
 **/
class Db_manager
{
    public $s_tables = array();
    public $f_tables = array();
    public $db;

    /**
     * Db_manager constructor.
     */
    public function __construct()
    {
        $this->db = APIDatabaseFactory::getDatabase();
        $this->db->setPrefix(API_DB_PREFIX);
        $this->db->setLogger(APILogger::getInstance());
    }

    /**
     * @return bool
     */
    public function isConnectable()
    {
        $isConnected = ($this->db->connect(false) !== false);
        if (!$isConnected) {
            $_SESSION['error'] = '(' . $this->db->conn->connect_errno . ') ' . $this->db->conn->connect_error;
        }
        return $isConnected;
    }

    /**
     * @return bool
     */
    public function dbExists()
    {
        return ($this->db->connect() != false);// ? true : false;
    }

    /**
     * @return bool
     */
    public function createDB()
    {
        $this->db->connect(false);

        $result = $this->db->query('CREATE DATABASE ' . API_DB_NAME);

        return ($result != false);// ? true : false;
    }

    /**
     * @param $sql_file_path
     *
     * @return bool
     */
    public function queryFromFile($sql_file_path)
    {
        $tables = array();

        if (!file_exists($sql_file_path)) {
            return false;
        }
        $sql_query = trim(fread(fopen($sql_file_path, 'r'), filesize($sql_file_path)));
        SqlUtility::splitMySqlFile($pieces, $sql_query);
        $this->db->connect();
        foreach ($pieces as $piece) {
            $piece = trim($piece);
            // [0] contains the prefixed query
            // [4] contains unprefixed table name
            $prefixed_query = SqlUtility::prefixQuery($piece, $this->db->prefix());
            if ($prefixed_query != false) {
                $table = $this->db->prefix($prefixed_query[4]);
                if ($prefixed_query[1] === 'CREATE TABLE') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['create'][$table])) {
                            $this->s_tables['create'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->f_tables['create'][$table])) {
                            $this->f_tables['create'][$table] = 1;
                        }
                    }
                } elseif ($prefixed_query[1] === 'INSERT INTO') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['insert'][$table])) {
                            $this->s_tables['insert'][$table] = $this->db->getAffectedRows();
                        } else {
                            $this->s_tables['insert'][$table]=$this->f_tables['insert'][$table]+$this->db->getAffectedRows();
                        }
                    } else {
                        if (!isset($this->f_tables['insert'][$table])) {
                            $this->f_tables['insert'][$table] = $this->db->getAffectedRows();
                        } else {
                            $this->f_tables['insert'][$table]=$this->f_tables['insert'][$table]+$this->db->getAffectedRows();
                        }
                    }
                } elseif ($prefixed_query[1] === 'ALTER TABLE') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['alter'][$table])) {
                            $this->s_tables['alter'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->s_tables['alter'][$table])) {
                            $this->f_tables['alter'][$table] = 1;
                        }
                    }
                } elseif ($prefixed_query[1] === 'DROP TABLE') {
                    if ($this->db->query('DROP TABLE ' . $table) != false) {
                        if (!isset($this->s_tables['drop'][$table])) {
                            $this->s_tables['drop'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->s_tables['drop'][$table])) {
                            $this->f_tables['drop'][$table] = 1;
                        }
                    }
                }
            }
        }

        return true;
    }

    public $successStrings = array(
        'create' => TABLE_CREATED,
        'insert' => ROWS_INSERTED,
        'alter'  => TABLE_ALTERED,
        'drop'   => TABLE_DROPPED);
    public $failureStrings = array(
        'create' => TABLE_NOT_CREATED,
        'insert' => ROWS_FAILED,
        'alter'  => TABLE_NOT_ALTERED,
        'drop'   => TABLE_NOT_DROPPED);

    /**
     * @return string
     */
    public function report()
    {
        $commands = array('create', 'insert', 'alter', 'drop');
        $content  = '<ul class="log">';
        foreach ($commands as $cmd) {
            if (isset($this->s_tables[$cmd])) {
                foreach ($this->s_tables[$cmd] as $key => $val) {
                    $content .= '<li class="success prestige">';
                    $content .= ($cmd !== 'insert') ? sprintf($this->successStrings[$cmd], $key) : sprintf($this->successStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        foreach ($commands as $cmd) {
            if (!@empty($this->f_tables[$cmd])) {
                foreach ($this->f_tables[$cmd] as $key => $val) {
                    $content .= '<li class="failure prestige">';
                    $content .= ($cmd !== 'insert') ? sprintf($this->failureStrings[$cmd], $key) : sprintf($this->failureStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        $content .= '</ul>';

        return $content;
    }

    /**
     * @param $sql
     *
     * @return mixed
     */
    public function query($sql)
    {
        $this->db->connect();

        return $this->db->query($sql);
    }

    /**
     * @param $table
     *
     * @return mixed
     */
    public function prefix($table)
    {
        $this->db->connect();

        return $this->db->prefix($table);
    }

    /**
     * @param $ret
     *
     * @return mixed
     */
    public function fetchArray($ret)
    {
        $this->db->connect();

        return $this->db->fetchArray($ret);
    }

    /**
     * @param $table
     * @param $query
     *
     * @return bool
     */
    public function insert($table, $query)
    {
        $this->db->connect();
        $table = $this->db->prefix($table);
        $query = 'INSERT INTO ' . $table . ' ' . $query;
        if (!$this->db->queryF($query)) {
            if (!isset($this->f_tables['insert'][$table])) {
                $this->f_tables['insert'][$table] = 1;
            } else {
                $this->f_tables['insert'][$table]++;
            }

            return false;
        } else {
            if (!isset($this->s_tables['insert'][$table])) {
                $this->s_tables['insert'][$table] = 1;
            } else {
                $this->s_tables['insert'][$table]++;
            }

            return $this->db->getInsertId();
        }
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return isset($this->f_tables) ? true : false;
    }

    /**
     * @param $tables
     *
     * @return array
     */
    public function deleteTables($tables)
    {
        $deleted = array();
        $this->db->connect();
        foreach ($tables as $key => $val) {
            if (!$this->db->query('DROP TABLE ' . $this->db->prefix($key))) {
                $deleted[] = $ct;
            }
        }

        return $deleted;
    }

    /**
     * @param $table
     *
     * @return bool
     */
    public function tableExists($table)
    {
        $table = trim($table);
        $ret   = false;
        if ($table != '') {
            $this->db->connect();
            $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix($table);
            $ret = $this->db->query($sql);
            $ret = !empty($ret);  //return false on error or $table not found
        }

        return $ret;
    }
}
