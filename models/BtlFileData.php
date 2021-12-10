<?php

namespace d3yii2\d3btl\models;

use d3yii2\d3btl\models\base\BtlFileData as BaseBtlFileData;

/**
 * This is the model class for table "btl_file_data".
 */
class BtlFileData extends BaseBtlFileData
{
  
  public $in = [];
 
  public function load($data)
  {
    if (!parent::load($data)) {
      return false;
    }
    if($this->file_data && !$ths->processed_data) {
      $this->processFile()
    }
    rweturn true;
  }
  
  public function processFile()
  {

    $this->processed_data = 'aaaa'
    $in = BtlPart()  
    $in->????
    $this->in[] = $in;  

    $out = BtlPart()  
    $out->????
    $this->out[] = $out;  
  }
  
  public function save(???)
  {
    if (!$this->save()){
      return false;
    }
    
    foreach($this->in as $in) {
      $in->file_data_id = $this->id;
         if (!$in->save()){
           return false;
      } 
    }
    foreach($this->out as $out) {
      $out->file_data_id = $this->id;
         if (!$out->save()){
           return false;
      } 
    }
    return true;
  }
  
  public function

}
