<?php

namespace App\Traits;

use DateTime;

trait DateFormat
{

    function format_date($date_to_format)
    {
        $date = DateTime::createFromFormat('n/j/Y', $date_to_format);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        return $formattedDate;
    }

    public function format_time($time, $format = 'h:i:s A', $newFormat = 'H:i:s')
    {
        $dateTime = DateTime::createFromFormat($format, $time);
        return $dateTime ? $dateTime->format($newFormat) : null;
    }

}