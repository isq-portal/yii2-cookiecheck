<?php

namespace IsqPortal\Yii2Cookiecheck;

/** class imports */
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Json;
use IsqPortal\Yii2Cookiecheck\ScwCookie;

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



    /** widget init
     * @return void
     */
    public function init()
    {
        /** parent init call */
        parent::init();

        /** set alias for routing **/
        Yii::setAlias('@isqcookiecheck', dirname(__FILE__));

        /** get the asset bundle instance */
        $this->bundle = Yii::$app->getAssetManager()->getBundle('IsqPortal\Yii2Cookiecheck\ISQCookiecheckAsset');

        /** get asset baseUrl */
        $this->baseUrl = $this->bundle->baseUrl;

        /** get asset basePath */
        $this->basePath = $this->bundle->basePath;

        /** set web root */
        $this->webroot = Yii::getAlias("@webroot");


        /** call register Assets function */
        $this->registerAssets();
    }

    /** run the widget
     * @return mixed
     */
    public function run()
    {
        // require_once "ISQCookie.php";
        $scwCookie = new ISQCookie($this->baseUrl, $this->webroot);
        // return a dummy div tag with cookiecheck id and class
        // return Html::tag('div', 'Cookiecheck-Content', ['id' => 'cookiecheck', 'class' => 'cookiecheck']);
        return $scwCookie->output();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $this->view = $this->getView();
        $this->view->registerJS($this->configJs, View::POS_HEAD);
        $this->view->registerCss($this->configCss);
        ISQCookiecheckAsset::register($this->view);

    }

}