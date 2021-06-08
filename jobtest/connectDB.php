<?php
    
    $con = mysqli_connect('127.0.0.1','root','','testjob');

    if($con ==null)
    {
        echo "คำสั่งผิด";
        exit;
    }

    mysqli_query($con,'SET NAMES UTF8');

?>