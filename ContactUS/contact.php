<?php
session_start();

// تضمين ملف الاتصال بقاعدة البيانات
include('Conexion.php');

// تحقق من الاتصال بقاعدة البيانات
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// التحقق من إرسال البيانات عبر POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $FullName = $_POST['FullName'];
    $Email = $_POST['Email'];
    $NumberPhone = $_POST['NumberPhone'];
    $Subject = $_POST['Subject'];
    $Message = $_POST['Message'];

    // التحقق من ملء جميع الحقول
    if (empty($FullName) || empty($Email) || empty($NumberPhone) || empty($Subject) || empty($Message)) {
        die("❌ All fields are required!");
    }

    // إصلاح استعلام الإدخال وجعل القيم في `bind_param`
    $stmt = $conn->prepare("INSERT INTO contact (FullName, Email, NumberPhone, Subject, Message) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("❌ Query preparation error: " . $conn->error);
    }

    // ربط القيم بالاستعلام
    $stmt->bind_param("sssss", $FullName, $Email, $NumberPhone, $Subject, $Message);

    // تنفيذ الاستعلام والتحقق من نجاحه
    if ($stmt->execute()) {
        echo '<div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin: 20px auto; width: 90%; border-radius: 5px; text-align: center;">
                ✅ Your message has been sent successfully! We will respond to you as soon as possible.
            </div>';
    } else {
        die("❌ Error while entering data: " . $stmt->error);
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
}



?>