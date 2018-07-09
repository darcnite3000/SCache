<?php
/**
* SCachce
*/
class SCachce{
  public $folder = __DIR__;
  public $namespace = "";
  public $duration = 86400;

  function __construct($namespace = "", $duration = 86400, $folder = __DIR__){
    $this->folder = realpath($folder);
    $this->namespace = $namespace;
    $this->duration = intval($duration);
  }

  public function last_modified($key){
    clearstatcache();
    return filemtime($this->filename($key));
  }

  public function is_recent($filename){
    clearstatcache();
    return filemtime($filename) >= (time() - $this->duration);
  }

  public function filename($key){
    return $this->folder.'/'.$this->namespace.'-'.$key.'.cache';
  }

  public function set($key,$data){
    $filename = $this->filename($key);
    $r = fopen($filename, 'w+');
    if($r !== false){
      fwrite($r,$data);
      fclose($r);
      touch($filename);
      return true;
    }
    return false;
  }
  public function get($key){
    $filename = $this->filename($key);
    if(file_exists($filename) && $this->is_recent($filename)){
      $r = fopen($filename, 'r');
      $data = fread($r, filesize($filename));
      fclose($r);
      return $data;
    }
    return false;
  }
}
