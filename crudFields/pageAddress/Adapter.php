<?php


namespace execut\pages\crudFields\pageAddress;


use execut\pages\models\Page;

interface Adapter
{
    public function toArray($address);
    public function toString($params);
    public function getLabel():string;
    public function getKey():int;
    public function getFields():array;
    public function initPageFields(Page $page);
}