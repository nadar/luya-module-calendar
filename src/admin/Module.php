<?php

namespace nadar\calendar\admin;

/**
 * Calendar Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
    public $apis = [
        'api-calendar-item' => 'nadar\calendar\admin\apis\ItemController',
        'api-calendar-person' => 'nadar\calendar\admin\apis\PersonController',
    ];
    
    public function getMenu()
    {
        return (new \luya\admin\components\AdminMenuBuilder($this))
            ->node('Kalender', 'calendar_today')
                ->group('Daten')
                    ->itemApi('Termine', 'calendaradmin/item/index', 'calendar_today', 'api-calendar-item')
                    ->itemApi('Personen', 'calendaradmin/person/index', 'person', 'api-calendar-person');

    }
        
}