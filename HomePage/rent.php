<?php
session_start();
include 'Conexion.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// If rental data is submitted (this should come from a previous booking form)
if (isset($_POST['Rent'])) {
    // Sanitize input data
    $pickup_location = mysqli_real_escape_string($conn, $_POST['pickup_location']);
    $return_location = mysqli_real_escape_string($conn, $_POST['return_location']);
    $pickup_date = mysqli_real_escape_string($conn, $_POST['pickup_date']);
    $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time']);
    $return_date = mysqli_real_escape_string($conn, $_POST['return_date']);
    $return_time = mysqli_real_escape_string($conn, $_POST['return_time']);

    // Use prepared statement for security
    $query = "INSERT INTO rent (pickup_location, return_location, pickup_date, pickup_time, return_date, return_time)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssss", $pickup_location, $return_location, $pickup_date, $pickup_time, $return_date, $return_time);

        if (mysqli_stmt_execute($stmt)) {
            $id_rent = mysqli_insert_id($conn);
            $_SESSION['id_rent'] = $id_rent;
            echo "<script>alert('✅ Booking details saved! Please select a car.');</script>";
        } else {
            echo "<script>alert('❌ Error in booking: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('❌ Database prepare error: " . mysqli_error($conn) . "');</script>";
    }
}

// Check for the correct session variable - uncommented and fixed
if (!isset($_SESSION['id_rent'])) {
    echo "<script>alert('⚠️ Please complete your booking details first!'); window.location.href='rent.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="rent.css">
    <title>Cars Selection</title>
</head>
<body>
<header>
        <a href="home.html" class="logo"><img src="logo.webp" alt=""></a>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="http://localhost/PFF/PartiePratique/OurCars/OurCars.html">Our Cars</a></li>
            <li><a href="http://localhost/PFF/PartiePratique/AboutUs/AboutUs.html">About Us</a></li>
            <li><a href="http://localhost/PFF/PartiePratique/ContactUs/Contact.html">Contact Us</a></li>
        </ul>
</header>

<div class="profil">
    <h1>Selection Your Favorite Cars</h1>
    
    <!-- Show booking status -->
    
</div>

<div class="cars">
<?php
$result = mysqli_query($conn, "SELECT * FROM cars");

if (!$result) {
    echo "<p>Error fetching cars: " . mysqli_error($conn) . "</p>";
} elseif (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)){
?>
    <div class="box-container">
        <h3 class="TypeCar"><?php echo htmlspecialchars($row['TypeCar']); ?></h3>
        <h2 class="carname"><?php echo htmlspecialchars($row['carname']); ?></h2>
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['carname']); ?>"/>
        <div class="attributs">
            <p><i class="fa-solid fa-gas-pump"></i><?php echo htmlspecialchars($row['Typegasoline']); ?></p>
            <p><i class="fa-solid fa-car"></i><?php echo htmlspecialchars($row['manualorautomatic']); ?></p>
            <p><i class="fa-solid fa-person"></i><?php echo htmlspecialchars($row['PesonneVisable']); ?></p>
        </div>
        <h4 class="prix">Prix : <?php echo htmlspecialchars($row['price'])."$"; ?></h4>

        <form action="rentForm.php" method="post">
            <input type="hidden" name="TypeCar" value="<?php echo htmlspecialchars($row['TypeCar']); ?>">
            <input type="hidden" name="CarName" value="<?php echo htmlspecialchars($row['carname']); ?>">
            <input type="hidden" name="Price" value="<?php echo htmlspecialchars($row['price']); ?>">
            <button type="submit" class="Selection" name="RentNow">Selection</button>
        </form>
    </div>
<?php
    }
} else {
    echo "<div style='text-align: center; padding: 20px;'>";
    echo "<p>No cars available at the moment.</p>";
    echo "<p>Please check back later or contact us for more information.</p>";
    echo "</div>";
}
?>
</div>

<footer>
    <div class="informations">
        <ul>
            <li><i class="fa-solid fa-house"></i><h2>Our Locations</h2>
            <div class="moreinformations">
            <span>Agadir Agency</span><br>
            <span>Casablanca Agency</span><br>
            <span>Rabat Agency</span><br>
            <span>Tangier Agency</span><br>
            <span>Marackech Agency</span>
            </div>
            </li>
            <div class="lines"></div>
            <li><i class="fa-solid fa-phone"></i><h2>Contact Phone</h2>
            <div class="moreinformations">
                <span>Info & Reservation:</span><br>
                <span><h4 style="display: inline-block;">+212 629988434</h4></span>
            </div>
            </li>
            <div class="lines"></div>
            <li><i class="fa-solid fa-envelope"></i><h2> Email Contact</h2>
            <div class="moreinformations">
                <span>Booking: <h4 style="display: inline-block;">booking@AnasCars.ma</h4></span><br>
                <span>Contact: <h4 style="display: inline-block;">contact@carlaville.ma</h4></span>
            </div>
            </li>
            <div class="lines"></div>
            <li><i class="fa-solid fa-clock"></i><h2 style="display: inline-block;"> Working Hours</h2>
            <div class="moreinformations">
                <span>Working Hours: <h4 style="display: inline-block;">24h/7j</h4></span><br>
                <span>Reservation: <h4 style="display: inline-block;">All Year</h4></span>
            </div>
            </li>
        </ul>
    </div>
    <div class="line"></div>
    <div class="infor2">
        <div class="informations2">
            <a href="home.html" class="logo">AnasCars</a><br>
            <p>Car La Ville offers premium car rental in Morocco with high-end vehicles for unforgettable trips.</p>
        </div>
        <div class="information3">
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="OurCars/OurCars.html">Our Cars</a></li>
                <li><a href="AboutUs/AboutUs.html">About Us</a></li>
                <li><a href="ContactUs/Contact.html">Contact Us</a></li>
            </ul>
        </div>
    </div>
    <div class="line"></div>
    <div class="informations4">
        <p>Follow us:</p>
        <i class="fa-brands fa-facebook"></i>
        <i class="fa-brands fa-instagram"></i>
        <i class="fa-brands fa-tiktok"></i>
        
        <div class="debitcard">
            <img src="visa.png" alt="Visa">
            <img src="card.png" alt="Credit Card">
            <img src="maestro.png" alt="Maestro">
        </div>
    </div>
    <div class="informations5">
        <p>© Copyright 2025 carlaville.ma</p>
        <div class="seceruty">
            <a href="">Privacy Policy</a>
            <a href="">Terms and Conditions</a>
        </div>
    </div>
</footer>

<!-- Debug information (remove in production) -->
<script>
console.log("Session debug info:");
console.log("id_rent exists: <?php echo isset($_SESSION['id_rent']) ? 'Yes' : 'No'; ?>");
<?php if (isset($_SESSION['id_rent'])): ?>
console.log("id_rent value: <?php echo $_SESSION['id_rent']; ?>");
<?php endif; ?>
</script>

</body>
</html>