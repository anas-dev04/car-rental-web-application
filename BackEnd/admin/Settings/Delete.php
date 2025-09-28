<?php
include('Conexion.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM history_reservation WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: Settings.php"); // غيّر الاسم حسب اسم ملف الرسائل لديك
        exit;
    } else {
        echo "Error In Delete: " . mysqli_error($conn);
    }
}
?>
