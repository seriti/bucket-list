<?php 
namespace App\Bucket;

use Seriti\Tools\Table;

class BucketNote extends Table 
{
    protected $location_base = 'BKT';

    //configure
    public function setup($param = []) 
    {
        $param = ['row_name'=>'Bucket note','col_label'=>'date','pop_up'=>true];
        parent::setup($param);        
                       
        //NB: specify master table relationship
        $this->setupMaster(array('table'=>TABLE_PREFIX.'bucket','key'=>'bucket_id','child_col'=>'location_id','child_prefix'=>$this->location_base, 
                                 'show_sql'=>'SELECT CONCAT("Bucket: ",name) FROM '.TABLE_PREFIX.'bucket WHERE bucket_id = "{KEY_VAL}" '));  

        
        $this->addTableCol(array('id'=>'note_id','type'=>'INTEGER','title'=>'Note ID','key'=>true,'key_auto'=>true,'list'=>false));
        $this->addTableCol(array('id'=>'note','type'=>'TEXT','title'=>'Note text'));
        $this->addTableCol(array('id'=>'date_create','type'=>'DATE','title'=>'Note date','new'=>date('Y-m-d')));

        $this->addSortOrder('T.date_create, T.note_id ','Note date','DEFAULT');

        $this->addAction(array('type'=>'edit','text'=>'edit'));
        $this->addAction(array('type'=>'delete','text'=>'delete','pos'=>'R'));

        $this->addSearch(array('note','date_create'),array('rows'=>2));
    }    
}

?>
