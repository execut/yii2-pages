<?php
/**
 */

namespace execut\pages\action\adapter;


use execut\actions\action\Params;
use execut\pages\models\Page;
use execut\pages\TestCase;

class ShowPageTest extends TestCase
{
    public function testRun() {
        $adapter = new ShowPage([
            'modelClass' => Model::class,
            'actionParams' => new Params([
                'get' => [
                    'id' => 2,
                ],
            ]),
        ]);
        $response = $adapter->run();
        $this->assertArrayHasKey('model', $response->content);
        $this->assertInstanceOf(Model::class, $response->content['model']);
        $this->assertEquals(2, Model::$id);
    }
}

class Model extends Page {
    public static $id = null;
    public static function findById($id) {
        $page = new self;
        self::$id = $id;
        return $page;
    }
}