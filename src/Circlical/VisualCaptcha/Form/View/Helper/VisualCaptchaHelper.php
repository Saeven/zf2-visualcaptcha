<?php

namespace Circlical\VisualCaptcha\Form\View\Helper;

use Zend\Captcha\AbstractAdapter;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormInput;
use Zend\I18n\Translator\Translator;

class VisualCaptchaHelper extends FormInput
{

    private $config;

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    public function __construct( $config  )
    {
        $this->config       = $config;
    }

    /**
     * Render ReCaptcha form elements
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {

        $attributes = $element->getAttributes();
        $captcha = $element->getCaptcha();

        if ($captcha === null || !$captcha instanceof AbstractAdapter) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has a "captcha" attribute implementing Zend\Captcha\AdapterInterface; none found',
                __METHOD__
            ));
        }

        $name           = $element->getName();
        $id             = isset($attributes['id']) ? $attributes['id'] : $name;
        $markup         = $this->renderHtml($id, $name);
        $js             = $this->renderJsEvents( $id, $element->getAttribute('base_url') );

        return $markup . $js;
    }


    protected function renderHtml( $id, $name )
    {
        return "<div id='{$id}'></div><input type='hidden' name='$name' value='". hexdec( rand( 10000, 99999 ) )  ."'>";
    }


    /**
     * Create the JS events used to bind the challenge and response values to the submitted form.
     *
     * @param $id
     * @param $base_url
     * @return string
     */
    protected function renderJsEvents($id, $base_url)
    {
        if( !$base_url )
            $base_url = '/';

        $this->config['captcha']['url'] = $base_url;

        return "
        <script type='text/javascript'>
            $(document).ready( function(){
                var el = $( '#{$id}' ).visualCaptcha(" . json_encode( $this->config ) . ");
            });
        </script>
        ";
    }
}
