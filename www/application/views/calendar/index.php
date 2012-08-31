<?php defined('SYSPATH') or die('No direct script access.');

$time = Date::today_if_null($date_start);
$prev_month = mktime(0,0,0,date('m', $time),date('d', $time) - date('t', $time),date("Y",$time));
$next_month = mktime(0,0,0,date('m', $time),date('d', $time) + date('t', $time),date("Y",$time));
echo '<table class="calendar">';
    echo '<caption>';
        echo '<span class="prev_month">';
            echo HTML::anchor(
                'calendar/?date='.date('Y-m-d',$prev_month),
                '&nbsp;',array(
                    'class' => 'prev_month',
                    'id' => 'change_month',
                    'title' => UTF8::ucfirst('previous_month'),
                )
            );
        echo '</span>';
        echo '<b>';
            echo UTF8::ucfirst(strtolower(date('F', $time )));
            echo '&nbsp;';
            echo date('Y', $time );
        echo '</b>';

        echo '<span class="next_month">';
            echo HTML::anchor(
                'calendar/?date='.date('Y-m-d',$next_month),
                '&nbsp;',
                array(
                    'class' => 'next_month',
                    'id' => 'change_month',
                    'title' => UTF8::ucfirst('next_month')
                )
            );
        echo '</span>';
    echo '</caption>';
    echo '<thead>';
        foreach (Date::week_days($date_start) as $day_name)
        {
            echo '<th>';
                echo UTF8::ucfirst(strtolower($day_name));
            echo '</th>';
        }
    echo '</thead>';
    echo '<tbody>';
        $start_of_month = Date::start_of_month($date_start);
        $end_of_month = Date::end_of_month($date_start);
        $firstday = Date::start_of_week($start_of_month);
        $lastday = Date::end_of_week($end_of_month);
        $day = $firstday;

        $day_counter = 1;
        while( $day < $lastday )
        {
            $class = '';
            $special_type = '';

            if($day < $start_of_month)
            {
                $class .= ' lastmonth';
            }
            else if($day > $end_of_month)
            {
                $class .= ' nextmonth';
            }
            if(date('j n Y', time()) == date('j n Y', $day))
            {
                $class .= ' today';
            }

            if($day_counter % 7 == 1)
            {
                echo '<tr>';
            }

            $weekends = date('w', $day);
            if ( $weekends == 0 || $weekends == 6)
            {
                $class .= ' weekends';
            }

            $date = date('Y-m-d', $day);

            echo '<td title="'.UTF8::ucfirst('click_for_details').'" class="'.$class.'" data-date="'. $date .'">';
            if (date('j n Y', time()) == date('j n Y', $day))
            {
                echo '<span id="today-label" class="label label-success">';
                    echo UTF8::ucfirst('today');
                echo '</span>';
            }
            echo '<div class="date" >';
                    echo '<span title="'. $date .'">';
                        echo date('d', $day);
                    echo '</date>';
            echo '</div>';
                echo '<br/>';

                echo '</ul>';
            echo  '</td>';
            if($day_counter % 7 == 0)
            {
                echo  '</tr>';
            }
            $day = $day + Date::DAY;
            $day_counter++;
        }

    echo '</tbody>';
echo '</table>';
