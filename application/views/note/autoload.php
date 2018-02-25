<?php  


function myAutoLoad($class){
  include_once "./classes/$class".".php";
}

function myAutoLoad2($class){
  include_once "./classes/Indextank/$class".".php";
}

spl_autoload_register('myAutoLoad');
spl_autoload_register('myAutoLoad2');
