<?php

namespace App\Helpers;

class DateHelper
{
    public function convertDateToBahasa($date)
    {
        $date = date("l, d F Y", strtotime($date));

        $explodeMonth = explode(' ', $date);
        $month = $this->convertMonthToBahasa($explodeMonth[2]);

        $explodeDay = explode(',', $date);
        $day = $this->convertDayToBahasa($explodeDay[0]);

        $date = $day.', '.$explodeMonth[1].' '.$month.' '.$explodeMonth[3];

        return $date;
    }

    public static function convertMonthToBahasa($month)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        return $months[$month] ?? $month;
    }

    public static function convertDayToBahasa($day)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        return $days[$day] ?? $day;
    }
}
