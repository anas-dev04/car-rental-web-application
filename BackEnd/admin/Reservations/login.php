<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Administrateur</title>
</head>
<body>
    <form method="post" action="panel.html">
        <h2>Login</h2>
            <label>AdminName:</label><br>
            <input type="text" name="adminname" required><br><br>
    
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
    
            <button type="submit">Login</button>
        </form>
</body>
</html>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    include('Conexion.php');

    $adminname = $conn->real_escape_string($_POST["adminname"]);
    $password = $_POST["password"];

    // Vérifier les informations utilisateur
    $sql = "SELECT * FROM admin WHERE AdminName = '$adminname'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Vérifier le mot de passe

        
        if ($password==$admin["password"]) {
            $_SESSION["user_id"] = $admin["id_admin"];
            $_SESSION["adminname"] = $admin["AdminName"];
            $_SESSION["password"]=$admin["password"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Nom d'utilisateur introuvable.";
    }
    if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } 
    $conn->close();
}
?>
