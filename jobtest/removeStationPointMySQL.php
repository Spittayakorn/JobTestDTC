<?php

    header('Content-type: application/json; charset=UTF-8');
    date_default_timezone_set("Asia/Bangkok");

    $r_id = $_GET['r_id'];
    $getCountZero = $_GET['getCountZero'];

    function getDateTime($time){

        $spliteTime = explode(' ',$time);
        $date = $spliteTime[0];
        $time = $spliteTime[1];
        $spliteDate = explode("/",$date);
        $day = $spliteDate[0];
        $month = $spliteDate[1];
        $year = $spliteDate[2]-543;
        $timeStamp = $year."-".$month."-".$day." ".$time;
        
        return $timeStamp;
    }
    
    require('connectDB.php');
    
    $sqlDelStatPoint = "delete from stationpoint where r_id='$r_id';";
    $sqlQDelStatPoint = mysqli_query($con,$sqlDelStatPoint);

    $sqlSelStatPoint = "select * from stationpoint where r_time between '".getDateTime($getCountZero)."' and '9999-11-11 00:00:00'  order by r_time;";
    $sqlQSelStatPoint = mysqli_query($con,$sqlSelStatPoint);

    if($sqlQSelStatPoint == null)
    {
        echo "คำสั่งผิด2";
    }

    $sqlNumRow = mysqli_num_rows($sqlQSelStatPoint);
    
    $result_json = array();
    $i=0;
    
    if($sqlNumRow != 0)
    {
        while($sqlFechSelStatPoint = mysqli_fetch_array($sqlQSelStatPoint)){
            
            $recode_json = new stdClass();
            $recode_json->r_id = $sqlFechSelStatPoint[0];
            $recode_json->r_time = $sqlFechSelStatPoint[1];
            $recode_json->r_lat = $sqlFechSelStatPoint[2];
            $recode_json->r_lon = $sqlFechSelStatPoint[3];
            $result_json[$i] = $recode_json;
            $i++; 
        }
    }
    
    echo json_encode($result_json);

?>