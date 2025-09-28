<?php
include("config.php");

if (isset($_GET['id_cars'])) {
    $id = intval($_GET['id_cars']);
    $sql = "DELETE FROM cars WHERE id_cars = $id";
    if (mysqli_query($con, $sql)) {
        echo "Deleted successfully";
    } else {
        echo "Deletion error: " . mysqli_error($con);
    }
} else {
    echo "ID not found";
}
?>
