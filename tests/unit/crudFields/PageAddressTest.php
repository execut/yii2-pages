<?php
namespace execut\pages\crudFields;


use Codeception\Test\Unit;
use execut\crudFields\fields\Field;
use execut\pages\crudFields\pageAddress\Adapter;
use execut\pages\models\Page;
use yii\db\ActiveQuery;

class PageAddressTest extends Unit
{
    public function testApplyScopes() {
        $model = new PageAddressTestPage();
        $model->setScenario(Field::SCENARIO_GRID);
        $query = new ActiveQuery('a');
        $address = '/address';
        $model->address = $address;
        $adapter = $this->getMockBuilder(Adapter::class)->getMock();
        $model->adapter = $adapter;

        $params = [
            'alias' => 'address',
        ];
        $adapter->expects($this->once())
            ->method('toArray')
            ->with($address)
            ->willReturn($params);


        $field = new PageAddress([
            'model' => $model,
            'attribute' => 'address',
        ]);
        $field->applyScopes($query);
        $this->assertEquals($params, $query->where);
    }

    public function testApplyScopesWithEmptyValue() {
        $model = new PageAddressTestPage();
        $model->setScenario(Field::SCENARIO_GRID);
        $query = new ActiveQuery('a');
        $adapter = $this->getMockBuilder(Adapter::class)->getMock();
        $adapter->expects($this->never())
            ->method('toArray');
        $model->adapter = $adapter;


        $field = new PageAddress([
            'model' => $model,
            'attribute' => 'address',
        ]);
        $field->applyScopes($query);
    }

    public function testGetColumn() {
        $model = new PageAddressTestPage();
        $model->setScenario(Field::SCENARIO_GRID);
        $adapter = $this->getMockBuilder(Adapter::class)->getMock();
        $params = [
            'id' => 123,
        ];
        $model->attributes = $params;
        $adapter->expects($this->once())
            ->method('toString')
            ->with($params)
            ->willReturn('/address');
        $model->setAdapter($adapter);

        $field = new PageAddress([
            'model' => $model,
            'attribute' => 'address',
        ]);
        $column = $field->getColumn();
        $this->assertArrayHasKey('value', $column);
        $value = $column['value'];
        $this->assertEquals('<a href="/address" target="_blank">/address</a>', $value($model));

        $this->assertArrayHasKey('format', $column);
        $this->assertEquals('raw', $column['format']);
    }
}

class PageAddressTestPage extends Page {
    public static $query = null;
    public static function find()
    {
        return self::$query;
    }

    protected $adapter;

    /**
     * @param mixed $adapter
     */
    public function setAdapter($adapter): void
    {
        $this->adapter = $adapter;
    }

    /**
     * @return mixed
     */
    public function getAdapter():Adapter
    {
        return $this->adapter;
    }
}