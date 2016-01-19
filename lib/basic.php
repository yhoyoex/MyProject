<?php


function printr($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function printrt($data){
    ?>
    <pre>
    <table border="1">
        <tr>
            <?php 
            foreach($data[0] as $key => $val){
                echo "<td>" . $key . "</td>";
            }
            ?>
        </tr>
        <?php
        foreach($data as $dat) {
            echo "<tr>";
            foreach($data[0] as $key => $val){
                echo "<td>" . $dat[$key] . "</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    </pre>
    <?php
}

$time_marker = 0;
function mark_timer(){
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $time_marker = $time;
}
function end_and_print_timer(){
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;javascript:;
    
    $total_time = round(($finish - $timer_marker), 4);
    echo $total_time;
}

function get_timeago( $ptime )
{
    $estimate_time = time() - $ptime;

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array( 
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return  $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }
}


?>