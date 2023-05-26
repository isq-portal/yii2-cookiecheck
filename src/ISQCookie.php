<?php

/** namespace */
namespace IsqPortal\Yii2Cookiecheck;

/** imports */
use Yii;

/**
 * main ISQCookie class for ISQ Cookie Handling and GDPR Compliance
 */
class ISQCookie
{
    /** class properties */
    public $config = [];

    private $decisionMade = false;

    private $choices = [];

    private $vendorPath;

    private $assetBasePath;

    private $webroot;

    /** constructor
     * @param $basePath
     * @param $webroot
     */
    public function __construct($options)
    {

        /** fill config with options array */
        if (is_object($options)) {
            $options = (array)$options;
        }

        $this->config = $options;

        $this->vendorPath = $options['vendorPath'];

        $this->assetBasePath = $options['baseUrl'];

        $this->webroot = $this->config['webroot'];

        $this->decisionMade = self::getCookie('isqCookieBannerHidden') == 'true';

        $this->choices = $this->getChoices();

    }

    /** returns the cookie saved choices
     * @return array|mixed
     */
    public function getChoices()
    {
        if (self::getCookie('isqCookie') !== false) {
            $cookie = self::getCookie('isqCookie');
            $cookie = self::decrypt($cookie);
            return $cookie;
        }

        $return = [];
        foreach ($this->enabledCookies() as $name => $label) {
            $return[$name] = $this->config['unsetDefault'];
        }
        return $return;
    }

    /** encrypts the cookie value for storage
     * @param $value
     * @return string
     */
    public static function encrypt($value)
    {
        $value  = json_encode($value);
        $return = base64_encode($value);
        return $return;
    }

    /** decrypts the cookie value for processing
     * @param $value
     * @return mixed
     */
    public static function decrypt($value)
    {
        $value  = base64_decode($value);
        $value  = str_replace('\"', '"', $value);
        $return = json_decode($value, true);
        return $return;
    }

    /** checks if cookie is allowed
     * @param $name
     * @return bool
     */
    public function isAllowed($name)
    {
        $choices = $this->getChoices();
        return isset($choices[$name]) && $choices[$name] == 'allowed';
    }

    /** checks cookie config enabled
     * @param $name
     * @return bool
     */
    public function isEnabled($name)
    {
        $check = $this->config[$name];
        return is_array($check) && isset($check['enabled']) && $check['enabled'];
    }

    /** gets the attributes from cookie config
     * @param $name
     * @param $attribute
     * @return false|mixed
     */
    public function getConfig($name, $attribute)
    {
        return isset($this->config[$name]) && isset($this->config[$name][$attribute])
        ? $this->config[$name][$attribute]
        : false;
    }

    /** moin output function
     * @return void
     */
    public function output()
    {
        return $this->getOutput();
    }

    /** wrapper: get every output, embeds, codes
     * @return string
     */
    public function getOutput()
    {
        $return = [];

        // Get popup output
        $return[] = $this->getOutputHTML('popup');

        // Get embed codes
        foreach ($this->config as $configKey => $configValue) {

            $isArray = is_array($configValue);
            $isAllowed = $this->isAllowed($configKey);

            // make php8 conform and avoid illegal string offsets
            $isConfigValue = isset($configValue['enabled']) ? $configValue['enabled'] : false;

            if (!$isArray || !$isConfigValue || !$isAllowed) {
                continue;
            }
            $return[] = $this->getOutputHTML('cookies/'.$configKey.'/output');
        }

        return implode('\n', $return);
    }

    /** returns the html output from php file
     * @param $filename
     * @return false|string
     */
    public function getOutputHTML($filename)
    {
        $path = $this->vendorPath . '/src/output/'.$filename.'.php';

        if (!file_exists($path)) {
            return false;
        }

        ob_start();
        include $path;
        return trim(ob_get_clean());
    }

    /** filter enabled cookies
     * @return array
     */
    public function enabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {
            if (!$this->isEnabled($name)) {
                continue;
            }
            $return[$name] = $value['label'];
        }
        return $return;
    }

    /** filter disabled cookies
     * @return array
     */
    public function disabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {

            $isEnabled = $this->isEnabled($name);
            $isArray = is_array($value);
            $isAllowed = $this->isAllowed($name);

            if (!$isEnabled || !$isArray || $isAllowed) {
                continue;
            }

            $return[$name] = $value['label'];
        }
        return $return;
    }

    /** validates and sets the cookie
     * @param $name
     * @param $value
     * @param $lifetime
     * @param $lifetimePeriod
     * @param $domain
     * @param $secure
     * @return bool
     * @throws \Exception
     */
    public static function setCookie(
        $name,
        $value,
        $lifetime = 30,
        $lifetimePeriod = 'days',
        $domain = '/',
        $secure = false
    ) {
        // Validate parameters
        self::validateSetCookieParams($name, $value, $lifetime, $domain, $secure);

        // Calculate expiry
        $expiry = strtotime('+'.$lifetime.' '.$lifetimePeriod);

        // Set cookie
        return setcookie($name, $value, $expiry, $domain, $secure);
    }

    /**
     * @param $name
     * @param $value
     * @param $lifetime
     * @param $domain
     * @param $secure
     * @return bool
     * @throws \Exception
     */
    public static function validateSetCookieParams($name, $value, $lifetime, $domain, $secure)
    {
        // Types of parameters to check
        $paramTypes = [
            // Type => Array of variables
            'string' => [$name, $value, $domain],
            'int'    => [$lifetime],
            'bool'   => [$secure],
        ];

        // Validate basic parameters
        $validParams = self::basicValidationChecks($paramTypes);

        // Ensure parameters are still valid
        if (!$validParams) {
            // Failed parameter check
            header('HTTP/1.0 403 Forbidden');
            throw new \Exception('Incorrect parameter passed to Cookie::set');
        }

        return true;
    }

    /** basic validation check
     * @param $paramTypes
     * @return bool
     */
    public static function basicValidationChecks($paramTypes)
    {
        foreach ($paramTypes as $type => $variables) {
            $functionName = 'is_'.$type;
            foreach ($variables as $variable) {
                if (!$functionName($variable)) {
                    return false;
                }
            }
        }
        return true;
    }

    /** destroy cookies by group name
     * @param $groupName
     * @return array|false
     */
    public function clearCookieGroup($groupName)
    {

        $path = $this->vendorPath . '/src/output/cookies/'.$groupName.'/cookies.php';

        if (!file_exists($path)) {
            return false;
        }
        $clearCookies = include $path;

        $defaults = [
            'path'   => '/',
            'domain' => $_SERVER['HTTP_HOST'],
        ];

        if (isset($clearCookies['defaults'])) {
            $defaults = array_merge($defaults, $clearCookies['defaults']);
            unset($clearCookies['defaults']);
        }

        $return = [];

        foreach ($clearCookies as $cookie) {
            $cookie['path'] = isset($cookie['path']) ? $cookie['path'] : $defaults['path'];
            $cookie['domain'] = isset($cookie['domain']) ? $cookie['domain'] : $defaults['domain'];
            self::destroyCookie($cookie['name'], $cookie['path'], $cookie['domain']);
            $return[] = $cookie;
        }
        return $return;
    }

    /** If cookie exists - return it, otherwise return false
     * @param $name
     * @return false|mixed
     */
    public static function getCookie($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    /** destroy cookie, set cookie expiration to 1 hour ago
     * @param $name
     * @param $path
     * @param $domain
     * @return bool
     */
    public static function destroyCookie($name, $path = '', $domain = '')
    {
        return setcookie($name, '', time() - 3600, $path, $domain);
    }
}
