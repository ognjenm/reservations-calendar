<?php namespace Ognjenm\ReservationsCalendar\Facades;

use Illuminate\Support\Facades\Facade;

class ResCalendar extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */

  protected static function getFacadeAccessor() { return 'rescalendar'; }

}
