<?php
    session_start();
    require('connectDB.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSkPASxTuSggDVH6TIehVUqVRPybJ5wO4&libraries=&v=weekly" defer></script>
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        .container{
            width: 100%;
            min-height: 100vh;
            padding-left: 8%;
            padding-right: 8%;
            box-sizing: border-box;
            overflow:hidden;
        }
        .content{
            width: 100%;
            height: 60vh;
            margin-top:50px;
            display: flex;
            
        }
        .box-1{
            width:40%;
            height: 55vh;
            border: 1px solid black;
            display: inline-block;
            vertical-align: top;
        }
        .box-1 select{
            padding: 10px;
            width: 100%;
            overflow-y: hidden;
            border:0px;
            outline:0px;
        }
        .box-2{
            width:20%;
            height: 50vh;
            text-align: center;
            padding: 0 10px;
        }
        .box-2 p{
            margin: 30px 0;
            width: 100%;
        }
        input[type='button'] {
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            width: 100%;
        }
        .box-3{
            width:40%;
            height: 55vh;
            display: inline-block;
            vertical-align: top;
            border: 1px solid black;
        }
        .box-3 select{
            padding: 10px;
            width: 100%;
            overflow-y: hidden;
            border:0px;
            outline:0px;
        }
        table,tr,td{
            
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        .map{
            background: blue;
            width:  500px;
            height: 600px;
            margin: 50px;
            display:none;
        }
    
    </style>
    <script>
        $(document).ready(function(){

            function getStation(value)
            {
                $.get("getStation.php?station_id="+value,function(data,status){
                        if(status=='success'){
                            for(i=0;i<data.length;i++)
                            {
                                show = "<tr><td>"+data[i].station_id+"</td><td class='getName'>"+data[i].station_name+"</td><td class='getLat'>"+data[i].s_lat+"</td><td class='getLon'>"+data[i].s_lon+"</td></tr>";
                                $('#showLocation').html(show);

                            }
                        }
                    });
            }
            
            getStation($('#source option:selected').val())

            $('.box-1').on('click','#source option',function(){
                
                var show ='';
                $('#source option:selected').each(function(){
                    var value = $('#source option:selected').val();
                    getStation(value);
                });
                
            });

            $('.box-3').on('click','#destination option',function(){
                
                var show ='';
                $('#destination option:selected').each(function(){
                    var value = $('#destination option:selected').val();
                    getStation(value);
                    
                });
            });
            
            $('#LtoR').click(function(){
                $('#source option:selected').each(function(){
                    $('#destination').append("<option value="+this.value+" selected>"+this.text+"</option>");
                });      
            });
            
            $('#RtoL').click(function(){
                $('#destination option:selected').each(function(){
                    $('#source').append("<option value="+this.value+" selected>"+this.text+"</option>");
                });      
            });

            $('#moveallL').click(function(){
                $('#source option').each(function(){
                    $('#destination').append("<option value="+this.value+" selected>"+this.text+"</option>");
                });       
            });

            $('#moveallR').click(function(){
                $('#destination option').each(function(){
                    $('#source').append("<option value="+this.value+" selected>"+this.text+"</option>");
                });       
            });

            //-------------------
            function loadMap(){
                var cen = {
                        lat:13.85,
                        lng:100.2944 
                };
                var zom = 12;
                var type = 'terrain';
                var mapOptions = {
                    zoom:zom,
                    center:cen,
                    mapTypeId:type
                };
                //alert(JSON.stringify(mapOptions));                            
                var maps = new google.maps.Map(document.getElementById('map'),mapOptions);
                var ary=[];

                $(function(){
                    $('#showLocation').each(function(a){
                        var name = $('.getName',a).text();
                        var lat = $('.getLat',a).text();
                        var lon = $('.getLon',a).text();
                        ary.push({Name: name, Lat : lat, Lon: lon});
                    });
                    //alert(JSON.stringify(ary));
                    $.each(ary,function(i,item){
                        //alert(item.Lat +" " + item.Lon +" "+item.Name); 
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(item.Lat,item.Lon),
                            map:maps,
                            title:item.Name
                        });

                        info = new google.maps.InfoWindow();
                        
                        google.maps.event.addListener(marker,'click',(function(marker,i){
                            return function(){
                                var text = "<div style='text-align:left;color:red;'>"+item.Name +"<br>ละติจูด : "+item.Lat+"<br>ลองจิจูด : "+item.Lon+"</div>";
                                info.setContent(text);
                                info.open(maps,marker);
                            }
                        })(marker,i));

                    }); 
                });
            /*
                var lat = $('.getLat').text();
                var lon = $('.getLon').text()
                var name = $('.getName').text();
                
                alert(lat +" " + lon + " " + name);
            */  
            }

            $('#showMap').click(function(){
                    $('#map').css('display','block');
                    loadMap();
                }
            );
        //----------------
        });        
    </script>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="box-1">
                <select id="source" size="20">
                    <?php
                        $sqlSelStat = "select * from station;";
                        $sqlQSelStat = mysqli_query($con,$sqlSelStat);

                        if($sqlQSelStat == null){
                            echo "คำสั่งผิด";
                        }
                        
                        $sqlNumRowSelStat = mysqli_num_rows($sqlQSelStat);
                        if($sqlNumRowSelStat != 0){
                            while($sqlfetchSelStat = mysqli_fetch_array($sqlQSelStat)){

                                if($sqlfetchSelStat[0]=='1')
                                {
                                    echo "<option value='$sqlfetchSelStat[0]' selected>$sqlfetchSelStat[1]</option> ";
                                }else{
                                    echo "<option value='$sqlfetchSelStat[0]' >$sqlfetchSelStat[1]</option> ";
                                }   
                            }
                        }else{
                        }
                    ?>
                </select>
            </div>
            <div class="box-2">
                <p><input type="button" value=">" id='LtoR'></p>
                <p><input type="button" value="<" id='RtoL'></p>
                <p><input type="button" value=">> Move all" id='moveallL'></p>
                <p><input type="button" value="<< Move all" id='moveallR'></p>
                <p><input type="button" value="แสดงในแผนที่" id='showMap'></p>
            </div>
            <div class="box-3">
                <select id="destination" size="20">
                </select>
            </div>
        </div>
        <div class="showData">
            <center>
            <table border='1' >
                <tr>
                    <th>Station_ID</th>
                    <th>Station_Name</th>
                    <th>S_Lat</th>
                    <th>S_Lon</th>
                </tr>
                <tbody id='showLocation'>
                </tbody>
            </table>
            </center>
        </div>
        <center>
            <div class="map" id="map">
            </div>
        </center>
    </div>
</body>
</html>

<script>
/*
                    onclick="listbox_move('source','destination')"
                    onclick="listbox_move('destination','source')"
                    onclick="listbox_move_all('source','destination')"
                    onclick="listbox_move_all('destination','source')"
                    */
    /*
    
    function createOption(value,text){
                var newOption = document.createElement('option');
                newOption.value = value;
                newOption.text = text;
                //newOption.selected = true;
                return newOption;
            }

            function listbox_move(source,desctination){
                var src = document.getElementById(source);
                var dest = document.getElementById(desctination);

                for(var count=0; count <src.options.length ; count++)
                {            
                    if(src.options[count].selected)
                    {   
                        var option = src.options[count];
                        dest.appendChild(createOption(option.value,option.text));
                        //src.remove(count);      
                    }   
                }     
            }
function listbox_move_all(source,desctination){
                var src = document.getElementById(source);
                var dest = document.getElementById(desctination);

                for(var count=0; count <src.options.length ; count++)
                {   
                    var option = src.options[count];
                    dest.appendChild(createOption(option.value,option.text));     
                }                  
                //src.innerHTML='';     
            }            
    */
</script>
