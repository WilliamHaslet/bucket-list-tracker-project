<?php
$username = 'wlh4dh'; 
$password = 'a';
$host = 'mysql01.cs.virginia.edu';
$dbname = 'wlh4dh_a';
$dsn = "mysql:host=$host;dbname=$dbname";

try 
{
   $db = new PDO($dsn, $username, $password);
   // echo "<p>You are connected to the database</p>";
}
catch (PDOException $e)
{
   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}
?>