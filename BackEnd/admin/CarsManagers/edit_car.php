<?php
include("config.php");

// التحقق من id
if (!isset($_GET['id_cars'])) {
    echo "No car ID provided.";
    exit;
}

$id = intval($_GET['id_cars']);

// جلب بيانات السيارة
$query = "SELECT * FROM cars WHERE id_cars = $id";
$result = mysqli_query($con, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Car not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);

// التحديث بعد إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $TypeCar = $_POST['TypeCar'];
    $carname = $_POST['carname'];
    $Typegasoline = $_POST['Typegasoline'];
    $manualorautomatic = $_POST['manualorautomatic'];
    $PesonneVisable = $_POST['PesonneVisable'];
    $price = $_POST['price'];
    $imagePath = $row['image']; // الاحتفاظ بالصورة القديمة

    // إذا تم رفع صورة جديدة
    if (!empty($_FILES["image"]["name"])) {
        $imageName = $_FILES["image"]["name"];
        $imageTmp = $_FILES["image"]["tmp_name"];
        $uploadDir = "images/";
        $imagePath = $uploadDir . basename($imageName);
        move_uploaded_file($imageTmp, $imagePath);
    }

    // تنفيذ التحديث
    $updateQuery = "UPDATE cars SET 
        TypeCar = '$TypeCar',
        carname = '$carname',
        Typegasoline = '$Typegasoline',
        manualorautomatic = '$manualorautomatic',
        PesonneVisable = '$PesonneVisable',
        price = '$price',
        image = '$imagePath'
        WHERE id_cars = $id";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Updated successfully'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo " Error while updating: " . mysqli_error($con);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car Information</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri&family=Cairo:wght@200&family=Poppins:wght@100;200;300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div>
            <h2>Panel</h2>
            <ul>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/panel.html"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="index.php"> <i class="fa-solid fa-car"></i> Cars Management</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Reservations/reservations.php"><i class="fa-solid fa-bookmark"></i> Reservations</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Messages/Messages.php"><i class="fa-solid fa-message"></i> Messages</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Settings/Settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
            </ul>
        </div>

        <!-- زر تسجيل الخروج -->
        <div class="logout">
            <button class="logout" onclick="location.href='logout.php'">Disconnection</button>
        </div>
</div>
<!-- ====== النموذج ====== -->
<div class="edit_car">
    <h2>Edit Car Information</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Type Of The Car:</label><br>
    <select name="TypeCar" required>
        <?php
        $types = ['Economy', 'Luxury', 'Compact', 'Suv', 'Hybrid'];
        foreach ($types as $type) {
            $selected = ($row['TypeCar'] == $type) ? 'selected' : '';
            echo "<option value='$type' $selected>$type</option>";
        }
        ?>
    </select><br><br>

    <label>Car Name:</label><br>
    <input type="text" name="carname" value="<?= htmlspecialchars($row['carname']) ?>" required><br><br>

    <label>Type Of Gasoline:</label><br>
    <select name="Typegasoline" required>
        <option value="gasoline" <?= $row['Typegasoline'] == 'gasoline' ? 'selected' : '' ?>>Gasoline</option>
        <option value="diesel" <?= $row['Typegasoline'] == 'diesel' ? 'selected' : '' ?>>Diesel</option>
    </select><br><br>

    <label>Manual or Automatic:</label><br>
    <select name="manualorautomatic" required>
        <option value="manual" <?= $row['manualorautomatic'] == 'manual' ? 'selected' : '' ?>>Manual</option>
        <option value="automatic" <?= $row['manualorautomatic'] == 'automatic' ? 'selected' : '' ?>>Automatic</option>
    </select><br><br>

    <label>Visible Persons:</label><br>
    <select name="PesonneVisable" required>
        <option value="5 Personnes" <?= $row['PesonneVisable'] == '5 Personnes' ? 'selected' : '' ?>>5 Personnes</option>
        <option value="4 personnes" <?= $row['PesonneVisable'] == '4 personnes' ? 'selected' : '' ?>>4 Personnes</option>
    </select><br><br>

    <label>Price Of The Car:</label><br>
    <input type="text" name="price" value="<?= htmlspecialchars($row['price']) ?>" required><br><br>

    <label>Current Image:</label><br>
    <img src="<?= htmlspecialchars($row['image']) ?>" width="150"><br><br>

    <label>Select New Image (optional):</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Update</button>
    </form>
</div>

</body>
</html>