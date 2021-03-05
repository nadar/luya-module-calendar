<?php

namespace nadar\calendar\admin\apis;

/**
 * Item Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class ItemController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'nadar\calendar\models\Item';
}