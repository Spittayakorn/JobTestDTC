<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .containner{
            width: 50%;
            background: white;
            margin: 10px 5px;
            border:1px solid turquoise;
        }
        .login{
            margin: 0 3px;          
        }
        .header{
            background-color:gray;
            color:white;
            text-align: center;
            padding: 10px 0;
        }
        
        .uid p{
            display:inline-block;
            width: 30%;

        }
        .upass p{
            display:inline-block;
            width: 30%;

        }
        input[type='text'],input[type='password']{
            width: 50%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .confirm{
            text-align: right;
            margin-bottom: 5px;
        }
        input[type=submit]{
            padding: 10px 15px;
            background-color: white;
            border-color:turquoise;
            color:turquoise;
        }
    </style>

</head>
<body>
    
    <div class="containner">
    <form action="checkLogin.php" method="Post">
        <div class="login">
            <div class="header">
                Log in
            </div>
            
            <div class="content">
            <div class="uid">
                <p>User Name:</p>
                <input type="text" name='txt-uid' value="<?php if(isset($_COOKIE["ctxt-uid"])) { echo $_COOKIE["ctxt-uid"]; } ?>">
            
            </div>
            
            <div class="upass">
                <p>Password:</p>
                <input type="password" name='txt-upass' value="<?php if(isset($_COOKIE["ctxt-upass"])) { echo $_COOKIE["ctxt-upass"]; } ?>">
            </div>
            
            </div>
            <div class="remem">
            <input type="checkbox" name='remem' <?php if(isset($_COOKIE["ctxt-uid"])) { ?> checked <?php } ?>>Remember me next time.
        </div>
        <div class="confirm">
            <input type="submit" value="Log in" >
        </div>
        </div>
        
        
        </form>
    </div>
    
    
</body>
</html>