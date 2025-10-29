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
            ]
        ];

        $r2d2->config($config);
        $result = $r2d2->getConfig();

        $this->assertCount(1, $result);
        $this->assertCount(3, $result['engine']);
        $this->assertCount(7, $result['engine']['admin']);
        $this->assertCount(7, $result['engine']['catalog']);
        $this->assertCount(7, $result['engine']['install']);

        $this->assertSame($result['engine']['admin']['branch'], '/admin');
        $this->assertSame($result['engine']['admin']['constructor'], '/view/default/admin/constructor.php');
        $this->assertSame($result['engine']['admin']['pagesPath'], '/view/default/admin/pages');
        $this->assertSame($result['engine']['admin']['jsPath'], '/js/structure/admin/pages');
        $this->assertSame($result['engine']['admin']['modelPath'], '/model/eMarket/Admin');
        $this->assertSame($result['engine']['admin']['namespace'], '\eMarket\Admin');
        $this->assertSame($result['engine']['admin']['index_route'], 'dashboard');

        $this->assertSame($result['engine']['catalog']['branch'], '/');
        $this->assertSame($result['engine']['catalog']['constructor'], '/view/default/catalog/constructor.php');
        $this->assertSame($result['engine']['catalog']['pagesPath'], '/view/default/catalog/pages');
        $this->assertSame($result['engine']['catalog']['jsPath'], '/js/structure/catalog/pages');
        $this->assertSame($result['engine']['catalog']['modelPath'], '/model/eMarket/Catalog');
        $this->assertSame($result['engine']['catalog']['namespace'], '\eMarket\Catalog');
        $this->assertSame($result['engine']['catalog']['index_route'], 'catalog');

        $this->assertSame($result['engine']['install']['branch'], '/install');
        $this->assertSame($result['engine']['install']['constructor'], '/view/default/install/constructor.php');
        $this->assertSame($result['engine']['install']['pagesPath'], '/view/default/install/pages');
        $this->assertSame($result['engine']['install']['jsPath'], '/js/structure/install/pages');
        $this->assertSame($result['engine']['install']['modelPath'], '/model/eMarket/Install');
        $this->assertSame($result['engine']['install']['namespace'], '\eMarket\Install');
        $this->assertSame($result['engine']['install']['index_route'], 'index');
    }
}
