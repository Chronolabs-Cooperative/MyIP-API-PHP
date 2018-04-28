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

    $ip = getIP(true);
    $netbios = getNetbios();
    $ipv4 = getHostByNamel($netbios);
    $ipv6 = getHostByNamel6($netbios, false);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'OpenRend'},
		{'service': 'twitter', 'id': 'SimonXaies'},
		{'service': 'facebook', 'id': 'mynamesnot'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>
<body>
<div class="main">
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This is an API REST Service returns your caller or all reference to AAAA + A Records IPv4 or IPv6 addresses from the source caller in JSON, XML, Serialisation, HTML and RAW outputs.</p>
    <h2>You current calling details are as follows:</h2>
    <blockquote>
    		<div style="width: 100%; margin: 4px; padding: 5px;">
    			<div class="help-title-text" style="width: 23%; text-align: right; font-size: 1.2343em;">Remote Calling <?php echo (validateIPv4($ip)?"IPv4":"IPv6"); ?>:&nbsp;</div>
    			<div class="help-url-example" style="width: 23%; text-align: center; font-weight: bold; font-size: 1.1123em;"><?php echo $ip; ?></div>
    		</div>
    		<?php if ($netbios != $ip) { ?>
    		<div style="width: 100%; margin: 4px; padding: 5px;">
    			<div class="help-title-text" style="width: 23%; text-align: right; font-size: 1.2343em;">Remote Calling NetBIOS:&nbsp;</div>
    			<div class="help-url-example" style="width: 23%; text-align: center; font-weight: bold; font-size: 1.1123em;"><?php echo $netbios; ?></div>
    		</div>
    		<?php } ?>
    		<?php if (count($ipv4) > 0) { ?>
    		<div style="width: 100%; margin: 4px; padding: 5px;">
    			<div class="help-title-text" style="width: 23%; text-align: right; font-size: 1.2343em;">All DNS IPv4 for NetBIOS:&nbsp;</div>
    			<div class="help-url-example" style="width: 23%; text-align: center; font-weight: bold; font-size: 1.1123em;"><?php foreach($ipv4 as $id => $addy) { echo $addy . (($id<count($ipv4)-1)?'<br/>':''); } ?></div>
    		</div>
    		<?php } ?>
    		<?php if (count($ipv6) > 0) { ?>
    		<div style="width: 100%; margin: 4px; padding: 5px;">
    			<div class="help-title-text" style="width: 23%; text-align: right; font-size: 1.2343em;">All DNS IPv6 for NetBIOS:&nbsp;</div>
    			<div class="help-url-example" style="width: 23%; text-align: center; font-weight: bold; font-size: 1.1123em;"><?php foreach($ipv6 as $id => $addy) { echo $addy . (($id<count($ipv6)-1)?'<br/>':''); } ?></div>
    		</div>
    		<?php } ?>
    </blockquote>
	<h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo API_URL . '/'; ?>docs/" target="_blank"><?php echo API_URL . '/'; ?>docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>PHP Document Output</h2>
    <p>This is done with the <em>*.php</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.php" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.php</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.php" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.php</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.php" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.php</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.php" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.php</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	$result = eval("?>".file_get_contents("<?php echo API_URL . '/'; ?>v1/allmyip.php")."<?php echo '<?php'; ?>");
    	die(print_r($results, true));
    </pre>
    <h2>HTML Document Output</h2>
    <p>This is done with the <em>*.html</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.html" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.html</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.html" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.html</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.html" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.html</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.html" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.html</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	die(file_get_contents("<?php echo API_URL . '/'; ?>v1/allmyip.html"));
    </pre>
    <h2>TEXT Document Output</h2>
    <p>This is done with the <em>*.txt</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.txt" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.txt</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.txt" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.txt</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.txt" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.txt</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.txt" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.txt</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	die(str_replace("<br />", "\n", file_get_contents("<?php echo API_URL . '/'; ?>v1/allmyip.txt")));
    </pre>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>*.serial</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.serial" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.serial</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.serial" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.serial</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.serial" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.serial</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.serial" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.serial</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	$result = unserialize(file_get_contents("<?php echo API_URL . '/'; ?>v1/allmyip.serial"));
    	die(print_r($results, true));
    </pre>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>*.json</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.json" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.json</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.json" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.json</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.json" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.json</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.json" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.json</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	$result = json_decode(file_get_contents("<?php echo API_URL . '/'; ?>v1/allmyip.json"), true);
    	die(print_r($results, true));
    </pre>
    
    <h2>XML Document Output</h2>
    <p>This is done with the <em>*.xml</em> extension at the end of the url, there is only static calls to this REST API for the  IPv4 or IPv6 address to be returned.</p>
    <blockquote>
        <font class="help-title-text">This will return the IPv4 or IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myip.xml" target="_blank"><?php echo API_URL . '/'; ?>v1/myip.xml</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 + IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/allmyip.xml" target="_blank"><?php echo API_URL . '/'; ?>v1/allmyip.xml</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv4 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv4.xml" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv4.xml</a></font><br /><br />
        <font class="help-title-text">This will return all the DNS IPv6 of the caller to the API</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/myipv6.xml" target="_blank"><?php echo API_URL . '/'; ?>v1/myipv6.xml</a></font><br /><br />
    </blockquote>
    <p class="help-title-text">Example on how to call in PHP to return your result from the API is as follows:~</p>
    <pre class="help-url-example">
    	$result = simplexml_load_file("<?php echo API_URL . '/'; ?>v1/allmyip.xml");
    	die(print_r($results, true));
    </pre>
    <h2 style="margin-top: 33px;">The Author</h2>
    <p>This was developed by Dr. Simon Antony Roberts in 2018 and is part of the Chronolabs Cooperative API REST Services + Systems.<br/><br/>This is open source which you can download from <a href="https://github.com/Chronolabs-Cooperative/MyIP-API-PHP">https://github.com/Chronolabs-Cooperative/MyIP-API-PHP</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
