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

All setup starts with placing a configuration file in a single entry point for all requests. 

The structure of the configuration file. Example for 5 branches: 
`./
./admin
./install
./uploads
./services/jsonrpc/request`:
```php
['engine' =>
 [
 'catalog' => [
    'branch' => '/',
    'constructor' => '/view/default/catalog/constructor.php',
    'pagesPath' => '/view/default/catalog/pages',
    'jsPath' => '/js/structure/catalog/pages',
    'modelPath' => '/model/eMarket/Catalog',
    'namespace' => '\eMarket\Catalog',
    'index_route' => 'catalog',
  ],
  'admin' => [ // Name
    'branch' => '/admin', // Branch name
    'constructor' => '/view/default/admin/constructor.php', // Path to template constructor
    'pagesPath' => '/view/default/admin/pages', // Path to template pages
    'jsPath' => '/js/structure/admin/pages', // Path to JS files
    'modelPath' => '/model/eMarket/Admin', // Path to Model (objects this branch)
    'namespace' => '\eMarket\Admin', // Path to branch Namespace
    'index_route' => 'dashboard', // Branch index file
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
The response array of values ​​from R2-D2 will be something like this (for url https://localhost/admin/?route=orders)

```php
['engine' => 
    [
    'branch' => '/admin', // branch (http://localhost/admin)
    'constructor' => '/var/www/localhost/view/default/admin/constructor.php', // Path to template constructor
    'page' => '/var/www/localhost/view/default/admin/pages/orders/index.php', // Path to this template file
    'js' => '/var/www/localhost/js/structure/admin/pages/orders/js.php', // Path to JS file-constructor
    'namespace' => '\eMarket\Admin\Orders', // Object namespace
    'routing_parameter' => 'orders', // Routing path (?route=dashboard),
    'index_route' => 'dashboard', // Default index page for this branch (dashboard)
    ]
]
```
Routing uses the keyword "route", so an example url would look like this: https://localhost/admin/?route=my_route_path

Example:
```php
$R2D2 = new \R2D2\R2D2();

$config = [...]; // My config file
$R2D2->config($config); // Set config
$config = $R2D2->getConfig() // Get config

$route = $R2D2->route(); (Outbound routing as an array)

// For convenience, methods have been created that output data as a string.
$constructor = $R2D2->constructor(); (Path to template constructor file)
$page = $R2D2->page(); (Path to this template page file)
$js = $R2D2->js(); (Path to JS file-constructor)
$namespace = $R2D2->namespace(); (Object namespace)
$routing_parameter = $R2D2->routingParameter(); (Routing path)
$index = $R2D2->indexRoute(); (Default index page)
```
To ensure the router finds the object we need, we add a required parameter to the object variable `public static $routing_parameter`:
This parameter does not have to match the name of the object, and can be arbitrary. This parameter must be unique for all objects in the specified section and must not be repeated in other objects in this section. Therefore, when creating an object, we immediately assign it the `route` parameter, allowing us to easily add objects to the project by simply copying them, without creating a new routing parameter for the object in the router configuration. R2-D2 will automatically construct the entire routing path.

Example:
```php
declare(strict_types=1);

namespace myProject\Admin;

/**
 * Invoice
 *
 */
class InvoiceBlank {

    public static $routing_parameter = 'invoice';

    /**
     * Constructor
     *
     */
    function __construct() {
    }
}
```

After this, the specified method will be accessible via the URL: `/admin/?route=invoice`

### PHP Standards Recommendations Used: 
  - PSR-1 (Basic Coding Standard)
  - PSR-4 (Autoloading Standard)
  - PSR-5 (PHPDoc Standard)
  - PSR-12 (Extended Coding Style Guide)
  - PSR-19 (PHPDoc tags)
