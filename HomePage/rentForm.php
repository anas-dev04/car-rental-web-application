<?php
session_start();
include("Conexion.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Store car details in session if they come from the car selection page
if (isset($_POST['RentNow'])) {
    $_SESSION['TypeCar'] = $_POST['TypeCar'] ?? '';
    $_SESSION['CarName'] = $_POST['CarName'] ?? '';
    $_SESSION['Price'] = $_POST['Price'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Rent'])) {
    // Sanitize input data
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $number_phone = mysqli_real_escape_string($conn, trim($_POST['number_phone']));
    $adresse = mysqli_real_escape_string($conn, trim($_POST['adresse']));

    // Get car details from POST or SESSION
    $TypeCar = mysqli_real_escape_string($conn, $_POST['TypeCar'] ?? $_SESSION['TypeCar'] ?? '');
    $CarName = mysqli_real_escape_string($conn, $_POST['CarName'] ?? $_SESSION['CarName'] ?? '');
    $Price = mysqli_real_escape_string($conn, $_POST['Price'] ?? $_SESSION['Price'] ?? '');

    // Debug: Check if data is being received
    echo "<script>console.log('Form data received: Name=" . $full_name . ", Email=" . $email . "');</script>";

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($number_phone) || empty($adresse)) {
        echo "<script>alert('❌ Please fill in all required fields.');</script>";
    } elseif (empty($TypeCar) || empty($CarName) || empty($Price)) {
        echo "<script>alert('❌ Car information is missing. Please select a car first.'); window.location.href='rent.php';</script>";
        exit();
    } elseif (!isset($_SESSION['id_rent'])) {
        echo "<script>alert('❌ Booking information is missing. Please start over.'); window.location.href='booking_form.php';</script>";
        exit();
    } else {
        $id_rent = $_SESSION['id_rent'];

        // Begin transaction for data integrity
        mysqli_autocommit($conn, FALSE);
        
        try {
            // Insert car data to rent_cars with the existing id_rent
            $query2 = "INSERT INTO rent_cars (id_rent, TypeCar, CarName, Price) 
                        VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $query2);
            
            if (!$stmt2) {
                throw new Exception("Prepare failed for rent_cars: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt2, "issd", $id_rent, $TypeCar, $CarName, $Price);
            
            if (!mysqli_stmt_execute($stmt2)) {
                throw new Exception("Error inserting car data: " . mysqli_stmt_error($stmt2));
            }
            
            // Get the id_select_car for the clients table
            $id_select_car = mysqli_insert_id($conn);

            // Insert user data to clients table using id_select_car
            $query3 = "INSERT INTO clients (id_select_car, full_name, email, number_phone, adresse) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmt3 = mysqli_prepare($conn, $query3);
            
            if (!$stmt3) {
                throw new Exception("Prepare failed for clients: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt3, "issss", $id_select_car, $full_name, $email, $number_phone, $adresse);
            
            if (!mysqli_stmt_execute($stmt3)) {
                throw new Exception("Error inserting client data: " . mysqli_stmt_error($stmt3));
            }

            // Commit transaction
            mysqli_commit($conn);
            
            // Clear session after successful reservation
            unset($_SESSION['id_rent']);
            unset($_SESSION['TypeCar']);
            unset($_SESSION['CarName']);
            unset($_SESSION['Price']);
            
            echo "<script>alert('✅ Reservation confirmed successfully!'); window.location.href='home.html';</script>";
            
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            echo "<script>alert('❌ " . addslashes($e->getMessage()) . "');</script>";
        }
        
        // Close prepared statements
        if (isset($stmt2)) mysqli_stmt_close($stmt2);
        if (isset($stmt3)) mysqli_stmt_close($stmt3);
        
        // Restore autocommit
        mysqli_autocommit($conn, TRUE);
    }
}

// Get car details from session for displaying on the form
$TypeCar = $_SESSION['TypeCar'] ?? $_POST['TypeCar'] ?? '';
$CarName = $_SESSION['CarName'] ?? $_POST['CarName'] ?? '';
$Price = $_SESSION['Price'] ?? $_POST['Price'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Form</title>
    <link rel="stylesheet" href="rentform.css">
</head>
<body>
    <div class="form-container2">
        <div class="form-container">
            <div class="personal-information">
                <h1>Your Info</h1>
                <?php if (!empty($CarName)): ?>
                <div class="selected-car">
                    <h3>Selected Car: <?php echo htmlspecialchars($CarName); ?> (<?php echo htmlspecialchars($TypeCar); ?>)</h3>
                    <p>Price: $<?php echo htmlspecialchars($Price); ?></p>
                </div>
                <?php else: ?>
                <div class="warning" style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; margin: 10px 0; border-radius: 5px;">
                    <strong>⚠️ No car selected.</strong> Please go back and select a car first.
                    <br><a href="rent.php" style="color: #856404; text-decoration: underline;">← Go Back to Car Selection</a>
                </div>
                <?php endif; ?>
            </div>
    
            <form id="payment-form" action="rentForm.php" method="post">
                <!-- Hidden fields to keep car information -->
                <input type="hidden" name="TypeCar" value="<?php echo htmlspecialchars($TypeCar); ?>">
                <input type="hidden" name="CarName" value="<?php echo htmlspecialchars($CarName); ?>">
                <input type="hidden" name="Price" value="<?php echo htmlspecialchars($Price); ?>">
    
                <div class="form-group">
                    <input type="text" id="name" name="full_name" placeholder="Full Name" required minlength="3" maxlength="50" autocomplete="name">
                </div>
    
                <div class="form-row">
                    <div class="form-col">
                        <input type="email" id="email" name="email" placeholder="Email Address" required autocomplete="email">
                    </div>
                    <div class="form-col">
                        <input type="tel" id="phone" name="number_phone" placeholder="Phone Number" required autocomplete="tel">
                    </div>
                </div>
    
                <div class="form-group">
                    <input type="text" id="address" name="adresse" placeholder="Address" required autocomplete="street-address">
                </div>
    
                <button type="submit" name="Rent" class="btn" <?php echo empty($CarName) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?>>
                    Confirm Reservation
                </button>
            </form>
    
            <div id="message-container"></div>
        </div>
    </div>

    <script src="home.js"></script>
    
    <!-- Debug info -->
    <script>
    console.log("Car selection debug:");
    console.log("TypeCar: <?php echo $TypeCar; ?>");
    console.log("CarName: <?php echo $CarName; ?>");
    console.log("Price: <?php echo $Price; ?>");
    console.log("Session id_rent: <?php echo isset($_SESSION['id_rent']) ? $_SESSION['id_rent'] : 'Not set'; ?>");
    </script>
</body>
</html>