<?php
session_start();
if(!isset($_SESSION["user_id"]))
  header("Location:../index.php");

include '../../database/config.php';

if(isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // Xóa học sinh khỏi bài kiểm tra
    $sql = "DELETE FROM students WHERE id = $student_id";
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
}
?> 