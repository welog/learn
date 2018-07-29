<!DOCTYPE html>
<html>
<head>
    <meta charset=UTF-8 />
    <title>Document</title>
</head>
<body>
    <?php
   $dbhost = 'localhost:3306';  //mysql服务器主机地址
   $dbuser = 'guest';      //mysql用户名
   $dbpass = 'guest123';//mysql用户名密码
   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
   if(! $conn )
   {
     die('Could not connect: ' . mysql_error());
   }
   echo 'Connected successfully';
   mysql_close($conn);
?>
</body>
</html>