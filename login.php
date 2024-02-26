<?php

$email = $_POST['email'];
$password = $_POST['password'];
 
$myconnection = mysqli_connect('localhost', 'root', '') 
   or die ('Could not connect: ' . mysql_error());

$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

$query = 'SELECT email FROM account WHERE email = ' . $email;
$result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysql_error());
if($result = NULL) {
  echo 'No matching email';
}
else
{
  $query = 'SELECT password FROM account WHERE email = ' . $email;
  $result = mysqli_query($mydb,$query) or die("Query Failed: " . mysql_error());
  if($resut != $password)
  {
    echo 'Incorrect Password';
  }
}
 ?>