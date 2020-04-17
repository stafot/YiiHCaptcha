YiiHCaptcha 
============
An extension for Yii 1.1 to use hCaptcha validation
Based on hCaptcha API

## Quick Start

You can clone the [github repo](https://github.com/stafot/YiiHCaptcha) and get the full codebase to build the distributive something you want. 

## Dependencies

* 1/ Install Composer

```curl -sS https://getcomposer.org/installer | php ```

* 2/ Install Guzzle

``` php composer.phar install ```

## Installation
* 1/ Download GitHub repo (stafot/YiiHCaptcha) and extract files into a destination folder(extensions folder/vendor folder or any folder in your structure)

* 2/ [Sign up for an hCAPTCHA API key](https://www.hcaptcha.com/). and get the key/secret pair

* 3/ Configure this component in your configuration file (main.php file). The parameters siteKey and secret are required.

```php
'components' => [
    'hCaptcha' => [
        'name' => 'hCaptcha',
        'class' => '<path-to-destination-folder>\YiiHCaptcha\HCaptcha',
        'key' => '<your-key>',
        'secret' => '<your-secret>',
    ],
    ...
```

4/ Add `HCaptchaValidator` in your model, for example:
```php
    public $verifyCode;

    public function rules()
    {
        return [
            ['verifyCode', 'required'],
            ['verifyCode', '<path-to-destination-folder>.YiiHCaptcha.HCaptchaValidator'],
        ];
    }
```

5/ Usage this widget in your view
```php
<?php
$this->widget('<path-to-destination-folder>.YiiHCaptcha.HCaptcha', [
    'model'     => $model,
    'attribute' => 'verifyCode',
]);
?>
```
6/ Use for multiple domain: By default, the hCaptcha is restricted to the specified domain. Use the secure token to request a CAPTCHA challenge from any domain. Adding more attribute `'isSecureToken' => true` to setup for any domain:
```php
<?php
$this->widget('<path-to-destination-folder>.YiiHCaptcha.HCaptcha', [
    'model'     => $model,
    'attribute' => 'verifyCode',
    'isSecureToken' => true,
]);
?>
```
