<?php

namespace Ognjenm\ReservationsCalendar;

class CalendarMonth extends CalendarObj {

    function __toString() {
        return $this->format('Y-m');
    }

    function int() {
        return $this->monthINT;
    }

    function weeks($force = false) {

        $first = $this->firstDay();
        $week  = $first->week();

        $currentMonth = $this->int();
        $nextMonth    = $this->next()->int();

        $max = ($force) ? $force : 6;

        for ($x = 0; $x < $max; $x++) {

            // make sure not to add weeks without a single day in the same month
            if (!$force && $x > 0 && $week->firstDay()->month()->int() != $currentMonth)
                break;

            $array[] = $week;

            // make sure not to add weeks without a single day in the same month
            if (!$force && $week->lastDay()->month()->int() != $currentMonth)
                break;

            $week = $week->next();

        }

        return new CalendarIterator($array);

    }

    function countDays() {
        return date('t', $this->timestamp);
    }

    function firstDay() {
        return new CalendarDay($this->yearINT, $this->monthINT, 1);
    }

    function lastDay() {
        return new CalendarDay($this->yearINT, $this->monthINT, $this->countDays());
    }

    function days() {

        // number of days per month
        $days  = date('t', $this->timestamp);
        $array = [];
        $ts    = $this->firstDay()->timestamp();

        foreach (range(1, $days) as $day) {
            $array[] = $this->day($day);
        }

        return new CalendarIterator($array);

    }

    function day($day = 1) {
        return new CalendarDay($this->yearINT, $this->monthINT, $day);
    }

    function next() {
        return $this->plus('1month')->month();
    }

    function prev() {
        return $this->minus('1month')->month();
    }

    function name() {
        return strftime('%B', $this->timestamp);
    }

    function shortname() {
        return strftime('%b', $this->timestamp);
    }

}