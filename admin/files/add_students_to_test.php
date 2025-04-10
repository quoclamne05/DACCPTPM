<?php
session_start();
if(!isset($_SESSION["user_id"]))
  header("Location:../index.php");

include '../../database/config.php';

if(isset($_POST['test_id']) && isset($_POST['student_ids'])) {
    $test_id = $_POST['test_id'];
    $student_ids = json_decode($_POST['student_ids']);
    $success = true;
    
    // Thêm từng học sinh vào bài kiểm tra
    foreach($student_ids as $student_id) {
        // Kiểm tra xem học sinh đã được thêm vào bài kiểm tra chưa
        $check_sql = "SELECT * FROM students WHERE test_id = $test_id AND rollno = '$student_id'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_result) == 0) {
            // Tạo mật khẩu ngẫu nhiên cho học sinh
            $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
            
            // Thêm học sinh vào bảng students
            $sql = "INSERT INTO students (rollno, test_id, password, score) VALUES ('$student_id', $test_id, '$password', 0)";
            if(!mysqli_query($conn, $sql)) {
                $success = false;
                break;
            }
        }
    }
    
    if($success) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
}

// Lấy danh sách học sinh chưa được thêm vào bài kiểm tra
if(isset($_GET['test_id']) && isset($_GET['class_id'])) {
    $test_id = $_GET['test_id'];
    $class_id = $_GET['class_id'];
    
    $sql = "SELECT sd.rollno, sd.id 
            FROM student_data sd 
            LEFT JOIN students s ON sd.rollno = s.rollno AND s.test_id = $test_id
            WHERE sd.class_id = $class_id AND s.id IS NULL";
            
    $result = mysqli_query($conn, $sql);
    $students = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    
    echo json_encode($students);
}
?> 