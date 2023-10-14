<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
        function is_open($time, $day, $holiday, $date)
        {
            if (in_array($date, $holiday)) {
                return false;
            } else if (($day >= 1 && $day <= 5) && ($time >= 8 && $time < 20)) {
                return true;
            } elseif ($day == 6 && $time >= 9 && $time < 14) {
                return true;
            } else {
                return false;
            }
        }
    
        date_default_timezone_set('Europe/Zagreb');
        $now = date('H');
        $today = date('N');
        $holidays = array('1.01', '6.01', '9.04', '10.04', '1.05', '30.05', '8.06', '22.06', '5.08', '15.08', '1.11', '18.11', '25.12', '26.12');
        $today_date = date('j.m');
    
        if (is_open($now, $today, $holidays, $today_date)) {
            echo "The supermarket is open.";
        } else {
            echo "The supermarket is closed.";
        }
    ?>
</body>
  
</html>
