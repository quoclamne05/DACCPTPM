<?php
session_start();
if(!isset($_SESSION["user_id"]))
  header("Location:../index.php");

include '../../database/config.php';

if(isset($_POST['test_id']) && isset($_POST['status'])) {
    $test_id = $_POST['test_id'];
    $status = $_POST['status'];
    
    // Cập nhật trạng thái bài kiểm tra
    $sql = "UPDATE tests SET status_id = (SELECT id FROM status WHERE name = '$status') WHERE id = $test_id";
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
}
?> 