<?php
namespace App\Library;

class DateFormat
{
    /**
     * 将 Y-m-d H:i:s 字符串格式转成对应去掉空格和符号的格式
     * @param  string  $date_string 字符串日期
     * @param  string  $format      格式 : hour, day, month, year
     * @return int     hour: YmdH
     *                 day: Ymd
     *                 month: Ym
     *                 year: Y
     */
    public static function string2int($date_string, $format)
    {
        switch ($format) {
            case 'hour':
                return (int)Date("YmdH", strtotime($date_string.":00:00"));
                break;
            case 'day':
                return (int)Date("Ymd", strtotime($date_string." 00:00:00"));
                break;
            case 'month':
                return (int)Date("Ym", strtotime($date_string."-01 00:00:00"));
                break;
            case 'year':
                return (int)Date("Y", strtotime($date_string."01-01 00:00:00"));
                break;
        }
    }
    
    /**
     * 将 string2int函数生成的格式转成对应的 Y-m-d H:i:s格式
     * @return string
     */
    public static function int2string($date_int)
    {
        switch (strlen($date_int)) {
            case 4: //year
                return (string)$date_int;
                break;
            case 6: //month
                return substr($date_int, 0, 4)."-".substr($date_int, 4);
                break;
            case 8: //day
                return substr($date_int, 0, 4)."-".substr($date_int, 4,2)."-".substr($date_int, 6);
                break;
            case 10: //hour
                return substr($date_int, 0, 4)."-".substr($date_int, 4,2)."-".substr($date_int, 6,2)." ".substr($date_int, 8);
                break;
        }
    }
}
?>
