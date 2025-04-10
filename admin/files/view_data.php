<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="robots" content="noindex">
  <meta http-equiv="pragma" content="no-cache" />
  <meta http-equiv="expires" content="-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" />
  
  <title><?= ucfirst(basename($_SERVER['PHP_SELF'], ".php")); ?></title>

  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/now-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <link href="../assets/css/main.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">
    <?php include "sidebar.php"; ?>

    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute bg-primary fixed-top">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="#">View Data</a>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->

      <div class="panel-header panel-header-sm"></div>

      <div class="content" style="min-height: auto;">
        <div class="row">
          <div class="col-md-12">
            <div class="card" style="min-height:400px;">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-8">
                    <h5 class="title">All Class and Students Data</h5>
                  </div>
                  <div class="col-md-2">
                    <select id="options" name="options" class="btn-round form-control" required>
                      <option selected disabled value="">Select class</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button onclick="populateTable()" class="btn btn-primary btn-round btn-block" style="width:100px; float:right;">FIND</button>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <input type="hidden" name="general_settings" />
                <table id="roll_numbers_table">
                  <thead>
                    <tr>
                      <th data-field="id">ID</th>
                      <th data-field="rollno">Roll Number</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include "footer.php"; ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <script src="../assets/js/now-ui-dashboard.min.js?v=1.1.0" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>

  <script>
    $(document).ready(function () {
      $.ajax({
        type: 'POST',
        url: 'get_classes.php',
        success: function (response) {
          try {
            var opts = JSON.parse(response);
            $.each(opts, function (i, d) {
              $('#options').append('<option value="' + d + '">' + d + '</option>');
            });
          } catch (e) {
            console.error("Lỗi parse JSON từ get_classes.php:", e);
          }
        },
        error: function (xhr, status, error) {
          console.error("Lỗi AJAX load lớp học:", status, error);
        }
      });
    });

    function populateTable() {
      const selectedClass = $('#options').val();
      if (!selectedClass) {
        alert('Please select a class!');
        return;
      }

      $.ajax({
        type: 'POST',
        url: 'get_student_from_class.php',
        data: {
          class_name: selectedClass
        },
        dataType: 'json',
        success: function (data) {
          $('#roll_numbers_table').bootstrapTable('destroy'); // clear old data
          $('#roll_numbers_table').bootstrapTable({ data: data });
        },
        error: function (xhr, status, error) {
          console.error("Lỗi AJAX load học sinh:", status, error);
        }
      });
    }
  </script>
</body>
</html>
