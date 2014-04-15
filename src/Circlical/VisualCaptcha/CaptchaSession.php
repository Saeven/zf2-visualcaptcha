<?php

/**
,,
`""*3b..											
     ""*3o.					  						4/7/14 2:00 PM
         "33o.			                  			Alexandre Lemaire
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

namespace Circlical\VisualCaptcha;

use Zend\Session\Container;

class CaptchaSession extends \visualCaptcha\Session
{
    private $namespace = '';

    /**
     * @var \Zend\Session\Container
     */
    private $session;

    public function __construct( Container $session_container, $namespace = 'visualcaptcha' ) {
        $this->namespace = $namespace;
        $this->session   = $session_container;
    }

    public function clear() {
        /* @TODO parameter for session container name */
        $this->session->getManager()->getStorage()->clear($this->namespace);
    }

    public function get( $key ) {
        if( isset( $this->session->$key ) )
            return $this->session->$key;

        return null;
    }

    public function set( $key, $value ) {
        $this->session->$key = $value;
    }
};