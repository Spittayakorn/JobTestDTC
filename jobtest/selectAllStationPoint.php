<?php

    header('Content-type: application/json; charset=UTF-8');
    date_default_timezone_set("Asia/Bangkok");

    require('connectDB.php');
    
    $sqlSelStatPoint = "select * from stationpoint order by r_time;";
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