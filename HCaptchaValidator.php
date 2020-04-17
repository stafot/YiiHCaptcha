<?php
/**
 * HCaptchaValidator class file
 * Description of HCaptcha
 *
 * @author Stavros Foteinopoulos <stavros@peopleperhour.com>
 */

class HCaptchaValidator extends CValidator
{
    const SITE_VERIFY_URL        = 'https://hcaptcha.com/siteverify';
    const CAPTCHA_RESPONSE_FIELD = 'h-captcha-response';

    public $secret;

    /**
     * Constructor (CValidator does not have an init() function)
     */
    public function __construct()
    {
        // note: validator has no parent::__construct()
        $this->init();
    }

    public function init()
    {
        if (empty($this->secret)) {
            if (!empty(Yii::app()->hCaptcha->secret)) {
                $this->secret = Yii::app()->hCaptcha->secret;
            } else {
                throw new InvalidConfigException('Required `secret` param isn\'t set.');
            }
        }
        if ($this->message === null || empty($this->message)) {
            $this->message = Yii::t('yii', ' Please click on the checkbox to continue.');
        }
    }

    /**
     * Validate hcaptcha
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return mixed
     * @throws CException
     */
    protected function validateAttribute($object, $attribute)
    {
        // get input value
        $value = $object->$attribute;
        if (empty($value)) {
            if (!($value = Yii::app()->request->getParam(self::CAPTCHA_RESPONSE_FIELD))) {
                $message = $this->message;
                $this->addError($object, $attribute, $message);
                return;
            }
        }

        $client = new GuzzleHttp\Client();
        $response = $client->post(
            self::SITE_VERIFY_URL,
            ['body'=> [
                'secret'   => $this->secret,
                'response' => $value,
                'remoteip' => Yii::app()->request->getUserHostAddress(),
            ]]);
        $body = json_decode((string)$response->getBody());

        if (!isset($body->success)) {
            throw new CException('Invalid hcaptcha verify response.');
        }
        if (!$body->success) {
            $message = $this->message;
            $this->addError($object, $attribute, $message);
        }
    }

    /**
     *
     * Validate hcaptcha
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return string
     */
    public function clientValidateAttribute($object, $attribute)
    {
        $message = $this->message !== null ? $this->message : Yii::t('yii', 'Please click on the checkbox to continue.');
        return "(function(messages){if(!ghcaptcha.getResponse()){messages.push('{$message}');}})(messages);";
    }
}