## Chronolabs Cooperative presents

# MyIP Address REST API Services

## Version: 1.0.7 (stable)

### Author: Dr. Simon Antony Roberts <simon@snails.email>

#### Demo: http://myip.snails.email

This is an API REST Service returns your caller or all reference to AAAA + A Records IPv4 or IPv6 addresses from the source caller in JSON, XML, Serialisation, HTML and RAW outputs. 

# Apache Mod Rewrite (SEO Friendly URLS)

The follow lines go in your API_ROOT_PATH/.htaccess

    php_value memory_limit 25M
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^([a-z0-9]{2})/(myip|allmyip|myipv4|myipv6).(php|html|txt|serial|json|xml)$ ./index.php?version=$1&mode=$2&format=$3 [L,NC,QSA]

## Licensing

 * This is released under General Public License 3 - GPL3 - Only!

# Installation

Copy the contents of this archive/repository to the run time environment, configue apache2, ngix or iis to resolve the path of this repository and run the HTML Installer.
