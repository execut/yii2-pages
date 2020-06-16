<?php


namespace execut\pages\crudFields\pageAddress;


interface Adapter
{
    public function toArray($address);
    public function toString($params);
    public function getLabel():string;
    public function getKey():int;
    public function getFields():array;
}