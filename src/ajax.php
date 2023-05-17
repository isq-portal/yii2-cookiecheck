<?php
require_once('ISQCookie.php');

if (!isset($_POST['action'])) {
    header('HTTP/1.0 403 Forbidden');
    throw new Exception("Action not specified");
}

switch ($_POST['action']) {
    case 'hide':
        // Set cookie
        \IsqPortal\Yii2Cookiecheck\ISQCookie::setCookie('isqCookieBannerHidden', 'true', 52, 'weeks');
        header('Content-Type: application/json');
        die(json_encode(['success' => true]));
        break;

    case 'toggle':
        $isqCookie = new \IsqPortal\Yii2Cookiecheck\ISQCookie();
        $return    = [];

        // Update if cookie allowed or not
        $choices = $isqCookie->getCookie('isqCookie');
        if ($choices == false) {
            $choices = [];
            $enabledCookies = $isqCookie->enabledCookies();
            foreach ($enabledCookies as $name => $label) {
                $choices[$name] = $isqCookie->config['unsetDefault'];
            }
            $isqCookie->setCookie('isqCookie', $isqCookie->encrypt($choices), 52, 'weeks');
        } else {
            $choices = $isqCookie->decrypt($choices);
        }
        $choices[$_POST['name']] = $_POST['value'] == 'true' ? 'allowed' : 'blocked';

        // Remove cookies if now disabled
        if ($choices[$_POST['name']] == 'blocked') {
            $removeCookies = $isqCookie->clearCookieGroup($_POST['name']);
            $return['removeCookies'] = $removeCookies;
        }

        $choices = $isqCookie->encrypt($choices);
        $isqCookie->setCookie('isqCookie', $choices, 52, 'weeks');

        header('Content-Type: application/json');
        die(json_encode($return));
        break;

    case 'load':
        $isqCookie = new \IsqPortal\Yii2Cookiecheck\ISQCookie();
        $return    = [];

        $removeCookies = [];

        foreach ($isqCookie->disabledCookies() as $cookie => $label) {
            $removeCookies = array_merge($removeCookies, $isqCookie->clearCookieGroup($cookie));
        }
        $return['removeCookies'] = $removeCookies;

        header('Content-Type: application/json');
        die(json_encode($return));
        break;

    default:
        header('HTTP/1.0 403 Forbidden');
        throw new Exception("Action not recognised");
        break;
}
