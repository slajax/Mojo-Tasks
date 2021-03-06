<?php

/**
 * Controller Tasks.
 *
 * @package    mojo
 * @author     Kyle Campbell
 */

class MojoController extends MojoFile
{
  function __construct($args)
  {
      $this->args = $args;
      return $this;
  }

  function Scaffold()
  {

      //Replace this with a validation method
      if(empty($this->args['name'])) 
          return Mojo::prompt('Provide a full mojo path in your params string, ie: name=mojo.controller.myController');

      if(strpos($this->args['name'],'controller.') < 1)
          return Mojo::prompt('The name you provided for your Controller appears to be incorrect. '
                              .'Please use full Controller path, ie: name=mojo.controller.myController');

      $source = self::editStream(array('app_name'=>MojoConfig::get('mojo_app_name')),self::Source());
      $file = self::makeNewFile($this->args['name'],'controller');

      self::write($file,self::editStream($this->args,$source));
      Mojo::prompt('Generated Controller Scaffolding to '.$file);
  }

  function Source()
  {
      ob_start();
return <<<EOF
/*
  Class: %NAME%
  Author: %AUTHOR%
  Description: %DESCRIPTION%
*/

dojo.provide('%NAME%');
dojo.require('mojo.controller.Controller');

dojo.declare('%NAME%', mojo.controller.Controller,
{
  params: {

  },
  addObservers: function() {
    this.addObserver(this, 'onInit', 'do_sample', function(context, caller) { 
			console.log('controller mapped');
      return {

      };
    });
  },
  addCommands: function() {
    this.addCommand('do_sample', '%APP_NAME%.command.SampleCommand');
  },
  addIntercepts: function() {

  }
});
EOF;
      ob_end_flush();
      return ob_get_contents();
  }
}

?>
