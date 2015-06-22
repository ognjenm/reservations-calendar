<?php

namespace Ognjenm\ReservationsCalendar;

class Gantti {

    var $cal       = null;
    var $data      = [];
    var $first     = false;
    var $last      = false;
    var $options   = [];
    var $cellstyle = false;
    var $blocks    = [];
    var $events = [];
    var $months    = [];
    var $days      = [];
    var $seconds   = 0;
    private $defaults = [
        'title'      => 'Rezervacije',
        'cellwidth'  => 35,
        'cellheight' => 35,
        'today'      => true,
    ];

    function __construct() {
        $this->seconds = 60 * 60 * 24;
        $this->cal     = new Calendar();
    }

    private function parse() {

        //dd($this->data);
        foreach ($this->data as $d) {

            $this->properties[] = [
                'name' =>  $d['label'],
                'info' => isset($d['info'])?$d['info']:null,
                'class' => isset($d['class'])?$d['class']:null,
            ];

            $events = [];

            foreach($d['events'] as $event)
            {
                //dd($event);
                $events[] = [
                    'start' => $start = strtotime($event['start']),
                    'end'   => $end = strtotime($event['end']),
                    'class' => $event['class'],
                    'label' =>  isset($event['label'])?$event['label']:'',
                    'url' =>  isset($event['url'])?$event['url']:'#',
                    'tooltip' => isset($event['tooltip'])?$event['tooltip']:null,
                    'callback' => isset($event['callback'])?$event['callback']:null,
                    'icon' => isset($event['icon'])?$event['icon']:'fa-pencil',
                ];

                if (!$this->first || $this->first > $start)
                    $this->first = $start;
                if (!$this->last || $this->last < $end)
                    $this->last = $end;
            }


            $this->blocks[] = [
                'label' => $d['label'],
                'events' => $events
            ];



        }

       // dd($this->first);
        if($this->first == false)
        {
            $this->first = strtotime(date('Y-m-d'));
        }
       // dd($this->first);
        if(!$this->last) $this->last = strtotime(date('Y-m-d'));

        $this->first = $this->cal->date($this->first);
        $this->last  = $this->cal->date($this->last);

        $current = $this->first->month();
        $lastDay = $this->last->month()->lastDay()->timestamp;

        // build the months
        while ($current->lastDay()->timestamp <= $lastDay) {
            $month          = $current->month();
            $this->months[] = $month;
            foreach ($month->days() as $day) {
                // if( $month == $current month && $day + $offset_days > $today)
                $this->days[] = $day;
            }
            $current = $current->next();
        }

//        $offset_days = 15;
//for($j=0; $j<$offset_days; $j++)
//{
//    array_shift($this->days);
//
//}

    }

    function render($data, $params = []) {

        $this->options = array_merge($this->defaults, $params);
        $this->data    = $data;


        $this->cellstyle = 'style="width: ' . $this->options['cellwidth'] . 'px; height: ' . $this->options['cellheight'] . 'px"';


        // parse data and find first and last date
        $this->parse();



        $html = [];

        // common styles
        $cellstyle  = 'style="line-height: ' . $this->options['cellheight'] . 'px; height: ' . $this->options['cellheight'] . 'px"';
        $wrapstyle  = 'style="width: ' . $this->options['cellwidth'] . 'px"';
        $totalstyle = 'style="width: ' . (count($this->days) * $this->options['cellwidth']) . 'px"';


        // start the diagram
        $html[] = '<figure class="gantt">';

        // set a title if available
        if ($this->options['title']) {
           $html[] = '<figcaption>' . $this->options['title'] . '</figcaption>';
        }

        // set sidebar with labels
        $html[] = $this->renderSidebar($cellstyle);

        // data section
        $html[] = '<section id="events-area" class="gantt-data">';

        // data header section
        $html[] = '<header>';

        // months headers
        $html[] = '<ul class="gantt-months" ' . $totalstyle . '>';
        foreach ($this->months as $month) {
            $html[] = '<li class="gantt-month" style="width: ' . ($this->options['cellwidth'] * $month->countDays()) . 'px"><strong ' . $cellstyle . '>' . $month->name() . '</strong></li>';
        }
        $html[] = '</ul>';

        // days headers
        $html[] = '<ul class="gantt-days" ' . $totalstyle . '>';
        //$html[] = '<li class="gantt-day" ' . $wrapstyle . '><span ' . $cellstyle . '>22</span></li>';

        foreach ($this->days as $day) {

            $weekend = ($day->isWeekend()) ? ' weekend' : '';
            $today   = ($day->isToday()) ? ' today' : '';

            $html[] = '<li class="gantt-day' . $weekend . $today . '" ' . $wrapstyle . '><span ' . $cellstyle . '>' . $day->padded() . '</span></li>';
        }
        $html[] = '</ul>';

        // end header
        $html[] = '</header>';

        // main items
        $html[] = '<ul class="gantt-items" ' . $totalstyle . '>';

        foreach ($this->blocks as $i => $block) {

            $html[] = '<li class="gantt-item">';
            // days
            $html[] = $this->createDaysHtml($wrapstyle, $cellstyle);
            // the block ovo zavri koliko je dogadjaja
            foreach($block['events'] as $event)
            {
                $html[] = $this->createSingleEventHtml($event, $i);
            }


            $html[] = '</li>';

        }

        $html[] = '</ul>';

        if ($this->options['today']) {

            // today
            $today  = $this->cal->today();
            $offset = (($today->timestamp - $this->first->month()->timestamp) / $this->seconds);
            $left   = round($offset * $this->options['cellwidth']) + round(($this->options['cellwidth'] / 2) - 1);

            if ($today->timestamp > $this->first->month()->firstDay()->timestamp && $today->timestamp < $this->last->month()->lastDay()->timestamp)
            {
                $html[] = '<time style="top: ' . ($this->options['cellheight'] * 2) . 'px; left: ' . $left . 'px" datetime="' . $today->format('Y-m-d') . '">Today</time>';
            }

        }

        // end data section
        $html[] = '</section>';

        // end diagram
        $html[] = '</figure>';

        return implode('', $html);

    }

    /**
     *
     * @param $event
     * @param $i
     * @return array
     * @author Ognjen Miletic
     */
    private function createSingleEventHtml($event, $i)
    {
        $days = (($event['end'] - $event['start']) / $this->seconds) + 0.20; // dodato 0.2
        $offset = (($event['start'] - $this->first->month()->timestamp) / $this->seconds) + 0.35; //dodato 0.35
        $top = round($i * ($this->options['cellheight'] + 1));
        $left = round($offset * $this->options['cellwidth']);
        $width = round($days * $this->options['cellwidth'] - 9);
        $height = round($this->options['cellheight'] - 8);
        $class = ($event['class']) ? ' ' . $event['class'] : '';
        $label = $event['label'];
        $url = $event['url'];
        $icon = $event['icon'];
        $tooltip = $event['tooltip'];

        if($days < 3) $label_shorten = null;
        elseif($days<8)
        {
            $slova = $days * 2 + 3;
            $label_shorten = substr($event['label'], 0, $slova).'...';
        }
        else $label_shorten = $event['label'];




        $html = '<span  class="gantt-block' . $class . '" style="left: ' . $left . 'px; width: ' . $width . 'px; height: ' . $height . 'px">';

        if($event['tooltip']) {
            $html .='<a href="'.$url.'" style="margin-top:3px; margin-left:5px;" class="btn blue"  data-placement="top" tabindex="'.$i.'" data-html="true" data-trigger="focus" data-toggle="popover" title="'.$label.'" data-content="'.$tooltip.'"><i class="fa '.$icon.'"></i></a>';
        }
        else{
            $html .='<a href="'.$url.'" style="margin-top:3px; margin-left:5px;" class="btn blue"><i class="fa '.$icon.'"></i></a>';
        }

        $html .= '<span style="display: inline-block; margin-top:3px; margin-left:5px; color:white;">'.$label_shorten.'</span>';
        $html .= '</span>';
        return $html;

    }

    private function createDaysHtml($wrapstyle, $cellstyle)
    {
        $html = '<ul class="gantt-days">';
      //  $html .= '<li class="gantt-day" ' . $wrapstyle . '><span ' . $cellstyle . '>Soba 303</span></li>';

        foreach ($this->days as $day) {

            $weekend = ($day->isWeekend()) ? ' weekend' : '';
            $today   = ($day->isToday()) ? ' today' : '';

            $html .= '<li class="gantt-day' . $weekend . $today . '" ' . $wrapstyle . '><span ' . $cellstyle . '>' . $day . '</span></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function renderSidebar($cellstyle)
    {
        // sidebar with labels
        $html = '<aside>';
        $html .= '<ul class="gantt-labels" style="margin-top: ' . (($this->options['cellheight'] * 2) + 1) . 'px">';
        foreach ($this->properties as $room) {
            $html .= '<li class="gantt-label"><strong ' . $cellstyle . '>  ' . $room['name'] . '<span class="label pull-right" style="background-color: '.$room['class'].'">'.$room['info'].'</span></strong></li>';
        }
        $html .= '</ul>';
        $html .= '</aside>';
        return $html;
    }

//    function __toString() {
//        //return $this->render();
//    }

}