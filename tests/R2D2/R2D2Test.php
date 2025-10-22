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

use PHPUnit\Framework\TestCase;

final class R2D2Test extends TestCase {

    /**
     * getConfig()
     *
     */
    public function testGetConfig(): void {
        $r2d2 = new \R2D2\R2D2();

        $config = [
            'engine' =>
            [
                'admin' => [
                    'branch' => '/admin',
                    'constructor' => '/view/default/admin/constructor.php',
                    'pages' => '/view/default/admin/pages',
                    'js' => '/js/structure/admin/pages',
                    'model' => '/model/eMarket/Admin',
                ],
                'catalog' => [
                    'branch' => '/',
                    'constructor' => '/view/default/catalog/constructor.php',
                    'pages' => '/view/default/catalog/pages',
                    'js' => '/js/structure/catalog/pages',
                    'model' => '/model/eMarket/Catalog',
                ],
                'install' => [
                    'branch' => '/install',
                    'constructor' => '/view/default/install/constructor.php',
                    'pages' => '/view/default/install/pages',
                    'js' => '/js/structure/install/pages',
                    'model' => '/model/eMarket/Install',
                ],
            ]
        ];

        $r2d2->config($config);
        $result = $r2d2->getConfig();

        $this->assertCount(1, $result);
        $this->assertCount(3, $result['engine']);
        $this->assertCount(5, $result['engine']['admin']);
        $this->assertCount(5, $result['engine']['catalog']);
        $this->assertCount(5, $result['engine']['install']);

        $this->assertSame($result['engine']['admin']['branch'], '/admin');
        $this->assertSame($result['engine']['admin']['constructor'], '/view/default/admin/constructor.php');
        $this->assertSame($result['engine']['admin']['pages'], '/view/default/admin/pages');
        $this->assertSame($result['engine']['admin']['js'], '/js/structure/admin/pages');
        $this->assertSame($result['engine']['admin']['model'], '/model/eMarket/Admin');

        $this->assertSame($result['engine']['catalog']['branch'], '/');
        $this->assertSame($result['engine']['catalog']['constructor'], '/view/default/catalog/constructor.php');
        $this->assertSame($result['engine']['catalog']['pages'], '/view/default/catalog/pages');
        $this->assertSame($result['engine']['catalog']['js'], '/js/structure/catalog/pages');
        $this->assertSame($result['engine']['catalog']['model'], '/model/eMarket/Catalog');

        $this->assertSame($result['engine']['install']['branch'], '/install');
        $this->assertSame($result['engine']['install']['constructor'], '/view/default/install/constructor.php');
        $this->assertSame($result['engine']['install']['pages'], '/view/default/install/pages');
        $this->assertSame($result['engine']['install']['js'], '/js/structure/install/pages');
        $this->assertSame($result['engine']['install']['model'], '/model/eMarket/Install');
    }
}
