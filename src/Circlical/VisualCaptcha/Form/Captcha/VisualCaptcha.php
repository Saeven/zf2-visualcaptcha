<?php

/**
,,
`""*3b..											
     ""*3o.					  						4/7/14 10:10 AM
         "33o.			                  			Alexandre Lemaore
           "*33o.                                 	Circlical
              "333o.
                "3333bo...       ..o:
                  "33333333booocS333    ..    ,.
               ".    "*3333SP     V3o..o33. .333b
                "33o. .33333o. ...A33333333333333b
          ""bo.   "*33333333333333333333P*33333333:
             "33.    V333333333P"**""*"'   VP  * "l
               "333o.433333333X
                "*3333333333333AoA3o..oooooo..           .b
                       .X33333333333P""     ""*oo,,     ,3P
                      33P""V3333333:    .        ""*****"
                    .*"    A33333333o.4;      .
                         .oP""   "333333b.  .3;
                                  A3333333333P
                                  "  "33333P"
                                      33P*"
		                              .3"
                                     "
                                     
                                     
*/


namespace Circlical\VisualCaptcha\Form\Captcha;
use Traversable;
use Zend\Captcha\AbstractAdapter;

/**
 * VisualCaptcha Zend Framework 2 adapter
 *
 * @see http://recaptcha.net/apidocs/captcha/
 */
class VisualCaptcha extends AbstractAdapter
{
    /** @var \Circlical\VisualCaptcha\CaptchaSession */
    protected $session;


    const MISSING_VALUE = 'missingValue';
    const ERR_CAPTCHA   = 'errCaptcha';
    const BAD_CAPTCHA   = 'badCaptcha';


    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::MISSING_VALUE => 'You must answer the challenge question',
        self::ERR_CAPTCHA   => "You must answer the challenge question",
        self::BAD_CAPTCHA   => "Sorry, the challenge wasn't correctly answered. Please try again.",
    );

    private $imageConfig;

    private $audioConfig;

    /**
     * Constructor
     */
    public function __construct($session, $imageConfig, $audioConfig)
    {
        parent::__construct();

        $this->session      = $session;
        $this->imageConfig  = $imageConfig;
        $this->audioConfig  = $audioConfig;

    }


    /**
     * Set option
     *
     * If option is a service parameter, proxies to the service. The same
     * goes for any service options (distinct from service params)
     *
     * @param  string $key
     * @param  mixed $value
     * @return ReCaptcha
     */
    public function setOption($key, $value)
    {
        return parent::setOption($key, $value);
    }

    /**
     * Generate captcha
     *
     * @see AbstractAdapter::generate()
     * @return string
     */
    public function generate()
    {
        return "";
    }

    /**
     * Validate captcha
     *
     * @see    \Zend\Validator\ValidatorInterface::isValid()
     * @param  mixed $value
     * @param  mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $captcha = new \visualCaptcha\Captcha( $this->session, null, $this->imageConfig, $this->audioConfig );


        if( !($fed = $captcha->getFrontendData()) )
        {
            $this->error(self::MISSING_VALUE);
            $this->session->clear();
            return false;
        }

        if( isset( $_POST[$fed['imageFieldName']] ) && ($image_answer = $_POST[$fed['imageFieldName']] ) )
        {

            if( $captcha->validateImage( $image_answer ) )
            {
                $this->session->clear();
                return true;
            }
            else
            {
                $this->error(self::BAD_CAPTCHA);
                $this->session->clear();
                return false;
            }

        }
        else if( isset( $_POST[$fed['audioFieldName']] ) && ($audio_answer = $_POST[$fed['audioFieldName']] ) )
        {
            if( $captcha->validateAudio( $audio_answer ) )
            {
                $this->session->clear();
                return true;
            }
            else
            {
                $this->error(self::BAD_CAPTCHA);
                $this->session->clear();
                return false;
            }
        }

        $this->error(self::ERR_CAPTCHA);
        $this->session->clear();
        return false;

    }

    /**
     * Get helper name used to render captcha
     *
     * @return string
     */
    public function getHelperName()
    {
        return "circlical/visualcaptcha";
    }
}
