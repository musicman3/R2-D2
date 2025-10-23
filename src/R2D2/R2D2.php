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
     * Building tree to files
     *
     * @param string $dir Path to directory with files
     * @return array
     */
    private static function filesTree(string $dir): array {

        $handle = opendir($dir) or die("Error: Can't open directory $dir");
        $files = [];
        $subfiles = [];
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && $file != '.gitkeep' && $file != '.gitignore') {
                if (is_dir($dir . '/' . $file)) {

                    $subfiles = self::filesTree($dir . '/' . $file);

                    $files = array_merge($files, $subfiles);
                } else {
                    $files[] = $dir . '/' . $file;
                }
            }
        }
        closedir($handle);
        return $files;
    }

    /**
     * Routing Map
     *
     * @param string|null $model Model path
     * @param string|null $namespace Namespace path
     * @return array (Routing Map array)
     */
    private static function routingMap(?string $model, ?string $namespace): array {
        $routing_parameters = [];
        $files = self::filesTree(getenv('DOCUMENT_ROOT') . $model);

        $namespaces = [];
        foreach ($files as $file) {
            $namespace_right_part_prepare = explode($model . '/', $file)[1];
            $namespace_right_part = explode('.php', $namespace_right_part_prepare)[0];
            $namespaces_right = str_replace('/', '\\', $namespace_right_part);
            $namespaces[$file] = $namespace . '\\' . $namespaces_right;
        }

        foreach ($namespaces as $key => $value) {
            if (isset($value::$routing_parameter)) {
                $routing_parameters[$value::$routing_parameter] = [$key => $value];
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
                            $output['engine']['model'] = key($class);
                            $output['engine']['className'] = $class[key($class)];
                        }
                    }
                }
            }
        }
        return $output;
    }
}
