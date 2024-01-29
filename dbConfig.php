<?php 
// SQL server configuration 
$serverName = "PC-3\SQLEXPRESS"; 
$dbUsername = ""; 
$dbPassword = ""; 
$dbName     = "codex_db"; 
 
// Create database connection 
try {   
   $conn = new PDO( "sqlsrv:Server=$serverName;Database=$dbName", $dbUsername, $dbPassword);    
   $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
}   
   
catch( PDOException $e ) {   
   die( "Error connecting to SQL Server: ".$e->getMessage() );    
} 