<?php
include('config.php');

if(isset($_POST['upload'])) {
    $TypeCar  = $_POST['TypeCar'];
    $carname = $_POST['carname'];
    $Typegasoline = $_POST['Typegasoline'];
    $manualorautomatic = $_POST['manualorautomatic'];
    $PesonneVisable = $_POST['PesonneVisable'];
    $price = $_POST['price'];

    // مجلد فرعي حسب نوع السيارة (مثال بسيط)
    $folder = strtolower($TypeCar); // مثل: "SUV" => "suv"
    $folderPath = "images/$folder/";

    // إنشاء المجلد إذا لم يكن موجودًا
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imagePath = $folderPath . basename($imageName);

    // نقل الصورة للمجلد
    move_uploaded_file($imageTmpName, $imagePath);

    // تخزين المسار الكامل في قاعدة البيانات
    $insert = "INSERT INTO cars (TypeCar, carname, Typegasoline, manualorautomatic, PesonneVisable, price, image) 
            VALUES ('$TypeCar','$carname','$Typegasoline','$manualorautomatic','$PesonneVisable','$price','$imagePath')";

    if (mysqli_query($con, $insert)) {
        echo "<div style='
            position:fixed;
            top:0; left:0; right:0; bottom:0;
            background:rgba(0,0,0,0.6);
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial;
        '>
            <div style='background:#fff;padding:40px;border-radius:10px;text-align:center;'>
                The car has been added successfully.✅ <br><br>
                <a href='index.php' style='display:inline-block;padding:10px 20px;background:green;color:#fff;text-decoration:none;border-radius:5px;'>Back</a>
            </div>
        </div>";
    } else {
        echo "Input error:" . mysqli_error($con);
    }
}

?>