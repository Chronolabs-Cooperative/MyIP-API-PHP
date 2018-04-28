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
 * provide some utility methods for databases
 *
 * @author     Kazumi Ono <onokazu@api.org>
 * @package    kernel
 * @subpackage database
 */
class SqlUtility
{
    /**
     * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
     *
     * Removes comment and splits large sql files into individual queries
     *
     * Last revision: September 23, 2001 - gandon
     *
     * @param  array  $ret the splitted sql commands
     * @param  string $sql the sql commands
     * @return boolean always true
     * @access   public
     */
    public static function splitMySqlFile(&$ret, $sql)
    {
        $sql          = trim($sql);
        $sql_len      = strlen($sql);
        $char         = '';
        $string_start = '';
        $in_string    = false;

        for ($i = 0; $i < $sql_len; ++$i) {
            $char = $sql[$i];
            // We are in a string, check for not escaped end of
            // strings except for backquotes that can't be escaped
            if ($in_string) {
                for (; ;) {
                    $i = strpos($sql, $string_start, $i);
                    // No end of string found -> add the current
                    // substring to the returned array
                    if (!$i) {
                        $ret[] = $sql;

                        return true;
                    }
                    // Backquotes or no backslashes before
                    // quotes: it's indeed the end of the
                    // string -> exit the loop
                    elseif ($string_start === '`' || $sql[$i - 1] !== '\\') {
                        $string_start = '';
                        $in_string    = false;
                        break;
                    }
                    // one or more Backslashes before the presumed
                    // end of string...
                    else {
                        // first checks for escaped backslashes
                        $j                 = 2;
                        $escaped_backslash = false;
                        while ($i - $j > 0 && $sql[$i - $j] === '\\') {
                            $escaped_backslash = !$escaped_backslash;
                            ++$j;
                        }
                        // ... if escaped backslashes: it's really the
                        // end of the string -> exit the loop
                        if ($escaped_backslash) {
                            $string_start = '';
                            $in_string    = false;
                            break;
                        } // ... else loop
                        else {
                            ++$i;
                        }
                    } // end if...elseif...else
                } // end for
            } // end if (in string)
            // We are not in a string, first check for delimiter...
            elseif ($char === ';') {
                // if delimiter found, add the parsed part to the returned array
                $ret[]   = substr($sql, 0, $i);
                $sql     = ltrim(substr($sql, min($i + 1, $sql_len)));
                $sql_len = strlen($sql);
                if ($sql_len) {
                    $i = -1;
                } else {
                    // The submited statement(s) end(s) here
                    return true;
                }
            } // end else if (is delimiter)
            // ... then check for start of a string,...
            elseif (($char === '"') || ($char === '\'') || ($char === '`')) {
                $in_string    = true;
                $string_start = $char;
            } // end else if (is start of string)
            // for start of a comment (and remove this comment if found)...
            elseif ($char === '#' || ($char === ' ' && $i > 1 && $sql[$i - 2] . $sql[$i - 1] === '--')) {
                // starting position of the comment depends on the comment type
                $start_of_comment = (($sql[$i] === '#') ? $i : $i - 2);
                // if no "\n" exits in the remaining string, checks for "\r"
                // (Mac eol style)
                $end_of_comment = strpos(' ' . $sql, "\012", $i + 2) ?: strpos(' ' . $sql, "\015", $i + 2);
                if (!$end_of_comment) {
                    // no eol found after '#', add the parsed part to the returned
                    // array and exit
                    // RMV fix for comments at end of file
                    //$last = trim(substr($sql, 0, $i-1));
                    //if (!empty($last)) {
                    //   $ret[] = $last;
                    //}
                    return true;
                } else {
                    $sql     = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
                    $sql_len = strlen($sql);
                    $i--;
                } // end if...else
            } // end else if (is comment)
        } // end for
        // add any rest to the returned array
        if (!empty($sql) && trim($sql) != '') {
            $ret[] = $sql;
        }
        foreach($ret as $key => $value)
            if (strpos(" $value", 'use')||strpos(" $value", 'USE'))
                unset($ret[$key]);
        return true; // end for
    }

    /**
     * add a prefix.'_' to all tablenames in a query
     *
     * @param  string $query  valid SQL query string
     * @param  string $prefix prefix to add to all table names
     * @return mixed  FALSE on failure
     */
    public static function prefixQuery($query, $prefix)
    {
        $pattern  = "/^(INSERT[\s]+INTO|CREATE[\s]+TABLE|ALTER[\s]+TABLE|UPDATE)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
        $pattern2 = "/^(DROP TABLE)(\s)+([`]?)([^`\s]+)\\3(\s)?$/siU";
        if (preg_match($pattern, $query, $matches) || preg_match($pattern2, $query, $matches)) {
            $replace    = "\\1 " . $prefix . "_\\4\\5";
            $matches[0] = preg_replace($pattern, $replace, $query);

            return $matches;
        }

        return false;
    }
}