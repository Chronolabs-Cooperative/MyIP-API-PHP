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

api_load('APIEditor');

/**
 * FormDhtmlTextArea
 *
 * @package
 * @author              John
 * @copyright       (c) 2000-2016 API Project (www.api.org)
 * @access              public
 */
class FormDhtmlTextArea extends APIEditor
{
    /**
     * Hidden text
     *
     * @var string
     * @access private
     */
    public $_hiddenText = 'apiHiddenText';

    /**
     * FormDhtmlTextArea::__construct()
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->rootPath = '/class/apieditor/' . basename(__DIR__);
        $hiddenText     = isset($this->configs['hiddenText']) ? $this->configs['hiddenText'] : $this->_hiddenText;
        api_load('APIFormDhtmlTextArea');
        $this->renderer = new APIFormDhtmlTextArea('', $this->getName(), $this->getValue(), $this->getRows(), $this->getCols(), $hiddenText, $this->configs);
    }

    /**
     * FormDhtmlTextArea::render()
     *
     * @return string
     */
    public function render()
    {
        return $this->renderer->render();
    }
}
