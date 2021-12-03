<?php
namespace App\Bucket;

use Seriti\Tools\SetupModuleData;

class SetupData extends SetupModuledata
{

    public function setupSql()
    {
        $this->tables = ['bucket','note','file'];

        $this->addCreateSql('bucket',
                            'CREATE TABLE `TABLE_NAME` (
                            `bucket_id` int(11) NOT NULL AUTO_INCREMENT,
                            `name` varchar(64) NOT NULL,
                            `description` text NOT NULL,
                            `date_create` date NOT NULL,
                            `status` varchar(16) NOT NULL,
                            `access` varchar(64) NOT NULL,
                            `access_level` int(11) NOT NULL,
                            `pos_x` int(11) NOT NULL,
                            `pos_y` int(11) NOT NULL,
                            `pos_z` int(11) NOT NULL,
                            `icon_id` int(11) NOT NULL,
                            PRIMARY KEY (`bucket_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'); 

        $this->addCreateSql('note',
                            'CREATE TABLE `TABLE_NAME` (
                                `note_id` int(11) NOT NULL AUTO_INCREMENT,
                                `location_id` varchar(64) NOT NULL,
                                `date_create` date NOT NULL,
                                `note` text NOT NULL,
                                `access` varchar(64) NOT NULL,
                                `access_level` int(11) NOT NULL,
                                `status` varchar(16) NOT NULL,
                                PRIMARY KEY (`note_id`) 
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8'); 

        $this->addCreateSql('file',
                            'CREATE TABLE `TABLE_NAME` (
                              `file_id` int(10) unsigned NOT NULL,
                              `title` varchar(255) NOT NULL,
                              `file_name` varchar(255) NOT NULL,
                              `file_name_orig` varchar(255) NOT NULL,
                              `file_text` longtext NOT NULL,
                              `file_date` date NOT NULL DEFAULT \'0000-00-00\',
                              `location_id` varchar(64) NOT NULL,
                              `location_rank` int(11) NOT NULL,
                              `key_words` text NOT NULL,
                              `description` text NOT NULL,
                              `file_size` int(11) NOT NULL,
                              `encrypted` tinyint(1) NOT NULL,
                              `file_name_tn` varchar(255) NOT NULL,
                              `file_ext` varchar(16) NOT NULL,
                              `file_type` varchar(16) NOT NULL,
                              PRIMARY KEY (`file_id`),
                              FULLTEXT KEY `search_idx` (`key_words`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8');  

        //initialisation
        $this->addInitialSql('INSERT INTO `TABLE_PREFIXbucket` (`name`,`description`,`date_create`,`status`,`access`,`access_level`) '.
                             'VALUES("My Bucket","My first bucket",CURDATE(),"OK","USER",2)');

        //updates use time stamp in ['YYYY-MM-DD HH:MM'] format, must be unique and sequential
        //$this->addUpdateSql('YYYY-MM-DD HH:MM','Update TABLE_PREFIX--- SET --- "X"');
    }
 
}


  
?>
