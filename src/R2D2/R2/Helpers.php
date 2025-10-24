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

namespace R2D2\R2;

/**
 * R2D2 Helpers
 *
 * @package R2D2
 * @author R2D2 Team
 * @copyright © 2025 R2D2
 * @license Apache-2.0
 *
 */
class Helpers {

    /**
     * Constructor
     *
     */
    function __construct() {

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
     * Building tree to files
     *
     * @param string $dir Path to directory with files
     * @return array
     */
    public function filesTree(string $dir): array {

        $handle = opendir($dir) or die("Error: Can't open directory $dir");
        $files = [];
        $subfiles = [];
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && $file != '.gitkeep' && $file != '.gitignore') {
                if (is_dir($dir . '/' . $file)) {

                    $subfiles = $this->filesTree($dir . '/' . $file);

                    $files = array_merge($files, $subfiles);
                } else {
                    $files[] = $dir . '/' . $file;
                }
            }
        }
        closedir($handle);
        return $files;
    }
}
