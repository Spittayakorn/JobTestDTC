<?php
    session_start();
    require('connectDB.php');

    $uid = $_REQUEST['txt-uid'];
    $upass = $_REQUEST['txt-upass'];

    if(!empty($_REQUEST['remem']))
    {    
        setcookie('ctxt-uid',$uid,time()+ (10*365*24*60*60));
        setcookie('ctxt-upass',$upass,time()+ (10*365*24*60*60));
    }else{
        if(isset($_COOKIE["ctxt-uid"])){
            setcookie('ctxt-uid','');
        }
        if(isset($_COOKIE["ctxt-upass"])){
            setcookie('ctxt-upass','');
        }        
    }

    $md5_uid = md5($uid);
    $md5_upass = md5($upass); 

    $selUser = "select * from user where uid='$md5_uid' and upass ='$md5_upass'";
    $selQUser = mysqli_query($con,$selUser);

    if($selQUser == null)
    {
        echo "คำสั่งผิด";
    }

    $selNumUser = mysqli_num_rows($selQUser);
    
    if($selNumUser == '0')
    {
    ?>
        <script>
            alert('ไม่พบข้อมูล');
            window.open('login.php','_self');
        </script> 
        <?php
    }
    else
    {
        ?>
        <script>
            alert('ยินดีต้อนรับเข้าสู่ระบบ');
            window.open('station.php','_self');                   
        </script> 
        <?php
    }
    
    


    
?>