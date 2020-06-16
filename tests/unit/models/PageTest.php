<?php


namespace execut\pages\models;


use Codeception\Test\Unit;
use execut\crudFields\fields\Boolean;
use execut\crudFields\fields\DropDown;
use execut\pages\crudFields\PageAddress;
use execut\pages\Module;

class PageTest extends Unit
{
    public function tearDown(): void
    {
        parent::tearDown();

        \yii::$app->setModule('pages', null);
    }

    public function testTypeField() {
        $model = new Page();
        $field = $model->getField('type');
        $this->assertInstanceOf(DropDown::class, $field);
        $this->assertEquals([
            Page::TYPE_SIMPLE => 'Simple',
        ], $field->getData());
        $this->assertTrue($field->required);
    }

    public function testAddressField() {
        $model = new Page();
        $field = $model->getField('address');
        $this->assertInstanceOf(PageAddress::class, $field);
    }

    public function testGetAdapters() {
        $model = new Page();
        $this->assertCount(1, $model->getAdapters());
    }

    public function testGetAdaptersViaPlugins() {
        $model = new Page();

        $module = $this->getMockBuilder(Module::class)->setConstructorArgs(['pages'])->getMock();
        $module->method('getPageAddressAdapters')
            ->willReturn(['test']);
        \yii::$app->setModule('pages', $module);
        $this->assertCount(2, $model->getAdapters());
    }
}