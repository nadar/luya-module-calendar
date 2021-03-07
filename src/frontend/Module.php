<?php

namespace nadar\calendar\frontend;

use yii\base\InvalidConfigException;

/**
 * Calendar Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author
 * @since 1.0.0
 */
class Module extends \luya\base\Module
{
    /**
     * @var string If a password is given, the calendar is protected and the password must be entered to 
     */
    public $password;

    /**
     * @var string Will be shown in the ical feed.
     */
    public $calendarLocation;

    public $urlRules = [
        'calendarfrontend/year/<year:\d+>' => 'calendarfrontend/default/index',
        'calendarfrontend/date/<from:\d+>/<to:\d+>' => 'calendarfrontend/default/detail',
        'calendarfrontend/feed' => 'calendarfrontend/default/feed',
        'calendarfrontend/create' => 'calendarfrontend/default/create',
    ];
}