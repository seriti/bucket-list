<?php
namespace App\Bucket;

use Psr\Container\ContainerInterface;
use App\Bucket\Bucket;

class BucketController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        
        $table = TABLE_PREFIX.'bucket';

        $table = new Bucket($this->container->mysql,$this->container,$table);

        $table->setup();
        $html = $table->processTable();
        
        $template['html'] = $html;
        $template['title'] = MODULE_LOGO.'ALL document buckets';
        
        return $this->container->view->render($response,'admin.php',$template);
    }
}