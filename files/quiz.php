<?php
    include '../database/config.php';
    session_start();

    if(!isset($_SESSION['test_id']))
        header("Location: ../index.php");
    else
        $_SESSION['test_ongoing'] = "true";
?>
<html>
    <head>
        <link rel="icon" type="image/png" href="../admin/assets/img/favicon.png">
        <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/util.css">
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/quiz.css">
        <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="../vendor/tilt/tilt.jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
        <style>
			.loader {
			  border: 16px solid #f3f3f3;
			  border-radius: 50%;
			  border-top: 16px solid #3498db;
			  width: 120px;
			  height: 120px;
			  -webkit-animation: spin 2s linear infinite; /* Safari */
			  animation: spin 2s linear infinite;
			}

			/* Safari */
			@-webkit-keyframes spin {
			  0% { -webkit-transform: rotate(0deg); }
			  100% { -webkit-transform: rotate(360deg); }
			}

			@keyframes spin {
			  0% { transform: rotate(0deg); }
			  100% { transform: rotate(360deg); }
			}
		</style>
    </head>
    <body>
    <header class="header1">
            <!-- Header desktop -->
            <div class="container-menu-header">
                <div class="wrap_header">
                    <!-- Logo -->
                    <a href="../index.php" class="logo">
                        <img src="../images/icons/logo.png" alt="IMG-LOGO">
                    </a>

                    <!-- Header Icon -->
                    <div class="header-icons">
                        <a href="#" class="header-wrapicon1 dis-block">
                            <img src="../images/icons/logout.png" class="header-icon1" alt="ICON" onclick = 'logout()'>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Header Mobile -->
            <div class="wrap_header_mobile">
                <!-- Logo moblie -->
                <a href="../index.php" class="logo-mobile">
                    <img src="../images/icons/logo.png" alt="IMG-LOGO">
                </a>

                <!-- Button show menu -->
                <div class="btn-show-menu">
                    <!-- Header Icon mobile -->
                    <div class="header-icons-mobile">
                        <a href="#" class="header-wrapicon1 dis-block">
                            <img src="../images/icons/logout.png" class="header-icon1" alt="ICON" onclick = 'logout()'>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section>
            <div class="limiter">
                <div class="container-login100" style="display:block;">
                    <div class="container">
                        <div class="row">
                            <div class="col" style="padding:0px;">
                                    <div class="card" style="padding-bottom: 20px;">
                                        <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12" style="padding:0px;">
                                                        <div class="container-fluid">
                                                            <div class="modal-dialog" style="max-width: 100%">
                                                            <div class="modal-content">
                                                            	<div id="loader" class="loader" style="margin-left:45%;display:block;"></div>
                                                            	<div id="content" style="display:none;">
	                                                                <div class="modal-header">
	                                                                    <h5><span class="label label-warning" id="qid">1</span> <span id="question"></span></h5>
	                                                                </div>
	                                                                <div class="modal-body">
		                                                                <div class="quiz" id="quiz" data-toggle="buttons">
			                                                                <label id="optionA" onclick="getSelectedItem('a')" class="element-animation1 btn btn-lg btn-primary btn-block"><span class="btn-label" ><i class="glyphicon glyphicon-chevron-right"></i></span> <span id="optionA-text"></span></label>
			                                                                <label id="optionB" onclick="getSelectedItem('b')" class="element-animation2 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span> <span id="optionB-text"></span></label>
			                                                                <label id="optionC" onclick="getSelectedItem('c')" class="element-animation3 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span> <span id="optionC-text"></span></label>
			                                                                <label id="optionD" onclick="getSelectedItem('d')" class="element-animation4 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span> <span id="optionD-text"></span></label>
			                                                            </div>
		                                                            </div>
	                                                        	</div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            var question_data;
            var question_count = 1;
            
            $(document).ready(function(){
                var temp = document.getElementById('content');
                var temp1 = document.getElementById('loader');
                temp.style.display = 'none';
                temp1.style.display = 'block';

                // Kiểm tra trạng thái bài kiểm tra và tải câu hỏi
                if(Cookies.get('last_question_was_answered') == undefined || 
                   Cookies.get('last_question_was_answered') == "true") {
                    createQuestion();
                    Cookies.set('last_question_was_answered', 'false');
                } else if (Cookies.get('last_question_was_answered') == "false") {
                    // Load lại câu hỏi trước đó
                    try {
                        var lastQuestion = Cookies.get('last_question');
                        if(lastQuestion) {
                            loadQuestion(JSON.parse(lastQuestion));
                            question_data = JSON.parse(lastQuestion);
                        } else {
                            createQuestion();
                        }
                    } catch(e) {
                        console.error("Lỗi khi load câu hỏi trước:", e);
                        createQuestion();
                    }
                }
            });
        
            $('.js-tilt').tilt({
                scale: 1.1
            });

            function getSelectedItem(val){
                var temp = document.getElementById('content');
                var temp1 = document.getElementById('loader');
                temp.style.display = 'none';
                temp1.style.display = 'block';

                Cookies.set('last_question_was_answered', 'true');

                $.ajax({
                    type: 'POST',
                    url: "check_answer.php",
                    data: {
                        'question_id': question_data.id.toString(),
                        'selected_option': val.toString(),
                        'score': question_data.score.toString()
                    },
                    success: function(result) {
                        console.log("Kết quả kiểm tra câu trả lời:", result);
                        createQuestion();
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi kiểm tra câu trả lời');
                        temp.style.display = 'block';
                        temp1.style.display = 'none';
                    }
                });
            }

            function createQuestion(){
                $.ajax({
                    url: "get_question.php",
                    success: function(result){
                        console.log("Kết quả từ get_question.php:", result);
                        if(result === "QUESTION_SET_FINISHED"){
                            $.ajax({
                                type: 'POST',
                                url: 'end_session.php',
                                data: { 
                                    'message': '0'
                                },
                                success: function(msg){
                                    alert(msg);
                                    Cookies.remove('last_question_was_answered');
                                    Cookies.remove('last_question');
                                    Cookies.set('test_submitted_status', msg.toString());
                                    window.location.replace("test_finished.php");
                                }
                            });
                        } else {
                            try {
                                question_data = JSON.parse(result);
                                console.log("Dữ liệu câu hỏi:", question_data);
                                if(question_data && question_data.title) {
                                    Cookies.set('last_question', result);
                                    Cookies.set('last_question_was_answered', "false");
                                    loadQuestion(question_data);
                                } else {
                                    throw new Error("Dữ liệu câu hỏi không hợp lệ");
                                }
                            } catch(e) {
                                console.error("Lỗi khi parse JSON:", e);
                                console.log("Dữ liệu gốc:", result);
                                alert('Có lỗi xảy ra khi tải câu hỏi');
                                var temp = document.getElementById('content');
                                var temp1 = document.getElementById('loader');
                                temp.style.display = 'block';
                                temp1.style.display = 'none';
                            }
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi tải câu hỏi');
                        var temp = document.getElementById('content');
                        var temp1 = document.getElementById('loader');
                        temp.style.display = 'block';
                        temp1.style.display = 'none';
                    }
                });
            }

            function loadQuestion(question_data){
                var temp = document.getElementById('content');
                var temp1 = document.getElementById('loader');
                
                if(question_data && question_data.title) {
                    console.log("Hiển thị câu hỏi:", question_data);
                    $('#qid').text(question_count);
                    $('#question').text(question_data.title);
                    $('#optionA-text').text('A) ' + question_data.optionA);
                    $('#optionB-text').text('B) ' + question_data.optionB);
                    $('#optionC-text').text('C) ' + question_data.optionC);
                    $('#optionD-text').text('D) ' + question_data.optionD);
                    question_count++;
                    
                    temp.style.display = 'block';
                    temp1.style.display = 'none';
                } else {
                    console.error("Dữ liệu câu hỏi không hợp lệ:", question_data);
                    alert('Có lỗi xảy ra khi hiển thị câu hỏi');
                }
            }

            function logout(){
                $.ajax({
                    type: 'POST',
                    url: 'end_session.php',
                    data: { 
                        'message': '1'
                    },
                    success: function(msg){
                        alert(msg);
                        Cookies.remove('last_question_was_answered');
                        Cookies.remove('last_question');
                        Cookies.set('test_submitted_status', msg.toString());
                        window.location.replace("test_finished.php");
                    }
                });
            }
        </script>
    </body>
</html>