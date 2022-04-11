<?php
namespace App\Bucket;

use Exception;
use Seriti\Tools\Table;
use Seriti\Tools\Secure;
use Seriti\Tools\Form;
use Seriti\Tools\Validate;

class Bucket extends Table 
{
    protected $access_rank = 100;
    protected $location_base = 'BKT';

    //configure
    public function setup($param = []) 
    {
        $param = ['row_name'=>'Bucket','col_label'=>'name'];
        parent::setup($param);

        $config = $this->getContainer('config');

        $user_access = $config->get('user','access');

        //$this->user_access_level and $this->user_id set in parent::setup() above
        if(isset(ACCESS_RANK[$this->user_access_level])) $this->access_rank = ACCESS_RANK[$this->user_access_level];

        $access_select = [];
        foreach($user_access as $access) {
            $name = $access;
            if($name === 'GOD') $name = 'MASTER';
            if(ACCESS_RANK[$access] >= $this->access_rank) {
                $access_select[$access] = $name;   
            }
        }
     
        

        $this->addTableCol(array('id'=>'bucket_id','type'=>'INTEGER','title'=>'Bucket ID','key'=>true,'key_auto'=>true,'list'=>false));
        $this->addTableCol(array('id'=>'name','type'=>'STRING','title'=>'Bucket name'));
        $this->addTableCol(array('id'=>'description','type'=>'TEXT','title'=>'Description','required'=>false));
        $this->addTableCol(array('id'=>'date_create','type'=>'DATE','title'=>'Created on','edit'=>false));
        $this->addTableCol(array('id'=>'access','type'=>'STRING','title'=>'Access rights'));
        $this->addTableCol(array('id'=>'status','type'=>'STRING','title'=>'Status'));


        $this->addSortOrder('T.`date_create` DESC','Create date DESC','DEFAULT');

        $this->addSql('WHERE','T.`access_level` >= "'.$this->access_rank.'" ');


        $this->setupFiles(array('table'=>TABLE_PREFIX.'file','location'=>$this->location_base,'max_no'=>1000,
                                'icon'=>'<img src="/images/folder.png" border="0">manage',
                                'list'=>true,'list_no'=>100,'search'=>true,
                                'link_url'=>'bucket_file','link_data'=>'SIMPLE','width'=>'800','height'=>'720'));

        $this->addAction(array('type'=>'edit','text'=>'edit','icon'=>false,'verify'=>true));
        //$this->addAction(array('type'=>'view','text'=>'view','icon'=>false));
        $this->addAction(array('type'=>'delete','text'=>'delete','pos'=>'R','icon'=>false,'verify'=>true));
        $this->addAction(array('type'=>'popup','text'=>'Notes','url'=>'bucket_note','mode'=>'view','width'=>600,'height'=>600)); 
       
        $this->addSearch(array('name','description','access','date_create'),array('rows'=>2));

        $this->addSelect('status','(SELECT "OK") UNION (SELECT "HIDE")');
        $this->addSelect('access',['list'=>$access_select,'list_assoc'=>true]);
    }

    protected function modifyRowValue($col_id,$data,&$value) {
        if($col_id === 'access') {
            if($value === 'GOD') {
                $value = 'MASTER only';
            } else {
                $value .= ' & higher';    
            }    
        }    
    } 
    

    protected function verifyRowAction($action,$data) {
        $valid = true;
        
        //if($data['user_id'] === $this->user_id or $this->user_access_level === 'GOD') $valid = true;
        //echo 'userID'.$data['user_id'].'<br/>';
        return $valid;
    }
    
    protected function beforeDelete($id,&$error) 
    {
        $error_tmp = '';

        $location_id = $this->location_base.$id;
        
        $sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'file` '.
               'WHERE `location_id` = "'.$this->db->escapeSql($location_id).'" ';
        $count = $this->db->readSqlValue($sql);
        if($count != 0) $error_tmp .= $count.' documents found in bucket. ';

        $sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'note` '.
               'WHERE `location_id` = "'.$this->db->escapeSql($location_id).'" ';
        $count = $this->db->readSqlValue($sql);
        if($count != 0) $error_tmp .= $count.' notes found in bucket.';

        if($error_tmp !== '') $error = 'Cannot delete as: '.$error_tmp.' You need to delete all related data first.';
    }

    protected function afterUpdate($id,$edit_type,$form) {
              
        if($edit_type === 'INSERT') {
            $sql = 'UPDATE `'.$this->table.'` SET `date_create` = CURDATE() , `access_level` = "'.ACCESS_RANK[$form['access']].'" '.
                   'WHERE `'.$this->key['id'].'` = "'.$this->db->escapeSql($id).'" ';
        } else {
            $sql = 'UPDATE `'.$this->table.'` SET `access_level` = "'.ACCESS_RANK[$form['access']].'" '.
                   'WHERE `'.$this->key['id'].'` = "'.$this->db->escapeSql($id).'" ';
        }

        $this->db->executeSql($sql,$error_tmp);  
         
    } 

    
} 