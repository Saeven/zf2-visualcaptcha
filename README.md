zf2-visualcaptcha
=================

Zend Framework 2 Module for VisualCaptcha.net's slick CAPTCHA.  If you haven't checked it out yet, first go to http://visualcaptcha.net.  Nice right!


>I implemented this after a run with a tough crowd over Google's Recaptcha. This was really well received.  Kudos to the guys at emotionloop.


So - want to use visualcaptcha in your ZF2 app?  Require this module in your composer.json, update, and then add 'Circlical\VisualCaptcha' to your application.config.php


### Why Use This? ###

Simple.  You want visualcaptcha, and you want to save some time implementing translation, validation, view helpers, etc.



### Usage ###

Usage is very straightforward:


```
/*
 * CAPTCHA
 */

/** @var \Circlical\VisualCaptcha\Form\Captcha\VisualCaptcha $visualcaptcha */
$visualcaptcha = $sm->get('VisualCaptcha');
$captcha = new Element\Captcha( 'captcha' );
$captcha
    ->setCaptcha( $visualcaptcha )
         ->setLabel( 'Please complete the challenge below' );

$this->add( $captcha );
```

### Extra Stuff ###

* The module is translation ready, in other words, if you have your po files ready and have _( ) configured as a gettext extractor, your update run will pull all the strings that require translation, and use them at runtime.
* Strongly recommend using this with assetic.  It's already all preconfigured to copy files over from the source visualcaptcha package into your public space (the photos that are shown on the form itself)
* I am currently using this with Twig, and it works very well!


### Won't Do ###

* The audio isn't yet "translated".  It's quite the job.  If you look at Module.php though, you'll see that I prepped the keys for you.  Just need to redefine the VisualCaptchaAudio factory.

