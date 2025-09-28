<?php
session_start();
include('Conexion.php');

// SQL query
$sql = "SELECT AdminName FROM admin WHERE id_admin = 1"; 

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if query was successful
if (!$result) {
    die("Error in query: " . mysqli_error($conn)); // Display error if query fails
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="Settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        h1 {
            color: #333;
            text-align: center;
        }
        .form_password .form-password label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form_password .form-password input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .form_password .form-password button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 80%;
            font-size: 16px;
        }
        .form_password .form-password button:hover {
            background-color: #45a049;
        }
        .form_password .form-password p {
            font-size: 14px;
            color: red;
        }
        .form_password .form-password a {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .form_password .form-password a:hover {
            text-decoration: underline;
        }
        .add_admin_form .add-admin-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .add_admin_form .add-admin-form input {
            width: 80%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .add_admin_form .add-admin-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 80%;
            font-size: 16px;
        }
        .add_admin_form .add-admin-form button:hover {
            background-color: #45a049;
        }
        .add_admin_form .add-admin-form p {
            font-size: 14px;
            color: red;
        }
        .add_admin_form .add-admin-form a {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .add_admin_form .add-admin-form a:hover {
            text-decoration: underline;
        }
        
        .main .history_reservation {
            margin-left: 300px;
            margin-top: 25px;
            display: grid;
            grid-template-columns: 400px 400px 400px;
            /* gap: 20px; */
            justify-content: center;
        }

        .main .history_reservation .card {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            width: 300px;
            margin-top: 20px;
        }
        

        .main .history_reservation .card h3 {
            margin-top: 0;
            color: #333;
        }

        .main .history_reservation .card p {
            margin: 5px 0;
            color: #555;
        }
        .main .history_reservation .Delete{
            font-size: 20px;
            color: white;
            border-radius: 10px;
            padding: 10px;
            margin-left: 85px;
            background-color: red;
        }
        .main .history_reservation .Delete a{
            background-color: red;
            color: white;
            text-decoration: none;
        }
        .form_password {
            margin-left: 200px;
            text-align: center;
            display: none;
        }
        .add_admin_form {
            margin-left: 200px;
            text-align: center;
            display: none;
        }
        .main .history_reservation {
            display: none;
        }
        .main .main2{
            text-align: center;
            margin-left: 150px;
        }

</style>

</head>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>Panel</h2>
            <ul>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/panel.html"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/CarsManagers/index.php"> <i class="fa-solid fa-car"></i> Cars Management</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Reservations/reservations.php"><i class="fa-solid fa-bookmark"></i> Reservations</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Messages/Messages.php"><i class="fa-solid fa-message"></i> Messages</a></li>
                <li><a href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
            </ul>
        </div>

        <!-- زر تسجيل الخروج -->
        <div class="logout">
            <button class="logout" onclick="location.href='logout.php'">Déconnexion</button>
        </div>
    </div>

    <div class="main">
            <div class="main2">
                <h1>Hello Mr 
                <?php 
                if ($row = mysqli_fetch_assoc($result)) { 
                    echo $row['AdminName']; // Display admin name
                } else {
                    echo "Admin not found"; // Display message if no result found
                }
                ?>
            </h1>
            <br>
            <h4>This Page For Modify Password Or Add a New Admins Or Modify About Admins Informations</h4>
            <br>
            <div class="main-content">
                <a href="#" class="show-password-form">Update Password</a>
                <a href="#" class="show-add-admin">Add a new admins</a>
                <a href="#" class="show-reservation-history">Reservations History</a>
            </div>
            </div>

            <br><br>

            <div class="form_password">
                <form method="post" class="form-password">
                    <label>Current password :</label><br>
                    <input type="password" name="current_password" required><br><br>

                    <label>New Password :</label><br>
                    <input type="password" name="new_password" required><br><br>

                    <label>Confirm new password :</label><br>
                    <input type="password" name="confirm_password" required><br><br>

                    <button type="submit" name="change_password">To update</button>
                </form>
            </div>

            <div class="add_admin_form">
                <form method="post" class="add-admin-form">
                    <label>Admin Name :</label><br>
                    <input type="text" name="admin_name" required><br><br>

                    <label>Admin Email :</label><br>
                    <input type="email" name="admin_email" required><br><br>

                    <label>Admin Password :</label><br>
                    <input type="password" name="admin_password" required><br><br>

                    <button type="submit" name="ADD">ADD</button>
                </form>
            </div>

            <div class="history_reservation">
                <?php
                    $sql="SELECT * FROM history_reservation";
                    $result=mysqli_query($conn,$sql);
                    if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='card' data-pickup='".htmlspecialchars($row['pickup_location'])."'>";
                        echo "<h3>" . $row['full_name'] . "</h3>";
                        echo "<p><strong>number_phone:</strong> " . $row['number_phone'] . "</p>";
                        echo "<p><strong>email:</strong> " . $row['email'] . "</p>";
                        echo "<p><strong>adresse:</strong> " . $row['adresse'] . "</p>";
                        echo "<hr>";
                        echo "<p><strong>CarName:</strong> " . $row['CarName'] . " (" . $row['TypeCar'] . ")</p>";
                        echo "<p><strong>Price:</strong> " . $row['Price'] . "</p>";
                        echo "<hr>";
                        echo "<p><strong>From:</strong> " . $row['pickup_location'] . " - " . $row['pickup_date'] . " " . $row['pickup_time'] . "</p>";
                        echo "<p><strong>To:</strong> " . $row['return_location'] . " - " . $row['return_date'] . " " . $row['return_time'] . "</p>";
                        echo "<form method='post' style='display:inline;'>";
                        echo "<button class='Delete'><a href='Delete.php?id=".$row['id']."' onclick=\"return confirm('Are You Sure ?');\">Delete</a></button>";
                        echo "</form>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>There are no reservations history currently..</p>";
                };
                ?>
            </div>
        </div>
    </div>

    <script src="Settings.js"></script>

</body>
</html>

<?php
// session_start();
include('Conexion.php');

$success = "";
$error = "";

// تأكد من تسجيل الدخول
if (!isset($_SESSION["id_admin"])) {
    die("Access denied. Please log in.");
}

$id_admin = $_SESSION["id_admin"];
$sql = "SELECT AdminName FROM admin WHERE id_admin = $id_admin";
$result = mysqli_query($conn, $sql);

// تغيير كلمة المرور
if (isset($_POST["change_password"])) {
    $current_password = $conn->real_escape_string($_POST["current_password"]);
    $new_password = $conn->real_escape_string($_POST["new_password"]);
    $confirm_password = $conn->real_escape_string($_POST["confirm_password"]);

    $sql = "SELECT password FROM admin WHERE id_admin = $id_admin";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($current_password == $row["password"]) { // يمكنك استخدام password_verify هنا مستقبلاً
            if ($new_password === $confirm_password) {
                $sql_update = "UPDATE admin SET password = '$new_password' WHERE id_admin = $id_admin";
                if ($conn->query($sql_update) === TRUE) {
                    $success = "Your password has been successfully updated.";
                    $_SESSION["password"] = $new_password;
                } else {
                    $error = "Error updating password : " . $conn->error;
                }
            } else {
                $error = "New password and confirmation do not match.";
            }
        } else {
            $error = "The current password is incorrect.";
        }
    } else {
        $error = "User not found.";
    }
}

// إضافة أدمن جديد
if (isset($_POST['ADD'])) {
    $admin_name = $conn->real_escape_string($_POST['admin_name']);
    $admin_email = $conn->real_escape_string($_POST['admin_email']);
    $admin_password = $conn->real_escape_string($_POST['admin_password']);

    $check_sql = "SELECT * FROM admin WHERE AdminName = '$admin_name'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Administrator name already in use.";
    } else {
        $sql = "INSERT INTO admin (AdminName, email, password) 
                VALUES ('$admin_name', '$admin_email', '$admin_password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully.";
        } else {
            $error = "Error while inserting : " . mysqli_error($conn);
        }
    }
}

// عرض رسائل النجاح أو الخطأ إن وُجدت
if (!empty($error)) {
    echo "<p style='color:red'>$error</p>";
}
if (!empty($success)) {
    echo "<p style='color:green'>$success</p>";
}
?>



