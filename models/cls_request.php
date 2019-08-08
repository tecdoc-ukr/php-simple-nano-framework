<?php
/************************************************************
 * Валидация (проверка/коррекция) входных параметров
 *  29.01.2018
 ************************************************************/

class Request{
  private $rules = array();      // array $CNF_request


	//~~~~~~~~~~~~~~~~~~~~~~~~
  // $cnf_rules - array $CNF_request
	//~~~~~~~~~~~~~~~~~~~~~~~~
  function __construct($rules=array()) {
    $this->rules = $rules;
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Сформировать строку "URL-параметры" из полученных допустимых параметров и валидных значений
	//  СУТЬ: Объект класа знает список допустимых параметров $rules, поэтому он может сформировать такой запрос
  //  Необходим для изменения языка интерфейса с сохранением остальных параметров выбора
  //
  //  $escape - какие параметры пропускать в результирующем URL
  //  $need_empty - необходимость включения праметров с пустыми значениями
	//~~~~~~~~~~~~~~~~~~~~~~~~
  public function make_url($escape=array(), $need_empty=false){
    $res = '';
    $del = '';
    foreach($this->rules as $prm => $rule){
      if(in_array($prm, $escape)) continue; // пропускаем ненужные параметры

      $val = $this->get($prm);  // определяем валидное значение
      if(!$need_empty and $val === '') continue; // пропускаем пустые значения
      
      $res .= $del.$prm.'='.$val; 
      $del = '&';
    }
    
    return $res;
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Получить значение параметра
  //
  //  prm - имя входного параметра
  //  def - значение по умолчанию если параметр не был определено в Общих правилах $CNF_request
  //    алгоритм def: $res = isset($_REQUEST[$prm]) ? $_REQUEST[$prm] : $def;
  //    Есди параметр $prm определен в Общих правилах $CNF_request то будет использоваться его $CNF_request['def'], 
	//~~~~~~~~~~~~~~~~~~~~~~~~
  public function get($prm='', $def=''){
    $res = $def;
    
    // проверить существует ли для параметра правило в $CNF_request
    if(!isset($this->rules[$prm])){
      return $res;
    }
    
    // выберем правило для параметра
    $rule = $this->rules[$prm];
    
    // вычитать значение по умолчанию в правиле, если было задано
    if(isset($rule['def'])){
      $res = $rule['def'];
    }

    // проверяем был ли передан парамет
    if(!isset($_REQUEST[$prm])){
      return $res;
    }
    
    // вычитать и проверить метод валидации
    $validate_method = !empty($rule['method']) ? $rule['method'] : 'validate_word';
    if(!method_exists($this, $validate_method)){
      return $res;
    }
    
    // корректируем значение параметра
    $res = $_REQUEST[$prm];
    $res = self::$validate_method($res, $prm);
    
    return $res;
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
  // Допустимы только символы и цифры, Latin
	//~~~~~~~~~~~~~~~~~~~~~~~~
  private static function validate_word($val='', $prm=array()){
    $val = preg_replace('/[^A-Za-z0-9]/', '', $val);
    
    // коррекция длины
    if(isset($prm['lenth'])){
      $length = intval($prm['lenth']);
      $val = substr($val, 0, $length);
    }
    
    // верхний регистр
    if(isset($prm['upper'])){
      $val = strtoupper($val);
    }
    
    // нижний регистр
    if(isset($prm['lower'])){
      $val = strtolower($val);
    }
      
    return $val;
  }
  
}
?>