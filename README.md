# Yii2 Cookiecheck

Cookiecheck is a Yii2 Extension for the ISQ Custom Cookie Settings Check to reach GDPR/DSGVO compliance.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```bash
php composer.phar require --prefer-dist isq-portal/yii2-cookiecheck "dev-master"
```

or add

```bash
"isq-portal/yii2-cookiecheck": "dev-master"
```

to the require section of your `composer.json` file.

## Usage

```php
# add to composer.json
"require-dev" : {
		...,
		"isq-portal/yii2-cookiecheck": "@dev"
	}

# temporary dev local path (../) solution:
"repositories": [
		...,
		{
			"type": "path",
			"url": "../yii2-cookiecheck"
		}
	],

# composer update

# import class to view
use IsqPortal\Yii2Cookiecheck\ISQCookiecheck;

# integrate widget to view with options., e.g.:
<? echo ISQCookiecheck::widget(['options' => [
                    'cookiePolicyURL' => 'site/privacy',
                                    'panelTogglePosition' => 'left',
                                    'unsetDefault' => 'blocked',
                                    'Matomo' => [
                                                'enabled' => '1',
                                                'label' => 'Webanalyse (Matomo)',
                                                'code' => ''
                                        ]
                                    ]
                                ]); 
```

## License
[MIT](https://choosealicense.com/licenses/mit/)