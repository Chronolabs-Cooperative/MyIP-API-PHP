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


require_once './include/common.inc.php';
include_once '../class/apiload.php';
include_once '../class/preload.php';
include_once '../class/tmpbase/tmpbasefactory.php';
include_once '../class/logger/apilogger.php';

$_SESSION = array();
setcookie('xo_install_user', '', null, null, null);
defined('API_INSTALL') || die('API Installation wizard die');

$install_rename_suffix = uniqid(substr(md5($x = mt_rand()) . $x, -10));
$installer_modified    = 'install_remove_' . $install_rename_suffix;

$pageHasForm = false;

$content = '';
include "./language/{$wizard->language}/finish.php";

include './include/install_tpl.php';
