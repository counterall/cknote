<?php

function myAutoLoad($class){
  include_once "./classes/$class".".php";
}

spl_autoload_register('myAutoLoad');