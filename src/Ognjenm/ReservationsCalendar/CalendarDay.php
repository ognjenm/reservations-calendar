<?php

namespace Ognjenm\ReservationsCalendar;

class CalendarDay extends CalendarObj {

    function __toString() {
        return $this->format('Y-m-d');
    }

    function int() {
        return $this->dayINT;
    }

    function week() {
        $week = date('W', $this->timestamp);
        $year = ($this->monthINT == 1 && $week > 5) ? $this->year()->prev() : $this->year();
        return new CalendarWeek($year->int(), $week);
    }

    function next() {
        return $this->plus('1day');
    }

    function prev() {
        return $this->minus('1day');
    }

    function weekday() {
        return date('N', $this->timestamp);
    }

    function name() {
        return strftime('%A', $this->timestamp);
    }

    function shortname() {
        return strftime('%a', $this->timestamp);
    }

    function isToday() {
        $cal = new Calendar();
        return $this == $cal->today();
    }

    function isYesterday() {
        $cal = new Calendar();
        return $this == $cal->yesterday();
    }

    function isTomorrow() {
        $cal = new Calendar();
        return $this == $cal->tomorrow();
    }

    function isInThePast() {
        return ($this->timestamp < Calendar::$now) ? true : false;
    }

    function isInTheFuture() {
        return ($this->timestamp > Calendar::$now) ? true : false;
    }

    function isWeekend() {
        $num = $this->format('w');
        return ($num == 6 || $num == 0) ? true : false;
    }

    function hours() {

        $obj   = $this;
        $array = [];

        while ($obj->int() == $this->int()) {
            $array[] = $obj->hour();
            $obj     = $obj->plus('1hour');
        }

        return new CalendarIterator($array);

    }

}