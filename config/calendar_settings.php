<?php

return [
    'defaultDate' => date('Y-m-d'),
    'slotDuration' => '00:15',
    'minTime' => '09:00:00',
    'maxTime' => '22:00:00',
    'weekends' => 0, //1 will enable sundays and saturdays 0 would disable them.
    'startDate' => date('Y-m-d'),
    'repeatTypes' => [
        'weekly' => 7,
        'monthly' => 30,
//        'yearly' => 365,
        'custom' => 'custom',
    ],
    'endDate' => '',
    'eventColor' => '#cdf6f7',
    'delimeter' => '___',
    'time_zone' => 'Asia/Calcutta',
    'dayList' => array(
        0 => 'Sun',
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thur',
        5 => 'Fri',
        6 => 'Sat'
    )
];