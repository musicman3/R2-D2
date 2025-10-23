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
    Valid
};

/**
 * R2D2 PHP Router
 *
 * @package R2D2
 * @author R2D2 Team
 * @copyright © 2025 R2D2
 * @license Apache-2.0
 *
 */
class R2D2 {

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
            ],
            'catalog' => [
                'branch' => '/',
                'constructor' => '/view/default/catalog/constructor.php',
                'pagesPath' => '/view/default/catalog/pages',
                'jsPath' => '/js/structure/catalog/pages',
                'modelPath' => '/model/eMarket/Catalog',
                'namespace' => '\eMarket\Catalog',
            ],
            'install' => [
                'branch' => '/install',
                'constructor' => '/view/default/install/constructor.php',
                'pagesPath' => '/view/default/install/pages',
                'jsPath' => '/js/structure/install/pages',
                'modelPath' => '/model/eMarket/Install',
                'namespace' => '\eMarket\Install',
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
     * Get Config
     *
     * @return array|bool $this->config Config
     */
    public function getConfig(): array|bool {
        return self::$config;
    }

    /**
     * File check
     *
     * @param string $path Path check
     */
    public function fileCheck(string $path): string|bool {
        if (file_exists(getenv('DOCUMENT_ROOT') . $path)) {
            return getenv('DOCUMENT_ROOT') . $path;
        }
        return 'false';
    }

    /**
     * Current branch
     *
     * @param string|null $branch Set branch for tests
     * @return string
     */
    public static function branch(?string $branch = null): string {

        if ($branch != null) {
            return $branch;
        }

        $pathinfo = pathinfo(Valid::inSERVER('REQUEST_URI'));
        if ($pathinfo['dirname'] == '/' || $pathinfo['dirname'] == '\\') {
            return '/';
        }
        return $pathinfo['dirname'];
    }

    /**
     * Routing Map
     *
     * @param string|null $model Model path
     * @param string|null $namespace Namespace path
     * @return array (Routing Map array)
     */
    public static function routingMap(?string $model, ?string $namespace): array {
        $routing_parameters = [];
        $files = glob(getenv('DOCUMENT_ROOT') . $model . '/*');

        foreach ($files as $filename) {
            $full_namespace = $namespace . '\\' . pathinfo($filename, PATHINFO_FILENAME);
            if (isset($full_namespace::$routing_parameter)) {
                $routing_parameters[$full_namespace::$routing_parameter] = pathinfo($filename, PATHINFO_FILENAME);
            }
        }

        return $routing_parameters;
    }

    /**
     * Return route data
     *
     * @param string|null $route Set branch for tests
     * @return array|bool $this->config Config
     */
    public function route(?string $route = null): array|bool {

        if ($route == null) {
            $route = Valid::inGET('route');
        }

        $output = [];
        $config = $this->getConfig();

        foreach ($config['engine'] as $value => $name) {
            foreach ($name as $key => $val) {
                if ($key == 'branch' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $val;
                }
                if ($key == 'constructor' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $this->fileCheck($val);
                }
                if ($key == 'pagesPath' && $name['branch'] == $this->branch()) {
                    $output['engine']['page'] = $this->fileCheck($val . '/' . $route . '/index.php');
                }
                if ($key == 'jsPath' && $name['branch'] == $this->branch()) {
                    $output['engine']['js'] = $this->fileCheck($val . '/' . $route . '/js.php');
                }
                if ($key == 'modelPath' && $name['branch'] == $this->branch()) {
                    $routing_map = self::routingMap($val, $config['engine'][$value]['namespace']);
                    foreach ($routing_map as $routing_name => $class) {
                        if ($route == $routing_name) {
                            $output['engine']['model'] = $this->fileCheck($val . '/' . $class . '.php');
                            $output['engine']['className'] = $class;
                        }
                    }
                }
                if ($key == 'namespace' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $val;
                }
            }
        }
        return $output;
    }
}
