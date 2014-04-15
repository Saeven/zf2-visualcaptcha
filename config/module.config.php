<?php


/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(

    'circlical' => array(

        'visualcaptcha' => array(
            'imgPath' => '/assets/images/captcha/',
            'captcha' => array(
                'numberOfImages' => 8,
                'routes' => array(
                    'start' => 'captcha/start',
                    'image' => 'captcha/image',
                    'audio' => 'captcha/audio',
                ),
            ),

            // translator service, will call translator->translate( ) on this service name
            // leave it blank if not required
            'translator' => 'translator',

            'session' => array(
                'name' => 'launchfire',
            ),

            'images' => array(
                array( 'name' => _( "Airplane" ), 'path' => 'airplane.png' ),
                array( 'name' => _( "Balloons" ), 'path' => 'balloons.png' ),
                array( 'name' => _( "Camera" ), 'path' => 'camera.png' ),
                array( 'name' => _( "Car" ), 'path' => 'car.png' ),
                array( 'name' => _( "Cat" ), 'path' => 'cat.png' ),
                array( 'name' => _( "Chair" ), 'path' => 'chair.png' ),
                array( 'name' => _( "Clip" ), 'path' => 'clip.png' ),
                array( 'name' => _( "Clock" ), 'path' => 'clock.png' ),
                array( 'name' => _( "Cloud" ), 'path' => 'cloud.png' ),
                array( 'name' => _( "Computer" ), 'path' => 'computer.png' ),
                array( 'name' => _( "Envelope" ), 'path' => 'envelope.png' ),
                array( 'name' => _( "Eye" ), 'path' => 'eye.png' ),
                array( 'name' => _( "Flag" ), 'path' => 'flag.png' ),
                array( 'name' => _( "Folder" ), 'path' => 'folder.png' ),
                array( 'name' => _( "Foot" ), 'path' => 'foot.png' ),
                array( 'name' => _( "Graph" ), 'path' => 'graph.png' ),
                array( 'name' => _( "House" ), 'path' => 'house.png' ),
                array( 'name' => _( "Key" ), 'path' => 'key.png' ),
                array( 'name' => _( "Leaf" ), 'path' => 'leaf.png' ),
                array( 'name' => _( "Light Bulb" ), 'path' => 'light-bulb.png' ),
                array( 'name' => _( "Lock" ), 'path' => 'lock.png' ),
                array( 'name' => _( "Magnifying Glass" ), 'path' => 'magnifying-glass.png' ),
                array( 'name' => _( "Man" ), 'path' => 'man.png' ),
                array( 'name' => _( "Music Note" ), 'path' => 'music-note.png' ),
                array( 'name' => _( "Pants" ), 'path' => 'pants.png' ),
                array( 'name' => _( "Pencil" ), 'path' => 'pencil.png' ),
                array( 'name' => _( "Printer" ), 'path' => 'printer.png' ),
                array( 'name' => _( "Robot" ), 'path' => 'robot.png' ),
                array( 'name' => _( "Scissors" ), 'path' => 'scissors.png' ),
                array( 'name' => _( "Sunglasses" ), 'path' => 'sunglasses.png' ),
                array( 'name' => _( "Tag" ), 'path' => 'tag.png' ),
                array( 'name' => _( "Tree" ), 'path' => 'tree.png' ),
                array( 'name' => _( "Truck" ), 'path' => 'truck.png' ),
                array( 'name' => _( "T-Shirt" ), 'path' => 't-shirt.png' ),
                array( 'name' => _( "Umbrella" ), 'path' => 'umbrella.png' ),
                array( 'name' => _( "Woman" ), 'path' => 'woman.png' ),
                array( 'name' => _( "World" ), 'path' => 'world.png' ),
            )
        ),
    ),



    /**
     * Controller
     */

    'controllers' => array(
        'invokables' => array(
            'Captcha' => 'Circlical\VisualCaptcha\Controller\CaptchaController',
        ),
    ),


    /**
     * Router
     */
    'router' => array(
        'routes' => array(

            'captcha/start' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/captcha/start/[:count]',
                    'defaults' => array(
                        'controller' => 'Captcha',
                        'action' => 'start',
                    ),
                    'constraints' => array(
                        'count' => '[0-9]{1,2}',
                    ),
                ),
            ),

            'captcha/image' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/captcha/image/[:index]',
                    'defaults' => array(
                        'controller' => 'Captcha',
                        'action' => 'image',
                    ),
                    'constraints' => array(
                        'index' => '[0-9]{1,2}',
                    ),
                ),
            ),

            'captcha/audio' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/captcha/audio[/[:type]]',
                    'defaults' => array(
                        'controller' => 'Captcha',
                        'action' => 'audio',
                    ),
                    'constraints' => array(
                        'type' => '(mp3|ogg)',
                    ),
                ),
            ),

            'captcha/try' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/captcha/try',
                    'defaults' => array(
                        'controller' => 'Captcha',
                        'action' => 'try',
                    ),
                ),
            ),
        ),
    ),


    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),



    /**
     * Assetic
     */
    'assetic_configuration' => array(

        'cacheEnabled'      => false,
        'webPath'           => realpath('public/assets/captcha'),
        'basePath'          => 'assets/captcha',
        'combine'           => true,

        /*
         * In this configuration section, you can define which js, and css resources the module has.
         */
        'modules' => array(

            'captcha' => array(

                # module root path for yout css and js files
                'root_path' => __DIR__ . '/../assets',

                # collection of assets
                'collections' => array(

                    'captcha_css' => array(
                        'assets' => array(
                            'css/visualcaptcha.css',
                        ),
                        'options' => array(
                            'output' => 'head_vcaptcha.css',
                        ),
                    ),

                    'captcha_js' => array(
                        'assets' => array(
                            'js/visualcaptcha.jquery.js',
                        ),
                        'options' => array(
                            'output' => 'head_vcaptcha.js',
                        ),
                    ),

                    'captcha_images' => array(
                        'assets' => array(
                            'images/captcha/*.png',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        )
                    ),
                ),
            ),
        ),
    ),

    /**
     * ViewManager
     */
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),


    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),

);
