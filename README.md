#Laravel 5 Booking calendar
##About
This is rewriten [https://github.com/bastianallgeier/gantti] Gantt Class to fit my needs Eg. To show multiple events (bookings) per resource and Laravel 5 compatibility

##Screenshot

![](https://raw.githubusercontent.com/ognjenm/reservations-calendar/master/calendar.png)

##Installation

Require ognjenm/reservations-calendar in composer.json and run composer update.

```
{
    "require": {
        "laravel/framework": "5.1.*",
        ...
        "ognjenm/reservations-calendar": "*"
    }
    ...
}
```

Composer will download the package. After the package is downloaded, open config/app.php and add the service provider and alias as below:

```

'providers' => array(
    ...
    'Ognjenm\ReservationsCalendar\ReservationsCalendarServiceProvider:class',
),



'aliases' => array(
    ...
    'ResCalendar'     => 'Ognjenm\ReservationsCalendar\Facades\ResCalendar:class',
),

```
Finally you need to publish a configuration file by running the following Artisan command.

```
php artisan vendor:publish --tag=public --force
```

Include css in your view

```
<link href="/public/vendor/ognjenm/calendar.css" rel="stylesheet" type="text/css">

```

###Examples

Prepare data 
```

$data[] = [
            'label' => 'Soba 1',
        	'info' => '2+1',
        	'class' => 'blue',
            'events' => [

                    [
                    'label' => 'Ognjen Miletic',
                    'tooltip' => '<h5>Potvrdjena rezervacija</h5><br><p>od: 19.06.2015</p><p>do: 23.06.2015</p><p>Ukupno: 578 EUR</p>',
                    'url' => 'http://google.com',
                    'start' => '2015-06-19',
                    'end'   => '2015-06-23',
                    'class' => '',
                    'icon' => 'fa-arrow-down'
                    ],
                    [
                            'label' => 'Madona i ekipa',
                            'tooltip' => '<h5>Potvrdjena rezervacija</h5><br>
<p>od: 19.06.2015</p><p>do: 23.06.2015</p><p>Ukupno: 1578 EUR</p>',
                            'start' => '2015-06-10',
                            'end'   => '2015-06-19',
                            'class' => 'checkout',
                            'icon' => 'fa-sign-out'
                    ],
                    [
                            'label' => 'Jovan Jovanovic Zmaj',
                            'start' => '2015-06-23',
                            'end'   => '2015-06-30',
                            'class' => 'uncomfirmed',
                            'icon' => 'fa-question'
                    ],
                    [
                            'label' => 'Nikola Nikolic',
                            'tooltip' => '<h5>This is some html</h5>',
                            'url' => 'http://google.com',
                            'start' => '2015-06-30',
                            'end'   => '2015-07-15',
                            'class' => 'stay'
                    ],
            ]

            ];

    }
```


Render calendar
```
{!! ResCalendar::render($data,['title'=>'Hotel'])!!}
```

##Contributions are welcomed