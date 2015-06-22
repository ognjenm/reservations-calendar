<?php

namespace Ognjenm\ReservationsCalendar;

class Calendar {

    static $now = 0;

    function __construct() {
        Calendar::$now = time();
    }

    function years($start, $end) {
        $array = [];
        foreach (range($start, $end) as $year) {
            $array[] = $this->year($year);
        }
        return new CalendarIterator($array);
    }

    function year($year) {
        return new CalendarYear($year, 1, 1, 0, 0, 0);
    }

    function months($year = false) {
        $year = new CalendarYear($year, 1, 1, 0, 0, 0);
        return $year->months();
    }

    function month($year, $month) {
        return new CalendarMonth($year, $month, 1, 0, 0);
    }

    function week($year = false, $week = false) {
        return new CalendarWeek($year, $week);
    }

    function days($year = false) {
        $year = new CalendarYear($year);
        return $year->days();
    }

    function day($year = false, $month = false, $day = false) {
        return new CalendarDay($year, $month, $day);
    }

    function date() {

        $args = func_get_args();

        if (count($args) > 1) {

            $year   = isset($args[0]) ? $args[0] : false;
            $month  = isset($args[1]) ? $args[1] : 1;
            $day    = isset($args[2]) ? $args[2] : 1;
            $hour   = isset($args[3]) ? $args[3] : 0;
            $minute = isset($args[4]) ? $args[4] : 0;
            $second = isset($args[5]) ? $args[5] : 0;

        }
        else {

            if (isset($args[0])) {
                $ts = (is_int($args[0])) ? $args[0] : strtotime($args[0]);
            }
            else {
                $ts = time();
            }

            if (!$ts)
                return false;

            list($year, $month, $day, $hour, $minute, $second) = explode('-', date('Y-m-d-H-i-s', $ts));

        }

        return new CalendarDay($year, $month, $day, $hour, $minute, $second);

    }

    function today() {
        return $this->date('today');
    }

    function now() {
        return $this->today();
    }

    function tomorrow() {
        return $this->date('tomorrow');
    }

    function yesterday() {
        return $this->date('yesterday');
    }

}