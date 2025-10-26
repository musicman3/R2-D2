# R2-D2 PHP AutoRouter

### Project installation:
`composer require musicman3/r2-d2`

### System requirements: 
  - OS Unix, Linux or Windows
  - Apache Web Server >= 2.4 or Nginx >= 1.17
  - PHP >= 8.3

Project R2-D2 is an automatic router that automatically creates routing for new objects added to the project, eliminating the need for constant configuration when adding new objects. It was created primarily for the eMarket project: https://github.com/musicman3/eMarket

R2-D2 only allows you to configure the main routing branches, while R2-D2 automatically collects all object data and routes it. This eliminates the need to route each new object.

You simply add new objects (classes), and they will be automatically configured according to the configuration file structure. For autorouting to be feasible, we must adhere to the minimum placement requirements for new objects, which are also set in the configuration file.

All setup begins with placing a configuration file at the single point of receipt for all requests. The configuration file structure is:

```php
        'engine' =>
        [
            'admin' => [ // Name
                'branch' => '/admin', // Branch name
                'constructor' => '/view/default/admin/constructor.php', // Path to template constructor file
                'pagesPath' => '/view/default/admin/pages', // Path to template pages
                'jsPath' => '/js/structure/admin/pages', // Path to JS files
                'modelPath' => '/model/eMarket/Admin', // Path to Model (objects this branch)
                'namespace' => '\eMarket\Admin', // Path to branch Namespace
                'index_route' => 'dashboard', // Branch index file
            ],
            'catalog' => [
                'branch' => '/',
                'constructor' => '/view/default/catalog/constructor.php',
                'pagesPath' => '/view/default/catalog/pages',
                'jsPath' => '/js/structure/catalog/pages',
                'modelPath' => '/model/eMarket/Catalog',
                'namespace' => '\eMarket\Catalog',
                'index_route' => 'catalog',
            ],
            'install' => [
                'branch' => '/install',
                'constructor' => '/view/default/install/constructor.php',
                'pagesPath' => '/view/default/install/pages',
                'jsPath' => '/js/structure/install/pages',
                'modelPath' => '/model/eMarket/Install',
                'namespace' => '\eMarket\Install',
                'index_route' => 'index',
            ],
            'uploads' => [
                'branch' => '/uploads',
                'constructor' => '',
                'pagesPath' => '',
                'jsPath' => '',
                'modelPath' => '/model/eMarket/Uploads',
                'namespace' => '\eMarket\Uploads',
                'index_route' => '',
            ],
            'JsonRpc' => [
                'branch' => '/services/jsonrpc/request',
                'constructor' => '',
                'pagesPath' => '',
                'jsPath' => '',
                'modelPath' => '/model/eMarket/JsonRpc',
                'namespace' => '\eMarket\JsonRpc',
                'index_route' => 'JsonRpcController',
            ],
        ]
    ];
```
The response array of values ​​from R2-D2 will be something like this (for url https://localhost/admin/?route=dashboard)

```php
    'engine' => 
        [
            'branch' => '/admin', // branch (http://localhost/admin)
            'constructor' => '/var/www/localhost/view/default/admin/constructor.php', // Path to template constructor file
            'page' => '/var/www/localhost/view/default/admin/pages/dashboard/index.php', // Path to this template file
            'js' => '/var/www/localhost/js/structure/admin/pages/dashboard/js.php', // Path to JS file-constructor
            'namespace' => '\eMarket\Admin\Dashboard', // Object namespace
            'routing_parameter' => 'dashboard', // Routing path (?route=dashboard)
        ]
```
