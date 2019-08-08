<?php 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// НАСТРОЙКИ
//------------------------------------
require('configuration.php');


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// МАРШРУТИЗАЦИЯ
//------------------------------------
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : $controller_index;
$action_path = $controllers_dir.$action.'.php';

// проверим наличие файла контроллера
if(!file_exists($action_path)){
  $action_path = $controllers_dir.$controller_index.'.php';

  // отсувует контроллер
  if(!file_exists($action_path)){
    die($error_msg);
  }
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ЗАПУСК
//------------------------------------
require($views_dir.'header.html');
require($action_path);
require($views_dir.'footer.html');
?>