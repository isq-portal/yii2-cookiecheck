<?php

namespace IsqPortal\Yii2Cookiecheck;

/** class imports */
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Json;
use IsqPortal\Yii2Cookiecheck\ISQCookie;

/** main ISQCookiecheck class definition */
class ISQCookiecheck extends Widget
{
    /**
     * @var array An array of options that are supported
     */
    public $options = [];

    /** basePath property */
    public $basePath;

    /** configJs property */
    public $configJs;

    /** configCss property */
    public $configCss;

    /** view Property */
    public $view;

    /** bundle property */
    private $bundle;

    /** baseUrl property */
    private $baseUrl;

    /** @var web root property */
    private $webroot;

    /** @var vendorPath property */
    private $vendorPath;

    /** widget init
     * @return void
     */
    public function init()
    {
        /** parent init call */
        parent::init();

        /** get the asset bundle instance */
        $this->bundle = Yii::$app->getAssetManager()->getBundle('IsqPortal\Yii2Cookiecheck\ISQCookiecheckAsset');

        /** get asset baseUrl */
        $this->baseUrl = $this->bundle->baseUrl;

        /** get asset basePath */
        $this->basePath = $this->bundle->basePath;

        /** set web root */
        $this->webroot = Yii::getAlias("@webroot");

        /** get asset vendorPath */
        $this->vendorPath = Yii::getAlias("@vendor") . '/isq-portal/yii2-cookiecheck';

        /** call register Assets function */
        $this->registerAssets();
    }

    /** run the widget
     * @return mixed
     */
    public function run()
    {
        /** add baseUrl & webroot config to options array */
        $this->options['vendorPath'] = $this->vendorPath;
        $this->options['baseUrl'] = $this->baseUrl;
        $this->options['webroot'] = $this->webroot;
        $isqCookie = new ISQCookie($this->options);
        
        // return cookie output
        return $isqCookie->output();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $this->view = $this->getView();
        if (isset($this->configJs)) {
            $this->view->registerJS($this->configJs, View::POS_HEAD);
        }
        if (isset($this->configCss)) {
            $this->view->registerCss($this->configCss);
        }
        ISQCookiecheckAsset::register($this->view);

    }

}