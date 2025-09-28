<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="Messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .messagetable {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 260px;
        }
        .messagetable  th, td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        .messagetable td {
            white-space: normal;
            word-wrap: break-word;
            max-width: 150px;
        }
        .messagetable  th {
            background-color: #010058af;
            color: white;
        }
        .messagetable  tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .messagetable button {
            background-color: #010058af;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            transition: 0.5s;
        }
        .messagetable button a {
            color: white;
            text-decoration: none;
        }
        .messagetable button:hover {
            background-color: #3f0097;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>Panel</h2>
            <ul>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/panel.html"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/CarsManagers/index.php"> <i class="fa-solid fa-car"></i> Cars Management</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Reservations/reservations.php"><i class="fa-solid fa-bookmark"></i> Reservations</a></li>
                <li><a href="Messages.html"><i class="fa-solid fa-message"></i> Messages</a></li>
                <li><a href="http://localhost/PFF/PartiePratique/BackEnd/admin/Settings/Settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
            </ul>
        </div>

        <!-- زر تسجيل الخروج -->
        <div class="logout">
            <button class="logout" onclick="location.href='logout.php'">Déconnexion</button>
        </div>
    </div>

    <div class="content">
        <h1>Table The Messages</h1>
        <div class="users"></div>
        <div class="orders"></div>
        <div class="message"></div>
    </div>

</body>
</html>
<?php
session_start();
include('Conexion.php');


    // recuperation des enregistrement
$sql="SELECT * FROM contact";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
  //afficher les enregistrement dans un tableu
    echo "<table border='1' class='messagetable'>";
    echo "<tr><th>FullName</th>
    <th>Email</th>
    <th>Number Phone</th>
    <th>Subject</th>
    <th>Message</th>
    <th>Replay</th>";
    while ($row=mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$row['FullName']."</td>";
        echo "<td>".$row['Email']."</td>";
        echo "<td>".$row['NumberPhone']."</td>";
        echo "<td>".$row['Subject']."</td>";
        echo "<td>".$row['Message']."</td>";
        echo "<td>
    <button><a href='mailto:".$row['Email']."?subject=Reponse To: ".$row['Subject']."'>Reponse</a></button>
    <button><a href='Delete.php?id=".$row['id']."' onclick=\"return confirm('Are You Sure ?');\">Delete</a></button>
</td>";

    }
    echo "</table>";
}else{
    echo "Aucun enregistrement trouvé.";
}
// Fermeture de la connexion
mysqli_close($conn);
?>
