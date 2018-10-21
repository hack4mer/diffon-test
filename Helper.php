<?php
class Helper{  

  private $insight_data;

  function __construct(){
    $this->insight_data = [];
  }

  public function extract_insight($diff,$root="/"){

    if($root=="/"){
        $this->insight_data = [];
    }

    foreach ($diff as $key => $value) {

      if($key=="only_in_source" || $key=="only_in_destination" || $key=="not_same"){
    
        foreach ($value as $k => $v) {

          if(is_array($v)){

            $this->extract_insight($v,$root.$diff['in_both'][$k]."/");

          }else{
            $this->insight_data[$key][] = $root.$v;
          }
        }

      }
    }

    $x = $this->insight_data;
    return $x;
  }  
}
?>