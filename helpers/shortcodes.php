<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Create shortcode for Show Wall in profile page
*
*/
if ( !function_exists( 'shortcodeQuestionAW' ) ) {
  function shortcodeQuestionAW($atts) 
  {
    $attributes = shortcode_atts(array('id_vendor' => 0, 'show_pagination' => 'yes'), $atts);
    $question = new AW_Questions($attributes['id_vendor'], $attributes['show_pagination']);
  }
}

if ( !function_exists( 'addShortCodeQuestionAW' ) ) {
  function addShortCodeQuestionAW() 
  {
    add_shortcode( 'front-questions-wall', 'shortcodeQuestionAW' );
  }
}

add_action( 'init', 'addShortCodeQuestionAW' );

/*
* Create shortcode for Show Questions in user vendor page
*
*/

if ( !function_exists( 'shortcodeAllMyQuestions' ) ) {
  function shortcodeAllMyQuestions() 
  {
    $allMyQuestions = new AllMyQuestions();
  }
}

if ( !function_exists( 'addShortCodeAllMyQuestions' ) ) {
  function addShortCodeAllMyQuestions() 
  {
    add_shortcode( 'front-all-my-questions', 'shortcodeAllMyQuestions' );
  }
}

add_action( 'init', 'addShortCodeAllMyQuestions' );

/*
* Create shortcode for Show Information of Guie
*
*/

if ( !function_exists( 'shortcodeShowInormationGuie' ) ) {
  function shortcodeShowInormationGuie() 
  {
    $showInormationGuie = new ShowInormationGuie();
  }
}

if ( !function_exists( 'addShortCodeShowInormationGuie' ) ) {
  function addShortCodeShowInormationGuie() 
  {
    add_shortcode( 'front-show-inormation-guie', 'shortcodeShowInormationGuie' );
  }
}

add_action( 'init', 'addShortCodeShowInormationGuie' );
