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

### Options are:
- 'cookiePolicyURL': path to policy information site a.k.a. "Datenschutzerklärung", relative to webroot, e.g.: 'site/policy'
- 'panelTogglePosition': location for the toggle button to display on the bottom of the window: 'left', 'right' or 'center'
- 'unsetDefault': on page load will cookies be allowed or blocked: 'allowed' or 'blocked'
- cookie-settings array by name of the cookie, e.g.: 'Matomo' & inividual cookie code configs:
- 'enabled': is this cookie to be used on your site? 'true', 'false', '1', ''
- 'label': label for this cookie within the popup, e.g: 'Webanalyse (Matomo)'
- 'code': individual site code, e.g.: Google tracking ID: 'UA-1234567-8' or empty

## License
[MIT](https://choosealicense.com/licenses/mit/)