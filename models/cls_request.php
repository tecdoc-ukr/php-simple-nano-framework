<?php
/************************************************************
 * Валидация (проверка/коррекция) входных параметров
 *  17.01.2019
 ************************************************************/

class Request{
  private $rules = array();      // array $CNF_request
  private $params = array();     // array of available and validated $_REQUEST


	//~~~~~~~~~~~~~~~~~~~~~~~~
  // $cnf_rules - array $CNF_request
	//~~~~~~~~~~~~~~~~~~~~~~~~
  function __construct($rules=array()) {
    $this->rules = $rules;
    $this->create_parameters();
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
  // Валидация параметров $_REQUEST по правилу $this->rules
	//~~~~~~~~~~~~~~~~~~~~~~~~
  private function create_parameters(){
    // перебор всех возможных параметров со своими правилами валидации
    foreach($this->rules as $prm => $rule){
      if(!isset($_REQUEST[$prm])) continue;
      
      // Если отсутствует метод валидации - параметр пропускается
      $validate_method = !empty($rule['method']) ? $rule['method'] : 'validate_word';
      if(!method_exists($this, $validate_method)) continue;

      // валидация значения параметра
      $val = $_REQUEST[$prm];
      $val = self::$validate_method($val, $rule);
      $this->params[$prm] = $val;
    }
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Получить значение параметра
  //    prm - имя входного параметра
  //    def - значение по умолчанию если параметр отсутствует в $this->params
	//~~~~~~~~~~~~~~~~~~~~~~~~
  public function get($prm='', $def=''){
    $res = isset($this->params[$prm]) ? $this->params[$prm] : $def;
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
    
    // изменить регистр
    if(isset($prm['case'])){
      if($prm['case'] == 'U' or $prm['case'] == 'u'){
        $val = strtoupper($val);
      }
      if($prm['case'] == 'L' or $prm['case'] == 'l'){
        $val = strtolower($val);
      }
    }
      
    return $val;
  }
  
}
