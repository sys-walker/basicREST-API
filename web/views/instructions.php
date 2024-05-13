<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dockerized API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/css/homepage.css" >
</head>
<body>
<?php
require_once "/var/www/html/views/parts/sidenav.php";
?>

<div class="content">

  <h1> GET</h1>
  <ul>
    <p>URL v0: http://localhost/api/itlang/list</p>
    <p>URL v1: http://localhost:[port]/api/itlang/list?limit=[n (optional)]</p>
    <p>URL v2: http://localhost:[port]/api/itlang/list</p>
    <p><b>[port] it is not necessary unless if you have changed it in dockerfile</b></p>
      
    
  </ul>
  <h1> POST</h1>
  
  <ul>
    <p>http://localhost/api/itlang/add </p>
    <div>
    <code>{</code><br>
      <div style="margin-left:15px">
        <code> "name":"W++"</code><br>
        <code> "documentation_url":"wlang.org.wk",</code><br>
        <code> "description":"W++",</code>  <br>
        <code>"comment":"Wakandian C++"}</code><br>
      </div>
      <code>}</code><br>
    </div>


    <p>[name] => Required</p>
    <p>[doc] => Required</p>
    <p>[desc] => optional (default "")</p>
    <p>[comm] => optional (deafult "")</p>
  </ul>

</div>
</body>
</html>

