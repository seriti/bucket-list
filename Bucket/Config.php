<?php 
namespace App\Bucket;

use Psr\Container\ContainerInterface;
use Seriti\Tools\BASE_URL;
use Seriti\Tools\SITE_NAME;

class Config
{
    
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        
        $module = $this->container->config->get('module','bucket');
        
        $menu = $this->container->menu;
        $db = $this->container->mysql;
        $user = $this->container->user;

        //$user_specific = true;
        //$cache->setCache('Assets',$user_specific);
        //$cache->eraseAll();
        
        define('TABLE_PREFIX',$module['table_prefix']);
        define('MODULE_ID','BUCKET');
        define('MODULE_LOGO','<img src="'.BASE_URL.'images/bucket.png" width="32"> ');
        define('MODULE_PAGE',URL_CLEAN_LAST);

        define('ACCESS_RANK',['GOD'=>1,'ADMIN'=>2,'USER'=>5,'VIEW'=>10]);

        //only show module sub menu for users with normal non-route based access
        if($user->getRouteAccess() === false) {
            $submenu_html = $menu->buildNav($module['route_list'],MODULE_PAGE);
            $this->container->view->addAttribute('sub_menu',$submenu_html);
        }    
       
        $response = $next($request, $response);
        
        return $response;
    }
}