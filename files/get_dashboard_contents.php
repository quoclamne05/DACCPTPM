<?php
        session_start();
        include '../database/config.php';

        // Debug
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if(!isset($_SESSION['student_details'])) {
            echo "Bạn chưa đăng nhập";
            exit();
        }

        $data = $_SESSION['student_details'];
        $student_data = json_decode($data);

        if(empty($student_data)) {
            echo "Không có thông tin học sinh";
            exit();
        }

        foreach($student_data as $obj) {
            // Lấy thông tin bài kiểm tra và trạng thái
            $sql = "SELECT t.*, s.name as status_name, s.id as status_id 
                    FROM tests t 
                    INNER JOIN status s ON t.status_id = s.id 
                    WHERE t.id = '".$obj->test_id."'";
                    
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                echo "Lỗi truy vấn: " . mysqli_error($conn);
                exit();
            }
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['test_id'] = $row['id'];
                
                // Kiểm tra trạng thái bài kiểm tra
                switch($row['status_id']) {
                    case 2: // RUNNING
                        echo json_encode(array(
                            'name' => $row['name'],
                            'subject' => $row['subject'],
                            'total_questions' => $row['total_questions']
                        ));
                        break;
                    case 1: // PENDING
                        echo "Bài kiểm tra chưa bắt đầu";
                        break;
                    case 3: // COMPLETED
                        echo "Bài kiểm tra đã kết thúc";
                        break;
                    default:
                        echo "Trạng thái bài kiểm tra không hợp lệ";
                }
                exit();
            } else {
                echo "Không tìm thấy bài kiểm tra";
                exit();
            }
        }

        mysqli_close($conn);
?>