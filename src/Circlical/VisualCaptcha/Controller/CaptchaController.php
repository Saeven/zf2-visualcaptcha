<?php

/**
,,
`""*3b..											
     ""*3o.					  						10/22/13 2:12 PM
         "33o.			                  			Alexandre Lemaire
           "*33o.                                   Circlical
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


namespace Circlical\VisualCaptcha\Controller;

use Circlical\VisualCaptcha\CaptchaSession;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class CaptchaController extends AbstractActionController
{
    public function startAction()
    {

        $howMany = $this->params()->fromRoute('count');
        $session = $this->getServiceLocator()->get('CaptchaSession');
        $config  = $this->getServiceLocator()->get('config');
        $config  = $config['circlical']['visualcaptcha'];
        $captcha = new \visualCaptcha\Captcha( $session, null, $this->getServiceLocator()->get('VisualCaptchaImages'), isset( $config['audio'] ) ? $config['audio'] : null );
        $captcha->generate( $howMany );
        return new JsonModel( $captcha->getFrontendData() );
    }

    public function audioAction(){
        $session = $this->getServiceLocator()->get('CaptchaSession');
        $config  = $this->getServiceLocator()->get('config');
        $config  = $config['circlical']['visualcaptcha'];
        $captcha = new \visualCaptcha\Captcha( $session, null, $this->getServiceLocator()->get('VisualCaptchaImages'), isset( $config['audio'] ) ? $config['audio'] : null );

        $fileType       = $this->params()->fromRoute( 'type' );
        if( $fileType )
            $fileType = 'mp3';

        $audioOption    = $captcha->getValidAudioOption();
        $audioFileName  = $audioOption ? $audioOption[ 'path' ] : '';
        $audioFilePath  =  getcwd() . '/vendor/emotionloop/visualcaptcha/src/visualCaptcha/assets/audios/' . $audioFileName;

        if ( $fileType === 'ogg' )
           $audioFilePath = preg_replace( '/\.mp3/i', '.ogg', $audioFilePath );

        $mimeType       = mime_content_type( $audioFilePath );
        $audioContent   = file_get_contents( $audioFilePath );
        $response       = $this->getResponse();

        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $mimeType )
            ->addHeaderLine('Content-Length', mb_strlen($audioContent))
            ->addHeaderLine('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->addHeaderLine('Pragma', 'no-cache' )
            ->addHeaderLine('Expires', "@0" );

        $response->setContent( $audioContent );

        return $response;

    }

    public function imageAction(){
        $session = $this->getServiceLocator()->get('CaptchaSession');
        $config  = $this->getServiceLocator()->get('config');
        $config  = $config['circlical']['visualcaptcha'];
        $captcha = new \visualCaptcha\Captcha( $session, null, $this->getServiceLocator()->get('VisualCaptchaImages'), isset( $config['audio'] ) ? $config['audio'] : null );
        $index   = $this->params()->fromRoute('index');

        $imageOption    = $captcha->getImageOptionAtIndex( $index );
        $imageFileName  = $imageOption ? $imageOption[ 'path' ] : '';
        $imageFilePath  =  getcwd() . '/vendor/emotionloop/visualcaptcha/src/visualCaptcha/assets/images/' . $imageFileName;

        // Force boolean for isRetina
        $isRetina = $this->params()->fromQuery('retina') >= 1;

        // If retina is requested, change the file name
        if ( $isRetina )
            $imageFilePath = preg_replace( '/\.png/i', '@2x.png', $imageFilePath );

        if ( !file_exists( $imageFilePath ) )
        {
            die( "No image at path $imageFilePath" );
            return "";
        }

        $mimeType       = mime_content_type( $imageFilePath );
        $imageContent   = file_get_contents( $imageFilePath );
        $response       = $this->getResponse();

        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $mimeType )
            ->addHeaderLine('Content-Length', mb_strlen($imageContent))
            ->addHeaderLine('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->addHeaderLine('Pragma', 'no-cache' )
            ->addHeaderLine('Expires', "@0" );

        $response->setContent( $imageContent );

        return $response;
    }
}
