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
    private static $config;

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
     * @return string
     */
    public static function branch(): string {
        $pathinfo = pathinfo(Valid::inSERVER('REQUEST_URI'));
        if ($pathinfo['dirname'] == '/' || $pathinfo['dirname'] == '\\') {
            return '/';
        }
        return $pathinfo['dirname'];
    }

    /**
     * Return route data
     * 
     * @return array|bool $this->config Config
     */
    public function route(): array|bool {

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
                if ($key == 'pages' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $this->fileCheck($val . '/' . Valid::inGET('route') . '/index.php');
                }
                if ($key == 'js' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $this->fileCheck($val . '/' . Valid::inGET('route') . '/js.php');
                }
                if ($key == 'model' && $name['branch'] == $this->branch()) {
                    $output['engine'][$key] = $this->fileCheck($val . '/' . ucfirst(Valid::inGET('route')) . '.php');
                }
            }
        }
        return $output;
    }
}
