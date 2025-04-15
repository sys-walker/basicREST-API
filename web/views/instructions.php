<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dockerized API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/css/homepage.css" >
  <link rel="stylesheet" type="text/css" href="/css/use.css" >
</head>
<body>
  <?php
  require_once "/var/www/html/views/parts/sidenav.php";
  ?>

  <div class="content">

    <h1> GET</h1>
    <ul>
      <li>
        <p onclick=window.open(getExample('/api/itlang/list'))> http://localhost/api/itlang/list</p>
        <p class="comment">List all itlang items</p>
      </li>
      <li>
        <p onclick=window.open(getExample('/api/itlang/list?limit=3'))> http://localhost/api/itlang/list?limit=&lt;num&gt;</p>
        <p class="comment" style="color:red !important;">Not implemented</p>
      </li>
      <li>
        <p onclick=window.open(getExample('/api/itlang?id=3'))> http://localhost/api/itlang?id=&lt;num&gt;</p>
        <p class="comment">Get item with id</p>
      </li>
      <li>
        <p onclick=window.open(getExample('/api/itlang/1'))> http://localhost/api/itlang/&lt;num&gt;</p>
        <p class="comment">Get item with id</p>
      </li>
    </ul>
    <h1> POST</h1>
    
    <ul>
       <li>
        <p> http://localhost/api/itlang</p>
        <p>
          {<br>
          &nbsp;&nbsp;"name"&nbsp;:&nbsp;"Z++",<br>
          &nbsp;&nbsp;"documentation_url"&nbsp;:&nbsp;"zlang.lol",<br>
          &nbsp;&nbsp;"description"&nbsp;:&nbsp; <span>(optional)</span><br>
          &nbsp;&nbsp;"comment"&nbsp;:&nbsp;<span>(optional)</span><br>
          }
        </p>
        <p class="comment">Add element to table</p>
      </li>
    </ul>

  </div>

  <script>

    
    function getExample(endpoint){
        let arr =(window.location.href).split(/[\/\/,\/]+/);
      let proto = arr[0];
      let host = arr[1];
      return proto+"//"+host+endpoint; 



      

    }
    function jsonToTxt(json){
      return JSON.stringify(json, null, 2);
    }
  </script>
</body>
</html>

