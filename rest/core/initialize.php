<?php
    define('DS' , DIRECTORY_SEPARATOR);
    define('SITE_ROOT' , $_SERVER['DOCUMENT_ROOT'].DS.'flutter_blog_web_service'.DS.'rest');
    define('INC_PATH' , SITE_ROOT .DS .'includes');
    define('COR_PATH' , SITE_ROOT .DS .'core');
    define('CLASSES_PATH' , SITE_ROOT .DS .'classes');

    require_once(INC_PATH . DS . "config.php");

    require_once(CLASSES_PATH . DS . "user.php")

?>