<?php

/* =-=-=-= Copyright © 2025 R2D2 =-=-=-=-=-=
  |           APACHE-2.0 LICENSE            |
  |   https://github.com/musicman3/r2-d2    |
  |                   ___                   |
  |                ,-'___'-.                |
  |              ,'  [(_)]  '.              |
  |             |_]||[][O]o[][|             |
  |           _ |_____________| _           |
  |          | []   _______   [] |          |
  |          | []   _______   [] |          |
  |         [| ||      _      || |]         |
  |          |_|| =   [=]     ||_|          |
  |          | || =   [|]     || |          |
  |          | ||      _      || |          |
  |          | ||||   (+)    (|| |          |
  |          | ||_____________|| |          |
  |          |_| \___________/ |_|          |
  |          / \      | |      / \          |
  |         /___\    /___\    /___\         |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

declare(strict_types=1);

namespace R2D2;

use R2D2\R2\{
    Valid,
    Helpers
};

/**
 * R2D2 PHP AutoRouter
 *
 * @package R2D2
 * @author R2D2 Team
 * @copyright © 2025 R2D2
 * @license Apache-2.0
 *
 */
class R2D2 {

    /**
     * @var array|bool $object (Router Object)
     */
    private static $object = FALSE;

    /**
     * @var string|null $route (Route path)
     */
    private static $route = null;

    /**
     * @var array|bool $config (Router Config)
     */
    private static $config = [
        'engine' =>
        [
            'admin' => [
                'branch' => '/admin',
                'constructor' => '/view/default/admin/constructor.php',
                'pagesPath' => '/view/default/admin/pages',
                'jsPath' => '/js/structure/admin/pages',
                'modelPath' => '/model/eMarket/Admin',
                'namespace' => '\eMarket\Admin',
                'index_route' => 'dashboard',
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

    /**
     * Constructor
     *
     */
    function __construct() {
        
    }

    /**
     * Config
     *
     * @param array $set Config
     */
    public function config(array $set): void {
        self::$config = $set;
    }

    /**
     * Set Route
     *
     */
    public function setRoute(): void {
        $config = $this->getConfig();

        foreach ($config['engine'] as $name) {
            if ($this->branch() == $name['branch']) {
                if (!Valid::inGET('route') || Valid::inGET('route') == '') {
                    self::$route = $name['index_route'];
                }
            }
        }
    }

    /**
     * Get Config
     *
     * @return array|bool $this->config Config
     */
    public function getConfig(): array|bool {
        return self::$config;
    }

    /**
     * Current branch
     *
     * @param string|null $branch Set branch for tests
     * @return string
     */
    public function branch(?string $branch = null): string {

        if ($branch != null) {
            return $branch;
        }
        $parse_url = parse_url(Valid::inSERVER('REQUEST_URI'));

        if ($parse_url['path'] == '/') {
            return '/';
        }

        return rtrim($parse_url['path'], '/');
    }

    /**
     * Routing Map
     *
     * @param string|null $model Model path
     * @param string|null $namespace Namespace path
     * @return array (Routing Map array)
     */
    private function routingMap(?string $model, ?string $namespace): array {
        $routing_parameters = [];
        $Helpers = new Helpers();
        $files = $Helpers->filesTree(getenv('DOCUMENT_ROOT') . $model);

        $namespaces = [];
        foreach ($files as $file) {
            $namespace_right_part = explode($model . '/', $file)[1];
            $namespaces_right = str_replace(['.php', '/'], ['', '\\'], $namespace_right_part);
            $namespaces[$file] = $namespace . '\\' . $namespaces_right;
        }

        foreach ($namespaces as $value) {
            if (isset($value::$routing_parameter)) {
                $routing_parameters[$value::$routing_parameter] = $value;
            }
        }

        return $routing_parameters;
    }

    /**
     * Return route data
     *
     * @return array|bool $this->config Config
     */
    public function route(): array|bool {
        if (!self::$object) {

            $this->setRoute();

            if (self::$route == null) {
                $route = Valid::inGET('route');
            } else {
                $route = self::$route;
            }

            $Helpers = new Helpers();

            $output = [];
            $config = $this->getConfig();

            foreach ($config['engine'] as $value => $name) {
                foreach ($name as $key => $val) {
                    if ($key == 'branch' && $name['branch'] == $this->branch()) {
                        $output['engine']['branch'] = $val;
                    }
                    if ($key == 'constructor' && $name['branch'] == $this->branch()) {
                        $output['engine']['constructor'] = $Helpers->fileCheck($val);
                    }
                    if ($key == 'pagesPath' && $name['branch'] == $this->branch()) {
                        $output['engine']['page'] = $Helpers->fileCheck($val . '/' . $route . '/index.php');
                        if ($Helpers->fileCheck($val . '/' . $route . '/index.php') == 'false') {
                            $output['engine']['page'] = $Helpers->fileCheck($val . '/page_not_found/index.php');
                        }
                    }
                    if ($key == 'jsPath' && $name['branch'] == $this->branch()) {
                        $output['engine']['js'] = $Helpers->fileCheck($val . '/' . $route . '/js.php');
                    }
                    if ($key == 'modelPath' && $name['branch'] == $this->branch()) {
                        $routing_map = $this->routingMap($val, $config['engine'][$value]['namespace']);

                        foreach ($routing_map as $routing_name => $class) {
                            if ($route == $routing_name) {
                                $output['engine']['namespace'] = $class;
                                $output['engine']['routing_parameter'] = $routing_name;
                                break;
                            } else {
                                $output['engine']['namespace'] = $config['engine'][$value]['namespace'] . '\PageNotFound';
                                $output['engine']['routing_parameter'] = 'page_not_found';
                            }
                        }
                    }
                }
            }

            self::$object = $output;
            return $output;
        } else {
            return self::$object;
        }
    }

    /**
     * Constructor
     *
     * @return string|null|bool Path to constructor file
     */
    public function constructor(): string|null|bool {
        return $this->route()['engine']['constructor'];
    }

    /**
     * Page
     *
     * @return string|null|bool Path to Page file
     */
    public function page(): string|null|bool {
        $Helpers = new Helpers();
        return $Helpers->outputDataFiltering($this->route()['engine']['page']);
    }

    /**
     * JS
     *
     * @return string|null|bool Path to js file
     */
    public function js(): string|null|bool {
        $Helpers = new Helpers();
        return $Helpers->outputDataFiltering($this->route()['engine']['js']);
    }

    /**
     * Namespace
     *
     * @return string|null|bool Full Namespace to Class
     */
    public function namespace(): string|null|bool {
        $Helpers = new Helpers();
        if (isset($this->route()['engine']['namespace']) && class_exists($this->route()['engine']['namespace'])) {
            return $Helpers->outputDataFiltering($this->route()['engine']['namespace']);
        }
        exit;
    }

    /**
     * Routing parameter
     *
     * @return string|null|bool Routing parameter
     */
    public function routingParameter(): string|null|bool {
        $Helpers = new Helpers();
        return $Helpers->outputDataFiltering($this->route()['engine']['routing_parameter']);
    }
}
