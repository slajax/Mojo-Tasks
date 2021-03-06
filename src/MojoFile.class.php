<?php

/**
 * File Manipulation Class
 *
 * @package    mojo
 * @author     Kyle Campbell
 */

class MojoFile extends Mojo
{
  function __construct($args){
    $this->args = $args;
  }
  
  static function editStream($args,$stream)
  {
    if(empty($args)) return Mojo::prompt("Cannot edit stream, no args found");
    foreach($args as $k => $v) $stream = str_replace("%".strtoupper($k)."%",trim($v),$stream);
    return (string)$stream;
  }

  static function write($file, $string)
  {
    $fp = fopen($file,"w");
    for ($written = 0; $written < strlen($string); $written += $fwrite) {
        $fwrite = fwrite($fp, substr($string, $written));
        if (!$fwrite) {
            return $fwrite;
        }
    }
    return $written;
  }
  
  static function getAll($dir = false)
  {
    if($dir===false) return $dir;
    $listDir = array();
    if($handler = opendir($dir)) {
        while (($sub = readdir($handler)) !== FALSE) {
            if ($sub != '.' && $sub != '..' && $sub != '.svn') {
                if(is_file($dir."/".$sub)) {
                    $listDir[] = $sub;
                }elseif(is_dir($dir."/".$sub)){
                    $listDir[$sub] = self::getAll($dir."/".$sub);
                }
            }
        }   
        closedir($handler);
    }
    return $listDir;   
  } 
  
  static function makeNewFile($name,$type)
  { 
    $path = MojoConfig::get('mojo_js_dir').$type.DIRECTORY_SEPARATOR;   
    $arr = explode($type.'.',$name);
    array_shift($arr);
    $dirs = explode('.',$arr[0]);
    $file = trim(array_pop($dirs));
    foreach($dirs as $dir){
      $path .= $dir.DIRECTORY_SEPARATOR;
      if(!is_dir($path)) mkdir($path);
    }
    
    return $path.$file.'.js';
  }
}

?>
