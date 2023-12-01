<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "sorry";
    $username = "nope";
    $password = "you can't have it";
    $database = "vstdb";
    $port = 3306;

    
    $caFile = "../DigiCertGlobalRootCA.crt.pem";

    
    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, $caFile, NULL, NULL);
    mysqli_real_connect($conn, $host, $username, $password, $database, $port);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    
    $sql = "SELECT * FROM User WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        
        if (password_verify($password, $row["PasswordHash"])) {
            
            $_SESSION["UserID"] = $row["UID"];
            $_SESSION["UserName"] = $row["UserName"];
            $_SESSION["UserRole"] = $row["UserRole"];
            $_SESSION["EmailVerified"] = $row["EmailVerified"];

            
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found. Please register or check your email.";
    }

    
    $stmt->close();
    mysqli_close($conn);
}
?>
