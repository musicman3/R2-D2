<?php

/* =-=-=-= Copyright © 2025 R2D2 =-=-=-=-=-= 
  |           APACHE-2.0 LICENSE            |
  |   https://github.com/musicman3/Cruder   |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

declare(strict_types=1);

namespace R2D2;

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
    private $config;

    /**
     * Constructor
     *
     */
    function __construct() {
        
    }

    /**
     * Config
     * 
     * @param string $set Config
     */
    public function config(array $set): void {
        $this->config = $set;
    }

    /**
     * Get Config
     * 
     * @return array|bool $this->config Config
     */
    public function getConfig(): array|bool {
        return $this->config;
    }
}
