<?php  
/*
NB: This is not stand alone code and is intended to be used within "seriti/slim3-skeleton" framework
The code snippet below is for use within an existing src/routes.php file within this framework
copy the "/bucket" group into the existing "/admin" group within existing "src/routes.php" file 
*/

$app->group('/admin', function () {

    $$this->group('/bucket', function () {
        $this->any('/bucket', \App\Bucket\BucketController::class);
        $this->any('/bucket_note', \App\Bucket\BucketNoteController::class);
        $this->any('/bucket_file', \App\Bucket\BucketFileController::class);
        $this->any('/dashboard', \App\Bucket\DashboardController::class);
        $this->get('/setup_data', \App\Bucket\SetupDataController::class);
        $this->post('/ajax', \App\Bucket\Ajax::class);
    })->add(\App\Bucket\Config::class);



})->add(\App\ConfigAdmin::class);



