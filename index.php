<?php 
/*
Самый главный модуль:
+) маршрутизирует (подключает) контроллер полученного action, некоректный action перебрасывает на $controller_index
+) формирует всю html-страницу: header.html, menu.html, footer.html
+) автоматическая загрузка View для совего Controller
*/


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// НАСТРОЙКИ
//------------------------------------
require('configuration.php');


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// МАРШРУТИЗАЦИЯ
//------------------------------------
$controller = isset($_REQUEST['action']) ? $_REQUEST['action'] : $controller_index;
$controller_path = $controllers_dir.$controller.'.php';
$view_path = $views_dir.$controller.'.html';

// проверим наличие файла контроллера
if(!file_exists($controller_path)){
  $controller_path = $controllers_dir.$controller_index.'.php';

  // отсувует контроллер
  if(!file_exists($controller_path)){
    die($error_msg);
  }
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ЗАПУСК
//------------------------------------
require($views_dir.'header.html');
require($views_dir.'menu.html');
require($controller_path);
require($view_path);
require($views_dir.'footer.html');
?>