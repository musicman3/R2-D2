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
        if (file_exists(getenv('DOCUMENT_ROOT') . $path) && $path != '') {
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

    /**
     * Function for escaping special characters.
     * Protection against XSS, LFI and other types of attacks.
     * Output data filtering 
     *
     * @param string|array $data Data to escape characters
     * @return mixed
     */
    public function outputDataFiltering(mixed $data): mixed {
        // symbol and replacement
        $find = ["'", "script", "/.", "./"];
        $replace = ["&#8216;", "!s-c-r-i-p-t!", "!/.!", "!./!"];

        $output = $this->recursiveArrayReplace($find, $replace, $data);

        return $output;
    }

    /**
     * Recursive array replace
     *
     * @param string|array $find Find value
     * @param string|array $replace Replace value
     * @param string|array $data Input data
     * @return mixed
     */
    public function recursiveArrayReplace(array|string $find, array|string $replace, mixed $data): mixed {
        if (is_bool($data) || is_null($data)) {
            return $data;
        }

        if (is_int($data)) {
            return str_ireplace($find, $replace, (string) $data);
        }

        if (!is_array($data)) {
            return str_ireplace($find, $replace, $data);
        }

        $output = [];
        foreach ($data as $key => $value) {
            $output[$key] = $this->recursiveArrayReplace($find, $replace, $value);
        }
        return $output;
    }
}
