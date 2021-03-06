<?php
namespace Zeus\Facebook;

use Zeus\Facebook\ZeusFacebook;

class Insights extends ZeusFacebook{

  private $data = [];
  private $paging = [];
  private $error = null;
  public function __construct(){
    parent::__construct();


  }
  /* PUBLIC FUNCTIONS */
  public function spends($date = "last_30d", $sort = []){
    $this->call($date, $sort, "");
    return $this->toArray();
  }
  public function campaigns($date = "last_30d", $sort = []){
    $this->call($date, $sort, "campaign");
    return $this->toArray();
  }

  /* CALLS AND RETURNS */
  private function call($date, $sort, $level = "", $fields = ""){
    $url = "act_".$this->getAdAccount()."/insights";
    if(empty($fields)){
      $fields = "clicks,cpc,impressions,inline_link_click_ctr,unique_ctr,website_purchase_roas,spend,social_spend,actions,action_values";
    }

    $fields = array(
      'level' => $level,
      'sort' => $sort,
      'fields' => $fields
    );

    //date preset or range
    if(is_array($date)){
        $fields['time_range'] = $date;
        $fields['time_increment'] = 1; //separa dia a dia
       // ['since' => 'YYYY-MM-DD', 'until' => 'YYYY-MM-DD']
    }else{
      $fields['date_preset'] = $date;
    }


    $resp = $this->curl($url, $fields);
    // dd($resp);
    $this->error = null;
    if(isset($resp['error'])){
      $this->error = $resp['error'];
    }
    foreach ($resp as $key => $value) {
      $this->{$key} = $value;
    }
  }
  private function toArray(){
    if($this->error != null){
      return $this->error;
    }
    return [
      'data' => $this->data,
      'paging' => $this->paging
    ];
  }
}
