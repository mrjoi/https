<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
    
</body>
</html>







<?php 
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Include database configuration file 
require_once 'dbConfig.php'; 
 
// Set default redirect url 
$redirectURL = 'index.php'; 
 
if(isset($_POST['userSubmit'])){ 
    // Get form fields value 
    $MemberID = $_POST['MemberID']; 
    $FirstName = trim(strip_tags($_POST['FirstName'])); 
    $NRC_No = trim(strip_tags($_POST['NRC_No'])); 
    $Email = trim(strip_tags($_POST['Email'])); 
    $Country = trim(strip_tags($_POST['Country'])); 
     
    $id_str = ''; 
    if(!empty($id)){ 
        $id_str = '?id='.$MemberID; 
    } 
     
    // Fields validation 
    $errorMsg = ''; 
    if(empty($FirstName)){ 
        $errorMsg .= '<p>Please enter your first name.</p>'; 
    } 
    if(empty($NRC_No)){ 
        $errorMsg .= '<p>Please enter your Nrc_no.</p>'; 
    } 
    if(empty($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)){ 
        $errorMsg .= '<p>Please enter a valid email.</p>'; 
    } 
    if(empty($Country)){ 
        $errorMsg .= '<p>Please enter country name.</p>'; 
    } 
     
    // Submitted form data 
    $userData = array( 
        'FirstName' => $FirstName, 
        'NRC_No' => $NRC_No, 
        'Email' => $Email, 
        'Country' => $Country 
    ); 
     
    // Store the submitted field values in the session 
    $sessData['userData'] = $userData; 
     
    // Process the form data 
    if(empty($errorMsg)){ 
        if(!empty($MemberID)){ 
            // Update data in SQL server 
            $sql = "UPDATE Members SET FirstName = ?, NRC_No = ?, Email = ?, Country = ? WHERE MemberID = ?";   
            $query = $conn->prepare($sql);   
            $update = $query->execute(array($FirstName, $LastName, $Email, $Country, $MemberID)); 
             
            if($update){ 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Member data has been updated successfully.'; 
                 
                // Remove submitted field values from session 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                // Set redirect url 
                $redirectURL = 'addEdit.php'.$id_str; 
            } 
        }else{ 
            // Insert data in SQL server 
            $sql = "INSERT INTO Members (FirstName, NRC_No, Email, Country, Created) VALUES (?,?,?,?,?)";   
            $params = array( 
                &$FirstName, 
                &$NRC_No, 
                &$Email, 
                &$Country, 
                date("Y-m-d H:i:s") 
            );   
            $query = $conn->prepare($sql); 
            $insert = $query->execute($params);   
             
            if($insert){ 
                //$MemberID = $conn->lastInsertId(); 
                 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Member data has been added successfully.'; 
               
                 
                // Remove submitted field values from session 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                // Set redirect url 
                $redirectURL = 'addEdit.php'.$id_str; 
            } 
        } 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = '<p>Please fill all the mandatory fields.</p>'.$errorMsg; 
         
        // Set redirect url 
        $redirectURL = 'addEdit.php'.$id_str; 
    } 
     
    // Store status into the session 
    $_SESSION['sessData'] = $sessData; 
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){ 
    $MemberID = $_GET['id']; 
     
    // Delete data from SQL server 
    $sql = "DELETE FROM Members WHERE MemberID = ?"; 
    $query = $conn->prepare($sql); 
    $delete = $query->execute(array($MemberID)); 
     
    if($delete){ 
        $sessData['status']['type'] = 'success'; 
        $sessData['status']['msg'] = 'Member data has been deleted successfully.'; 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
    } 
     
    // Store status into the session 
    $_SESSION['sessData'] = $sessData; 
} 
 
// Redirect to the respective page 
header("Location:".$redirectURL); 
exit(); 
?>