<?php
/**
 */

namespace execut\pages\navigation;


class Page extends \execut\navigation\Page
{
    public $model = null;
    public function getText()
    {
        $text = '';
        $text .= parent::getText();

        return $text;
    }
}