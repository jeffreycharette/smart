<?php
/*
Property of IOMATIX Inc.
You may not alter, replicate, or reuse the contents in this file.
May 11th, 2006
*/
class Timer
{
    var $timers = array();

    function Timer()
    {
        // Nothing
    }

    function timerStart($name = 'default')
    {
        $time_portions = explode(' ',microtime());
        $actual_time = $time_portions[1].substr($time_portions[0],1);
        $this->timers["$name"] = $actual_time;
    }

    function timerStop($name = 'default')
    {
        $time_portions = explode(' ',microtime());
        $actual_time = $time_portions[1].substr($time_portions[0],1);
                $elapsed_time = $actual_time - $this->timers["$name"];
//        $elapsed_time = bcsub($actual_time, $this->timers["$name"], 6);
        return $elapsed_time;
    }
}
?>