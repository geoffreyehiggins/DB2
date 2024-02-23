<?php

$email = $_POST['email'];
$password = $_POST['password'] 
 
$myconnection = mysqli_connect('localhost', 'root', '') 
   or die ('Could not connect: ' . mysql_error());

 $mydb = mysqli_select_db ($myconnection, 'movie') or die ('Could not select database');

 ?>