<?php

function connect() {
    
    $host = "fake.no.databas.azure.com";
    $username = "hi";
    $password = "pass";
    $database = "vstdb";
    $port = 3306;
    
    
    $caFile = "../DigiCertGlobalRootCA.crt.pem";

    
    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, $caFile, NULL, NULL);
    mysqli_real_connect($conn, $host, $username, $password, $database, $port);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function getTotalUsers() {

    $conn = connect();
    
    $sql = "SELECT COUNT(*) AS userCount FROM User";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['userCount'];
    } else {
        return "Error fetching user count";
    }
}

function getTotalPlugins() {

    $conn = connect();

    
    $sql = "SELECT COUNT(*) AS pluginCount FROM plugin";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['pluginCount'];
    } else {
        return "Error fetching plugin count";
    }
}


function getTotalArticles() {

    $conn = connect();

    
    $sql = "SELECT COUNT(*) AS articleCount FROM article";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['articleCount'];
    } else {
        return "Error fetching article count";
    }
}

function getRecentlyFlaggedUsers() {

    $conn = connect();

    
    $sql = "SELECT COUNT(*) AS flaggedUserCount FROM User WHERE FlagCount >= 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['flaggedUserCount'];
    } else {
        return "Error counting flagged users";
    }
}

function isAdmin() {
    
if (isset($_SESSION['UserID']) && ($_SESSION['UserRole'] === 'admin' || $_SESSION['UserRole'] === 'webmaster')) {
    
    $userID = $_SESSION['UserID'];
    $conn = connect(); 

    $sql = "SELECT UserName, Email FROM User WHERE UID = $userID";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $username = $userData['UserName'];
        $email = $userData['Email'];
        
    }
    
} else {
    header("Location: unauthorized.php");
    exit();
}
}


function updatePluginVisibility($pluginID, $newVisibility) {

    $conn = connect();
    $newVisibility = ($newVisibility == 1) ? 1 : 0;

    $sql = "UPDATE Plugin SET Visible = ? WHERE PID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $newVisibility, $pluginID);
        $result = mysqli_stmt_execute($stmt);

        mysqli_close($conn);

        return $result;
    } else {
        mysqli_close($conn);
        return false;
    }
}

function toggleFeaturedArticle($articleID, $conn) {
    
    $sql = "UPDATE article SET Featured = (1 - Featured) WHERE ArticleID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $articleID);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt); 
            return true; 
        } else {
            mysqli_stmt_close($stmt); 
            return false; 
        }
    } else {
        return false; 
    }
}


function toggleArticleVisibility($articleID, $conn, $action) {
    
    $articleID = mysqli_real_escape_string($conn, $articleID);

    $query = '';
    if ($action === 'toggleVisibility') {
        $query = "UPDATE article SET visible = (1 - visible) WHERE ArticleID = ?";
    } else if ($action === 'setVisibility') {
        $query = "UPDATE article SET visible = 1 WHERE ArticleID = ?";
    } else if ($action === 'setInvisibility') {
        $query = "UPDATE article SET visible = 0 WHERE ArticleID = ?";
    }

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $articleID);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt); 
            return "Article visibility updated successfully!";
        } else {
            mysqli_stmt_close($stmt); 
            return "Article visibility update failed: " . mysqli_error($conn);
        }
    } else {
        return "Article visibility update failed: " . mysqli_error($conn);
    }
}
?>