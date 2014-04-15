<?php

namespace Circlical\VisualCaptcha;

use Circlical\VisualCaptcha\Form\Captcha\VisualCaptcha;
use LDP\Core;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\StandardConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module
{

    public function onBootstrap(MvcEvent $event)
    {
        if (PHP_SAPI === 'cli' )
            return;

        $app                = $event->getApplication();
        $sm                 = $app->getServiceManager();

        //$sm->get('CaptchaSession');
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'circlical/visualcaptcha' => function( $sm ){

                    $config = $sm->getServiceLocator()->get('config');
                    $config = $config['circlical']['visualcaptcha'];

                    if( !empty( $config['translator'] ) && ($tr = $sm->getServiceLocator()->get( $config['translator'] ) ) && $tr instanceof Translator )
                    {
                        $config['language'] = array(
                            'accessibilityAlt'          => $tr->translate( "Sound icon" ),
                            'accessibilityTitle'        => $tr->translate( "Accessibility option: listen to a question and answer it!" ),
                            'accessibilityDescription'  => $tr->translate( "Type below the <strong>answer</strong> to what you hear. Numbers or words:" ),
                            'explanation'               => $tr->translate( "Click or touch the <strong>ANSWER</strong>" ),
                            'refreshAlt'                => $tr->translate( "Refresh/reload icon" ),
                            'refreshTitle'              => $tr->translate( "Refresh/reload: get new images and accessibility option!" ),
                        );
                    }

                    return new \Circlical\VisualCaptcha\Form\View\Helper\VisualCaptchaHelper( $config );
                }
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . "/../../../autoload_classmap.php"
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(

            'factories' => array(

                'VisualCaptchaImages' => function( $sm ){
                    $config  = $sm->get('config');
                    $config  = $config['circlical']['visualcaptcha'];

                    if( empty( $config['images'] ) )
                        return null;

                    if( isset( $config['translator'] ) )
                    {
                        $tr = $sm->get($config['translator'] );

                        foreach( $config['images'] as $id => $a )
                            $config['images'][$id]['name'] = $tr->translate( $a['name'] );
                    }

                    return $config['images'];
                },


                'VisualCaptchaAudio' => function( $sm ){
                    $config  = $sm->get('config');
                    $config  = $config['circlical']['visualcaptcha'];

                    if( empty( $config['audio'] ) )
                        return array();

                    // @TODO: Deal with audio and i18n

                    return $config['audio'];
                },


                'VisualCaptcha' => function( $sm ){
                    $session = $sm->get('CaptchaSession');
                    return new VisualCaptcha(
                        $session,
                        $sm->get('VisualCaptchaImages'),
                        $sm->get('VisualCaptchaAudio')
                    );
                },


                'CaptchaSession' => function( $sm ){
                    $opts =  array(
                        'use_cookies'       => true,
                        'use_only_cookies'  => true,
                        'cookie_httponly'   => false,
                    );

                    $config         = $sm->get('config');
                    $config         = $config['circlical']['visualcaptcha'];
                    $session_config = new StandardConfig();

                    session_name( $config['session']['name'] );
                    $session_config->setOptions( $opts );
                    $sessionManager = new SessionManager($session_config);

                    if( getenv('APPLICATION_ENV') == 'production' && class_exists( 'Aws\Session\SaveHandler\DynamoDb' ))
                    {
                        $saveHandler    = $sm->get('Aws\Session\SaveHandler\DynamoDb');
                        $sessionManager->setSaveHandler($saveHandler);
                    }

                    $sessionManager->start();
                    $session_container  = new Container('captcha');
                    $session = new CaptchaSession( $session_container, $config['session']['name'] );
                    return $session;
                },
            ),

        );
    }

}
