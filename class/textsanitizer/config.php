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

return $config = array(
    'extensions' => array(
        'iframe' => 0,
        'image' => 1,
        'flash' => 1,
        'youtube' => 1,
        'mp3' => 0,
        'wmp' => 0,
        // If other module is used, please modify the following detection and 'link' in /wiki/config.php
        'wiki' => is_dir(API_ROOT_PATH . '/modules/mediawiki/'),
        'mms' => 0,
        'rtsp' => 0,
        'soundcloud' => 0, //new in API 2.5.7
        'ul' => 1,
        'li' => 1),
    'truncate_length' => 60,
    // Filters XSS scripts on display of text
    // There is considerable trade-off between security and performance
    'filterxss_on_display' => false);