<?php

namespace Ognjenm\ReservationsCalendar;

class CalendarIterator implements \Iterator {

    private $_ = [];

    public function __construct($array = []) {
        $this->_ = $array;
    }

    function __toString() {
        $result = '';
        foreach ($this->_ as $date) {
            $result .= $date . '<br />';
        }
        return $result;
    }

    function rewind() {
        reset($this->_);
    }

    function current() {
        return current($this->_);
    }

    function key() {
        return key($this->_);
    }

    function next() {
        return next($this->_);
    }

    function prev() {
        return prev($this->_);
    }

    function valid() {
        $key = key($this->_);
        $var = ($key !== null && $key !== false);
        return $var;
    }

    function count() {
        return count($this->_);
    }

    function first() {
        return array_shift($this->_);
    }

    function last() {
        return array_pop($this->_);
    }

    function nth($n) {
        $values = array_values($this->_);
        return isset($values[$n]) ? $values[$n] : null;
    }

    function indexOf($needle) {
        return array_search($needle, array_values($this->_));
    }

    function toArray() {
        return $this->_;
    }

    function slice($offset = null, $limit = null) {
        if ($offset === null && $limit === null)
            return $this;
        return new CalendarIterator(array_slice($this->_, $offset, $limit));
    }

    function limit($limit) {
        return $this->slice(0, $limit);
    }

}