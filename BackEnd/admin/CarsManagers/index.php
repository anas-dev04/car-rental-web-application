<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri&family=Cairo:wght@200&family=Poppins:wght@100;200;300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars Management</title>

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
            <button class="logout" onclick="location.href='logout.php'">Déconnexion</button>
        </div>
</div>

    <div class="content">
        <a href="#" class="show-add-car">Add a New Car</a>
        <a href="#" class="show-cars-availbles">Cars Availbles</a>
    </div>

        <div class="add_new_car">
            <form action="insert.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <h2>Add A New Car</h2>
                <label for="">Type Of The Car</label>
                <select required name="TypeCar">
                    <option value="" disabled selected hidden>Choose an option</option>
                    <option value="Economy">Economy</option>
                    <option value="Luxury">Luxury</option>
                    <option value="Compact">Compact</option>
                    <option value="Suv">Suv</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
                <label for="">Car Name :</label>
                <input type="text" name='carname' required>
                <label for="">Type Of Gasoile</label>
                <select name="Typegasoline" required>
                    <option value="" disabled selected hidden>Choose an option</option>
                    <option value="gasoline">gasoline</option>
                    <option value="diesel">diesel</option>
                </select>
                <label for="">manual or automatic</label>
                <select name="manualorautomatic" required>
                    <option value="" disabled selected hidden>Choose an option</option>
                    <option value="manual">manual</option>
                    <option value="automatic">automatic</option>
                </select>
                <label for="">Pesonne Visable</label>
                <select name="PesonneVisable" required>
                    <option value="" disabled selected hidden>Choose an option</option>
                    <option value="5 Personnes">5 Personnes</option>
                    <option value="4 personnes">4 Personnes</option>
                </select>
                <label for="">Price Of The Car</label>
                <input type="text" name='price'>
                <br>
                <label for="">Select An Image</label>
                <input type="file" id="file" name='image' style='display:block;'>
                <br>
                <button name='upload' class="submit" type="submit">Upload</button>
            </form>
        </div>

        <div class="cars hide">
            <?php
                include("config.php");
                $result = mysqli_query($con, "SELECT * FROM cars");

                if (!$result) {
                    echo "<p>Error fetching cars: " . mysqli_error($con) . "</p>";
                } elseif (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)){
            ?>
                    <div class="box-container">
                        <h3 class="TypeCar"><?php echo htmlspecialchars($row['TypeCar']); ?></h3>
                        <h2 class="carname"><?php echo htmlspecialchars($row['carname']); ?></h2>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['carname']); ?>"/>
                        <div class="attributs">
                            <p><i class="fa-solid fa-gas-pump"></i> <?php echo htmlspecialchars($row['Typegasoline']); ?></p>
                            <p><i class="fa-solid fa-car"></i> <?php echo htmlspecialchars($row['manualorautomatic']); ?></p>
                            <p><i class="fa-solid fa-person"></i> <?php echo htmlspecialchars($row['PesonneVisable']); ?></p>
                        </div>
                        <h4 class="prix">Prix : <?php echo htmlspecialchars($row['price'])."$"; ?></h4>
                        <button class="Update" data-id="<?php echo $row['id_cars']; ?>">Update</button>
                        <button class="Delete" data-id="<?php echo $row['id_cars']; ?>">Delete</button>

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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const add_new_car = document.querySelector('.add_new_car');
                const cars = document.querySelector('.cars');

                // إخفاء جميع النماذج
                function hideAll() {
                    add_new_car.style.display = 'none';
                    cars.style.display = 'none';
                }

                // عند الضغط على "Update Password"
                document.querySelector('.show-add-car').addEventListener('click', function(e) {
                    e.preventDefault();
                    hideAll();
                    add_new_car.style.display = 'block';
                });

                // عند الضغط على "Add a new admins"
                document.querySelector('.show-cars-availbles').addEventListener('click', function(e) {
                    e.preventDefault();
                    hideAll();
                    cars.style.display = 'grid';
                });

            });
            document.addEventListener("DOMContentLoaded", function () {
        // Supprimer une voiture
        document.querySelectorAll('.Delete').forEach(button => {
            button.addEventListener('click', function () {
                const carId = this.dataset.id;
                if (confirm("Do you really want to delete this car?")) {
                    fetch(`delete_car.php?id_cars=${carId}`, {
                        method: "GET"
                    }).then(res => res.text())
                        .then(data => {
                        alert(data);
                          location.reload(); // تحديث الصفحة بعد الحذف
                        });
                }
            });
        });

        // Modifier une voiture
        document.querySelectorAll('.Update').forEach(button => {
            button.addEventListener('click', function () {
                const carId = this.dataset.id;
                window.location.href = `edit_car.php?id_cars=${carId}`; // تحويل لصفحة التعديل
            });
        });
    });
        </script>
</body>
</html>