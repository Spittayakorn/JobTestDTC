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
            
            width:100%;
            min-height:100vh;
            padding-left:5%;
            padding-right:5%;
            box-sizing:border-box;
            overflow:hidden;
        }
        .header{
            width:100%;
            height:30px;
            background-color:#598AC1;
            display:flex;
            padding:10px 0;
            justify-content:space-between;
            margin-top:10px;
            border:1px solid black;
            align-items:center;
            
        }
        .search{
            width:45%;
            display:flex;
            justify-content:space-between;
            padding:0 30px;
        }

        .start{
            width:45%;
        }
        .end{
            width:45%;     
        }
        button{
            padding:5px 20px;
            border-radius:5px;
            cursor:pointer;
            margin-right:30px;
        }

        input[type='date']{
            width: 80%;
            padding: 5px 5px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            
        }
    
        .content{
            width:100%;
            height:85vh;
            display:flex;
            border:1px solid black;
        }
        .dataPoint{
            width:30%;
            height:85vh;
        }
        .map{
            width:70%;
            height:85vh;
        }
        
        .dataTable {
            margin-top:5px;
            margin-left:2px;
            table-layout: fixed;
            width: 100%;
            white-space: nowrap;
            display:block;
            height:84.5vh;
            overflow-y:scroll;
            overflow-x:scroll;
            border:none;
            border-collapse: collapse;
        }
        
        .dataTable td,
        .dataTable th {
            text-align: left;
            padding: 5px 10px;
            border:1px solid black;
            overflow:hidden;
            white-space:nowrap;
        }

        .dataTable th{
            width:20%;
            text-align: center;
        }
    
    </style>

    <script>
        $(document).ready(apple = ()=>{
        
            var infowindow;
            var map;
            //var icon;
            var locations;
            var zom = 9;
            var cen = {
                        lat:13.85000,
                        lng:100.29440 
            };
            var type = 'roadmap';
            var myOptions = {  
                    zoom:zom,
                    center: new google.maps.LatLng(cen),
                    mapTypeId:type
            };
            
            var markers = {};
            
            map = new google.maps.Map(document.getElementById('map'),myOptions);
            
            var getMarkerUniqueId = (lat,lon) => {
                return parseFloat(lat).toFixed(5)+"_"+parseFloat(lon).toFixed(5);
            }

            var getLatLon = (lat,lon) => {
                return new google.maps.LatLng(lat,lon);
            }


            var countClick = 0;
            var getCountZero ;
            
            var changeFormatTime = (r_time)=>{
                var dayTime = new Array();
                var day_array = new Array();
                
                dayTime = r_time.split(" ");
                
                var day = dayTime[0];
                var time = dayTime[1];

                day_array = dayTime[0].split("-");
                var y = parseInt(day_array[0])+543;
                var m = day_array[1];
                var d = day_array[2];
                
                var format =  d+"/"+m+"/"+y+"<br>"+time;
                return format;
            }
            
            var changeFormatLocalTime = (r_time)=>{
                var dayTime = new Array();
                var day_array = new Array();
    
                dayTime = r_time.split(" ");
                
                var day = dayTime[0];
                var time = dayTime[1];

                var format =  day+"T"+time;
                return format;
            }
            
            var addLocationMySQL = (rId,time,lat,lon) =>{
                
                $.get("addLocationMySQL.php?rId="+rId+"&time="+time+"&lat="+lat+"&lon="+lon+"&getCountZero="+getCountZero,function(data,status){
                    if(status='success')
                    {
                        $('#showData').html('');
                        $('#start').val('');
                        $('#end').val('');
                        var text;
                        
                        $('#start').val(changeFormatLocalTime(data[0].r_time));
                        $('#end').val(changeFormatLocalTime(data[data.length-1].r_time));

                        for(var i = 0; i< data.length;i++)
                        {
                            text += "<tr><td>"+changeFormatTime(data[i].r_time)+"</td><td>"+data[i].r_lat+"</td><td>"+data[i].r_lon+"</td></tr>";
                        }
                        $('#showData').html(text);
                    }
                });
            }
            
            var reMoveStationPointMySQL = (r_id)=>{

                $.get("removeStationPointMySQL.php?r_id="+r_id+"&getCountZero="+getCountZero,function(data,status){
                    if(status=='success')
                    {
                        $('#showData').html('');
                        
                        if(!$.trim(data))
                        {
                            $('#start').val(''); 
                            $('#end').val('');
                        }else
                        {
                            $('#start').val(changeFormatLocalTime(data[0].r_time));     
                            $('#end').val(changeFormatLocalTime(data[data.length-1].r_time));    
                        }
                        
                        var text;
                        for(var i = 0; i< data.length;i++)
                        {
                            text += "<tr><td>"+changeFormatTime(data[i].r_time)+"</td><td>"+data[i].r_lat+"</td><td>"+data[i].r_lon+"</td></tr>";
                        }
                        $('#showData').html(text);
                    }
                });

            }

            var calAngle = (lat1,lon1,lat2,lon2) =>{
                
                //alert("lat1 : "+lat1 + " lat2: " + lat2 +" lon1 : "+ lon1+" lon2: " +lon2);
                var angle;
                var dlon = (lon2-lon1);
                var y = Math.sin(dlon)*Math.cos(lat2);
                var x = Math.cos(lat1)*Math.sin(lat2) - Math.sin(lat1)*Math.cos(lat2)*Math.cos(dlon);
                angle = Math.atan2(y,x);
                angle = angle*180/Math.PI;
                //alert(angle);
                return angle;
                
            }
            
            var lat1 = cen.lat;
            var lon1 = cen.lng;

            var check = 'false';

            var addMarker = map.addListener('click',(e)=>{
                //<---
                var angle = calAngle(lat1,lon1,e.latLng.lat(),e.latLng.lng());
                
                //var icon = {
                    /*
                    path: //"M 46.967 0 C 21.028 0 0 21.028 0 46.967 c 0 25.939 21.028 46.967 46.967 46.967 c 25.939 0 46.967 -21.027 46.967 -46.967 C 93.934 21.028 72.906 0 46.967 0 Z M 26.494 78.262 l 16.845 -31.295 L 26.494 15.672 l 51.614 31.295 L 26.494 78.262 Z",
                            google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    */
                    //fillColor: 'green',
                    //fillOpacity: 1,                    
                    //strokeWeight: 1,
                    //scale: 4
                    //rotation: angle
                //}

                var lat = e.latLng.lat();
                var lon = e.latLng.lng();
                //alert("Add Marker (center) : lat: "+lat1+" &lon: "+lon1);
                lat1 = lat;
                lon1 = lon;
                //alert("Add Marker (e.latLng) : lat: "+lat1+" &lon: "+lon1);
                var time = new Date().toLocaleString("th-TH",{timeZone:"Asia/Bangkok"});
                
                var markerId = getMarkerUniqueId(lat,lon);
                //alert(markerId);
                var marker = new google.maps.Marker({
                    position: getLatLon(lat,lon),
                    map:map,
                    //icon:icon,
                    //animation: google.maps.Animation.DROP,
                    id: 'marker_'+markerId,
                    html: "<div style='text-align:left;color:red;'>เวลา : "+time+"<br>ละติจูด : "+lat+"<br>ลองติจูด : "+lon+"</div>"
                });
                
                markers[markerId] = marker;

                if(countClick == 0)
                {   //เมื่อกดปุ่มเป็นครั้งแรก(marker ตัวแรก) ให้เก็บเวลาขณะนั้น นำไปแสดงตารางด้านข้าง และ ช่องข้อความค้นหา date_time local
                    getCountZero = time;              
                    check = 'true';   
                }
                
                addLocationMySQL(markerId,time,lat,lon);
                bindMarkerEvent(marker);
                bindMarkerInfo(marker);
                countClick +=1;
            });

            
            var delAllLessthanDateMarker = (timeS) =>{
                
                $.get("selectAllLessThanDateStationPoint.php?timeS="+timeS,function(data,status){
                    if(status=='success'){
                        for(var i = 0;i < data.length;i++)
                        {   
                            if(!$.trim(data))
                            {
                                
                            }else
                            {
                                //delete all marker
                                var markerId = data[i].r_id;
                                //alert(markerId);
                                var marker = markers[markerId];
                                
                                if(marker != null)
                                {   
                                    marker.setMap(null);
                                    delete markers[markerId];
                                }
                            }
                            
                        }
                    }
                });
            }

            //--
            var delAllMorethanDateMarker = (timeE) =>{
                
                $.get("selectAllMoreThanDateStationPoint.php?timeE="+timeE,function(data,status){
                    if(status=='success'){
                        for(var i = 0;i < data.length;i++)
                        {   
                            if(!$.trim(data))
                            {
                                
                            }else
                            {
                                //delete all marker
                                var markerId = data[i].r_id;
                                //alert(markerId);
                                var marker = markers[markerId];
                                
                                if(marker != null)
                                {   
                                    marker.setMap(null);
                                    delete markers[markerId];
                                }
                            }
                            
                        }
                    }
                });
            }
            //---
            var delAllMarker = () =>{
                $.get("selectAllStationPoint.php",function(data,status){
                    if(status=='success'){
                        for(var i = 0;i<data.length;i++)
                        {
                            //delete all marker
                            var markerId = data[i].r_id;
                            //alert(markerId);
                            var marker = markers[markerId];
                                
                            if(marker != null)
                            {   
                                marker.setMap(null);
                                delete markers[markerId];
                            }

                        }
                    }
                });
            }


            $('#showDataMarker').click(function(){
                
                var timeS = $('#start').val();
                var timeE = $('#end').val();
                var clat1 ;
                var clon1 ;
                
                if(check=='true' ){
                    clat1 = cen.lat;
                    clon1 = cen.lng; 
                }
                

                if(timeS !='' && timeE !='')
                {
                    $.get("selectStationPointFromDate.php?timeS="+timeS+"&timeE="+timeE,function(data,status){
                        if(status=='success'){
                            var text='';
                            
                            $('#showData').html('');
                            
                            countClick = 0;
                            
                            if(!$.trim(data))
                            {
                                delAllMarker();    
                            }
                            else{
                                //กรณีน้อยกว่า เวลายังแสดงอยู่ (ทำ)
                                delAllLessthanDateMarker(timeS);
                                delAllMorethanDateMarker(timeE);
                                
                                for(var i = 0;i<data.length;i++)
                                {
                                    //delete all marker
                                    var markerId = data[i].r_id;
                                    //alert(markerId);
                                    var marker = markers[markerId];
                                
                                    if(marker != null)
                                    {   
                                        marker.setMap(null);
                                        delete markers[markerId];
                                    }


                                    text += "<tr><td>"+changeFormatTime(data[i].r_time)+"</td><td>"+data[i].r_lat+"</td><td>"+data[i].r_lon+"</td></tr>";    
                                
                                    //creat marker Again
                                    //var icon = {
                                        /*
                                        path: //"M 46.967 0 C 21.028 0 0 21.028 0 46.967 c 0 25.939 21.028 46.967 46.967 46.967 c 25.939 0 46.967 -21.027 46.967 -46.967 C 93.934 21.028 72.906 0 46.967 0 Z M 26.494 78.262 l 16.845 -31.295 L 26.494 15.672 l 51.614 31.295 L 26.494 78.262 Z",
                                            google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                            */
                                            //fillColor: 'green',
                                            //fillOpacity: 1,                    
                                            //strokeWeight: 1,
                                            //scale: 4
                                            /*
                                            rotation: calAngle(
                                                clat1,
                                                clon1,
                                                data[i].r_lat,
                                                data[i].r_lon
                                            )//data[i].angle
                                            */
                                            
                                            
                                    //}
                                
                                    //alert("Click Show(center) : lat : "+ clat1 + " &lon: " + clon1);
                                    lat1 = data[i].r_lat;
                                    lon1 = data[i].r_lon;
                                    //ไม่มั่นใจ <<<<<<<<<<<<<<<<<<<<<<<เเนื่องจากเก็บ angleในฐานข้ออมูล
                                    clat1 = data[i].r_lat;
                                    clon1 = data[i].r_lon;
                                    //alert("Click Show(e.LntLng) : lat : "+ lat1 + " &lon : " + lon1);
                                
                                    var time = changeFormatTime(data[i].r_time);
                                    time = time.replace('<br>',' ');

                                    var markerIdn = data[i].r_id;
                                    //alert(markerIdn);
                                
                                    var markern = new google.maps.Marker({
                                        position: getLatLon(data[i].r_lat,data[i].r_lon),
                                        map:map,
                                        //icon:icon,
                                        //animation: google.maps.Animation.DROP,
                                        id: 'marker_'+markerIdn,
                                        html: "<div style='text-align:left;color:red;'>เวลา : "+time+"<br>ละติจูด : "+data[i].r_lat+"<br>ลองติจูด : "+data[i].r_lon+"</div>"
                                    });
                
                                    markers[markerIdn] = markern;
                                
                                    if(countClick == 0)
                                    {   //เมื่อกดปุ่มเป็นครั้งแรก(marker ตัวแรก) ให้เก็บเวลาขณะนั้น นำไปแสดงตารางด้านข้าง และ ช่องข้อความค้นหา date_time local
                                        getCountZero = time;              
                                        check = 'true';   
                                    }
                
                                    bindMarkerEvent(markern);
                                    bindMarkerInfo(markern);
                                    countClick +=1;
                                
                                }

                            }
                            
                            $('#showData').html(text);
                        }
                    });   

                }else{
                    alert('กรุณากรอกวัน/เดือน/ปี ชั่วโมง:นาที:วินาทีให้สมบูรณ์');
                }
            });
            //--
            var bindMarkerEvent = (marker) => {                
                marker.addListener('rightclick',(e)=>{
                    var markerId = getMarkerUniqueId(e.latLng.lat(),e.latLng.lng());
                    var marker = markers[markerId];
                    removeMarker(marker,markerId);
                });
            }

            var removeMarker = (marker,markerId) =>{
                marker.setMap(null);
                delete markers[markerId];
                reMoveStationPointMySQL(markerId);

            }
            
            var bindMarkerInfo = (marker) =>{
                marker.addListener('click',(e)=>{
                    
                    var markerId = getMarkerUniqueId(e.latLng.lat(),e.latLng.lng());
                    var marker = markers[markerId];
                    infowindow = new google.maps.InfoWindow();
                    infowindow.setContent(marker.html);
                    infowindow.open(map,marker);
                });
            }
            
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="search">
                <div class="start">
                    <b><label for="start">เริ่ม : </label></b>
                    <input type="datetime-local" name="start" step='1' id='start' >
                </div>
                <div class="end">
                    <b><label for="end">ถึง : </label></b>
                    <input type="datetime-local" name="end" step='1' id='end'>
                </div>
            </div>
            <button id='showDataMarker'>แสดงข้อมูล</button>
        </div>

        <div class="content">
            <div class="dataPoint">
                <table class="dataTable">
                    <thead>
                        <tr>
                            <th>r_time</th>
                            <th>r_lat</th>
                            <th>r_lon</th>
                        </tr>
                    </thead>
                    <tbody id='showData'>
                    </tbody>
                </table>
            </div>
            <div class="map" id="map">
            </div>
        </div>
    </div>
</body>
</html>