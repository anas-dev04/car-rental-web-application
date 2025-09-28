<?php
include("Conexion.php");

$sql = "SELECT 
            r.id_rent,
            c.id_select_car,
            r.pickup_location,
            r.return_location,
            r.pickup_date,
            r.pickup_time,
            r.return_date,
            r.return_time,
            c.TypeCar,
            c.CarName,
            c.Price,
            cl.full_name,
            cl.email,
            cl.number_phone,
            cl.adresse
        FROM rent r
        JOIN rent_cars c ON r.id_rent = c.id_rent
        JOIN clients cl ON c.id_select_car = cl.id_select_car";
$result = $conn->query($sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $id_rent = $_POST['id_rent'];
    $id_select_car = $_POST['id_select_car'];

    // جلب البيانات من نفس العلاقات السابقة
    $query = $conn->prepare("SELECT 
        r.pickup_location, r.return_location, r.pickup_date, r.pickup_time, r.return_date, r.return_time,
        c.TypeCar, c.CarName, c.Price,
        cl.full_name, cl.email, cl.number_phone, cl.adresse
        FROM rent r
        JOIN rent_cars c ON r.id_rent = c.id_rent
        JOIN clients cl ON c.id_select_car = cl.id_select_car
        WHERE r.id_rent = ? AND c.id_select_car = ?");

    $query->bind_param("ii", $id_rent, $id_select_car);
    $query->execute();
    $res = $query->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        // إدخال البيانات في history_reservation
        $stmt_history = $conn->prepare("INSERT INTO history_reservation 
            (full_name, number_phone, email, adresse, 
            CarName, TypeCar, Price, pickup_location, pickup_date, 
            pickup_time, return_location, return_date, return_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt_history->bind_param("sssssssssssss",
            $row['full_name'], $row['number_phone'], $row['email'], $row['adresse'],
            $row['CarName'], $row['TypeCar'], $row['Price'],
            $row['pickup_location'], $row['pickup_date'], $row['pickup_time'],
            $row['return_location'], $row['return_date'], $row['return_time']
        );

        if ($stmt_history->execute()) {
            // حذف البيانات من الجداول الثلاث
            $conn->query("DELETE FROM clients WHERE id_select_car = $id_select_car");
            $conn->query("DELETE FROM rent_cars WHERE id_rent = $id_rent");
            $conn->query("DELETE FROM rent WHERE id_rent = $id_rent");

            echo "<script>alert('The reservation has been archived successfully.'); window.location.href='reservations.php';</script>";
            exit;
        } else {
            echo "Input error to history: " . $conn->error;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations</title>
    <link rel="stylesheet" href="reservations.css">
    <style>
        button {
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            transition: all 0.3s ease;
            font-size: 16px;
            font-weight: 600;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .no-records {
            text-align: center;
            color: #7f8c8d;
            font-size: 18px;
            margin: 50px 0;
            padding: 30px;
            background: #ecf0f1;
            border-radius: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>Panel</h2>
            <ul>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/panel.html"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/CarsManagers/index.php"> <i class="fa-solid fa-car"></i> Cars Management</a></li>
                <li><a href="reservations.php"><i class="fa-solid fa-bookmark"></i> Reservations</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Messages/Messages.php"><i class="fa-solid fa-message"></i> Messages</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Settings/Settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
            </ul>
        </div>

        <div class="logout">
            <button class="logout" onclick="location.href='logout.php'">Déconnexion</button>
        </div>
    </div>

    <div class="title">
        <h1>Reservations list</h1>
    </div>

<div class="reservations">
<?php
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
        echo "<input type='hidden' name='id_rent' value='" . $row['id_rent'] . "'>";
        echo "<input type='hidden' name='id_select_car' value='" . $row['id_select_car'] . "'>";
        echo "<button type='submit' name='confirm' style='background-color: green;'>Done</button>";
        echo "</form>";

        echo "<button style='background-color: red;' onclick='imprimerCard(this)'>Imprimer</button>";
        echo "</div>";
    }
} else {
    echo "<p>There are no reservations currently..</p>";
}
?>
</div>

</body>
<script>
function imprimerCard(button) {
    const card = button.closest('.card');
    if (!card) {
        alert("Card not found!");
        return;
    }

    // Créer une copie de la carte sans les boutons
    const cardClone = card.cloneNode(true);
    const buttons = cardClone.querySelectorAll('button');
    buttons.forEach(btn => btn.remove());

    const pickupLocation = card.dataset.pickup;

    const cardsCount = document.querySelectorAll('.card').length;
    const date = new Date().toLocaleDateString();
    const userData = "iRent Morocco Agency - Number of reservations: " + cardsCount + " - Date: " + date;

    const newWin = window.open("", "_blank");

    const htmlContent = `
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Print Reservation,</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                color: #333;
            }
            .header {
                display: flex;
                justify-content: space-between;
                border-bottom: 3px solid #2980b9;
                padding-bottom: 15px;
                margin-bottom: 25px;
            }
            .company-info {
                text-align: center;
                flex-grow: 1;
            }
            .info {
                background-color: #e8f4fd;
                padding: 15px;
                border-radius: 8px;
                border-left: 4px solid #2980b9;
                margin-bottom: 20px;
            }
            .footer {
                margin-top: 30px;
                border-top: 2px solid #ccc;
                padding-top: 15px;
                display: flex;
                justify-content: space-between;
            }
            .signature-box {
                border: 2px dashed #ccc;
                width: 180px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #999;
                font-style: italic;
                background-color: #f9f9f9;
            }
            #qrcode {
                margin-top: 20px;
                text-align: center;
            }
            .card {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            hr {
                border: 0;
                height: 1px;
                background: #ddd;
                margin: 15px 0;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="logo.webp">
            <div class="company-info">
                <h2>iRent Morocco Agency</h2>
                <p>Adresse: ${pickupLocation}<br>Phone Number: +212 664 409 873</p>
            </div>
            <div id="qrcode"></div>
        </div>

        <div class="info">
            <strong>Date:</strong> ${date}<br>
            <strong>reservation number:</strong> RES-${Date.now().toString().slice(-6)}<br>
            <strong>Total number of reservations:</strong> ${cardsCount}
        </div>

        ${cardClone.outerHTML}

        <div class="footer">
            <div>
                <p><strong>Thank you for your trust</strong></p>
                <p>iRent Morocco Agency</p>
            </div>
            <div>
                <p><strong>Signature:</strong></p>
                <div class="signature-box">Signature here</div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"><\/script>
        <script>
            function generateQR() {
                try {
                    const canvas = document.createElement("canvas");
                    const qrDiv = document.getElementById("qrcode");
                    qrDiv.innerHTML = "";
                    qrDiv.appendChild(canvas);
                    new QRious({
                        element: canvas,
                        value: "${userData}",
                        size: 100,
                        background: "white",
                        foreground: "#2980b9"
                    });
                    setTimeout(() => window.print(), 500);
                } catch (e) {
                    console.error("QR error", e);
                    setTimeout(() => window.print(), 500);
                }
            }
            window.addEventListener("load", generateQR);
        <\/script>
    </body>
    </html>
    `;

    newWin.document.open();
    newWin.document.write(htmlContent);
    newWin.document.close();
}
</script>

</html>
