<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dockerized API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
 
  <p><a href="/phpMyAdmin">phpMyAdmin</a></p>
  <p><a href="/">Go to mainpage</a></p>
  <h1> GET</h1>
  <ul>
    <p>URL v1: http://localhost:[port]/api.php/itlang/list?limit=[n (optional)]</p>
    <p>URL v2: http://localhost:[port]/api.php/itlang/list</p>
    <p><b>[port] it is not necessary unless if you have changed it in dockerfile</b></p>
      
    
  </ul>
  <h1> POST</h1>
  
  <ul>
    <p>http://localhost/api.php/itlang/list?[name=&ltname&gt]&[doc=&ltdoc url&gt]&[desc=&ltdescription&gt]&[comm=&ltcomment&gt] </p>
    <p>[name] => Required</p>
    <p>[doc] => Required</p>
    <p>[desc] => optional (but they are ignored and replaced by "test1")</p>
    <p>[comm] => optional (but they are ignored and replaced by "test2")</p>
  </ul>

</body>
</html>

