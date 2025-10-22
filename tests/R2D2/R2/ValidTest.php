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

use PHPUnit\Framework\TestCase;
use R2D2\R2\{
    Valid
};

final class ValidTest extends TestCase {

    /**
     * Request simulator helper for test
     *
     * @param mixed $data Input data
     */
    public function requestSimulatorHelper(mixed $data): void {
        Valid::requestSimulator('json', $data);
        Valid::requestSimulator('post', $data);
        Valid::requestSimulator('get', $data);
        Valid::requestSimulator('server', $data);
        Valid::requestSimulator('cookie', $data);

        $this->assertSame($data, Valid::$post_json_simulator);
        $this->assertSame($data, Valid::$post_simulator);
        $this->assertSame($data, Valid::$get_simulator);
        $this->assertSame($data, Valid::$server_simulator);
        $this->assertSame($data, Valid::$cookie_simulator);

        Valid::closeRequestSimulator();
    }

    /**
     * requestSimulator()
     *
     */
    public function testRequestSimulator() {
        $this->requestSimulatorHelper('string');
        $this->requestSimulatorHelper(1);
        $this->requestSimulatorHelper([]);
        $this->requestSimulatorHelper(true);
    }

    /**
     * closeRequestSimulator()
     *
     */
    public function testCloseRequestSimulator() {

        Valid::closeRequestSimulator();

        $this->assertFalse(Valid::$post_json_simulator);
        $this->assertFalse(Valid::$post_simulator);
        $this->assertFalse(Valid::$get_simulator);
        $this->assertFalse(Valid::$server_simulator);
        $this->assertFalse(Valid::$cookie_simulator);
    }
}
