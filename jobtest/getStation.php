<?php
    header('Content-type: application/json; charset=UTF-8');


    $station_id = $_GET['station_id'];
    require('connectDB.php');

    $sqlSelStat = "select * from station where station_id='$station_id';";
    $sqlQSelStat = mysqli_query($con,$sqlSelStat);

    if($sqlQSelStat == null)
    {
        echo "คำสั่งผิด";
    }

    $i=0;
    $result_json = array();

    while($sqlFSelStat = mysqli_fetch_array($sqlQSelStat))
    {
        $record_json = new stdClass();
        $record_json->station_id = $sqlFSelStat[0];
        $record_json->station_name = $sqlFSelStat[1];
        $record_json->s_lat = $sqlFSelStat[2];
        $record_json->s_lon = $sqlFSelStat[3];
        $result_json[$i] = ($record_json);
        $i++;
    }

    echo json_encode($result_json);

?>