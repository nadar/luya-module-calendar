<?php

namespace nadar\calendar\admin\apis;

/**
 * Person Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class PersonController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'nadar\calendar\models\Person';
}