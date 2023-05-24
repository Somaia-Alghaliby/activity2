<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'activity';


$mySqli = new mysqli($hostname, $username, $password, $dbname);

// التحقق من صحة الاتصال
if ($mySqli->connect_error) {
    echo 'Failed to connect ' . $mySqli->connect_error;
    exit();
}

if (isset($_POST['submit'])){

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['phone'])) {
        $name = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

// التحقق من عدم تكرار اسم المستخدم أو البريد الإلكتروني أو رقم الهاتف
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?";
        $check_stmt = $mySqli->prepare($check_query);
        $check_stmt->bind_param("sss", $name, $email, $phone);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
    // إظهار رسالة خطأ في حالة تكرار أحد الحقول
            echo '<div class="alert alert-danger" role="alert">Username or email or phone number already exists!</div>';
        } else {
    // يتم إضافة البيانات إلى قاعدة البيانات
            $query = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
            $stmt = $mySqli->prepare($query);
            $stmt->bind_param("ssss", $name, $password, $email, $phone);
            $stmt->execute();
    
            $stmt->close();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Display Info</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <!-- استخدام لوحة الإنتقال الجانبية لعرض البيانات -->
                <div class="list-group">
                    <?php
                    // استعلام لاسترداد البيانات من جدول "users"
                    $result = $mySqli->query('SELECT * FROM users;');
                    // التحقق من وجود بيانات في الجدول
                    if ($result->num_rows) {
                        // عرض البيانات في اللوحة الجانبية
                        while ($row = $result->fetch_assoc()) {
                            echo '<a href="#" class="list-group-item list-group-item-action"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1">Username: ' . $row['username'] . '</h5><small>ID: ' . $row['id'] . '</small></div><p class="mb-1">Password: ' . $row['password'] . '</p><p class="mb-1">Email: ' . $row['email'] . '</p><p class="mb-1">Phone Number: ' . $row['phone'] . '</p></a>';
                        }
                    } else {
                        // عرض رسالة إذا لم يتم العثور على بيانات
                        echo '<p>No data found</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style=" margin-top: 60px ; width:160px ; " data-bs-whatever="@new student">Add</button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="col-form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="col-form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="col-form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="col-form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="submit">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php

$mySqli->close();
?>