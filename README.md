<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# LUYA CALENDAR MODULE

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)

A very simple, password protected calendar (months) and detail (days of selected month) view for LUYA.

## Installation

Install the extension through composer:

```sh
composer require nadar/luya-module-calendar
```

Add the modules to your config file:

```php
'modules' => [
  'calendarfrontend' => [
    'class' => 'nadar\calendar\frontend\Module',
    'password' => 'mysecretcalendar',
    'calendarLocation' => 'Somewhere over the Rainbow',
  ],
  'calendaradmin' => [
    'class' => 'nadar\calendar\admin\Module'
  ],
]
```


Run the migrate and import

```sh
./luya migrate
```

Import command

```sh
./luya import
```