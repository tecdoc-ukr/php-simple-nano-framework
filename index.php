<?php 
/*************************************************************
Точка входа в MVC:
+) маршрутизирует (подключает) контроллер полученного action, некоректный action перебрасывает на $controller_index
+) формирует всю html-страницу: header.html, menu.html, footer.html
+) автоматическая загрузка View для совего Controller
+) создание класса проверки параметров REQUEST
*************************************************************/


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// НАСТРОЙКИ
//------------------------------------
require('configuration.php');


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ВАЛИДАЦИЯ входных параметров
//------------------------------------
require($CNF_models_dir.'cls_request.php');               
$INX_request = new Request($CNF_request);    


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// МАРШРУТИЗАЦИЯ
//------------------------------------
$controller = isset($_REQUEST['action']) ? $_REQUEST['action'] : $CNF_controller_index;
$controller_path = $CNF_controllers_dir.$controller.'.php';
$view_path = $CNF_views_dir.$controller.'.html';

// проверим наличие файла Контроллера и файла его Представления, 
//  если какого либо файла нету переходим на главную страницу
if(!file_exists($controller_path) or !file_exists($view_path)){
  $controller_path = $CNF_controllers_dir.$CNF_controller_index.'.php';
  $view_path = $CNF_views_dir.$CNF_controller_index.'.html';

  // отсутствует Контроллер или его Представление
  if(!file_exists($controller_path) or !file_exists($view_path)){
    die($CNF_error_msg);
  }
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ЗАПУСК
//------------------------------------
require($CNF_views_dir.'header.html');  // оглавление 
require($CNF_views_dir.'menu.html');    // меню
require($controller_path);              // Контроллер action
require($view_path);                    // Представление action
require($CNF_views_dir.'footer.html');  // завершение
?>