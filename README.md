#Laravel 4 Booking calendar

##Screenshot

!(image)[https://raw.githubusercontent.com/ognjenm/reservations-calendar/master/calendar.png]

##Installation

Require ognjenm/reservations-calendar in composer.json and run composer update.

```
{
    "require": {
        "laravel/framework": "4.2.*",
        ...
        "ognjenm/reservations-calendar": "*"
    }
    ...
}
```

Composer will download the package. After the package is downloaded, open app/config/app.php and add the service provider and alias as below:

```

'providers' => array(
    ...
    'Ognjenm\ReservationsCalendar\ReservationsCalendarServiceProvider',
),



'aliases' => array(
    ...
    'ResCalendar'     => 'Ognjenm\ReservationsCalendar\Facades\ResCalendar',
),

```
Finally you need to publish a configuration file by running the following Artisan command.

```

php artisan asset:publish ognjenm/reservations-calendar
```

Include css in your view

```

    {{ HTML::style('packages/ognjenm/reservations-calendar/css/gantti.css') }}
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
{{ ResCalendar::render($data,['title'=>'Hotel'])}}
```

##Contributions are welcomed