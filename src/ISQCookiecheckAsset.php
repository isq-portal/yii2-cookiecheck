<?php

namespace IsqPortal\Yii2Cookiecheck;

use yii\web\AssetBundle;

/**
 * Asset bundle for ISQCookiecheck Widget
 */
class ISQCookiecheckAsset extends AssetBundle
{
    public $sourcePath = "@isqcookiecheck";

    public $js = [];

    public $css = [];

    /**
     * @var array
     */
    public $publishOptions = [
        'forceCopy' => true // reloads on every request, development option
    ];

    public function init()
    {
        parent::init();

        // set asset paths
        $this->js[] = 'js/isqCookie.js';
        $this->css[] = 'css/isqCookie.css';
    }


}