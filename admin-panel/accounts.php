<?php
session_start();

if (!isset($_SESSION['UserID']) || ($_SESSION['UserRole'] !== 'admin' && $_SESSION['UserRole'] !== 'webmaster')) {
    header("Location: ../index/index.php");
    exit();
}

$host = "you can't";
$username = "see it";
$password = "guess";
$database = "vstdb";
$port = 3306;

$caFile = "../DigiCertGlobalRootCA.crt.pem";

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, $caFile, NULL, NULL);
mysqli_real_connect($conn, $host, $username, $password, $database, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">User Accounts</div>
                <div class="button-group">
                    <a href ="admin.php" class = "button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <h1>User Accounts</h1>

        <form method="POST" action="accounts.php">
            <input type="text" name="search" class = "userSearch" placeholder="Search by UID, Username, or Email">
            <button type="submit">Search</button>
            <button type="button" onclick="resetFilters()">Reset</button>
            <br>
            <label for="accountStatus">Filter by Account Status:</label>
            <select name="accountStatus" id="accountStatus">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="banned">Banned</option>
            </select>

            <label for="userRole">Filter by User Role:</label>
            <select name="userRole" id="userRole">
                <option value="all">All</option>
                <option value="basic">Basic Users</option>
            </select>

            <label for="sort">Sort by:</label>
            <select name="sort" id="sort">
                <option value="username">Username (A-Z)</option>
                <option value="email">Email (A-Z)</option>
                <option value="suspension_date">Suspension Date</option>
            </select>

            <button type="submit">Apply Filters</button>
        </form>
        
        <br>

        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Account Status</th>
                <th>Role</th>
                <th>Flag Count</th>
                <th>Actions</th>
                <th>Suspension Date</th>
            </tr>

            <?php

                require 'functions.php';
                $sql = "SELECT UID, UserName, Email, AccountStatus, UserRole, FlagCount, SuspensionDate FROM User WHERE 1=1";

                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $searchTerm = $_POST['search'];
                    $sql .= " AND (UID LIKE '%$searchTerm%' OR UserName LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%')";
                }

                if (isset($_POST['accountStatus']) && $_POST['accountStatus'] !== 'all') {
                    $accountStatus = $_POST['accountStatus'];
                    $sql .= " AND AccountStatus = '$accountStatus'";
                }

                if (isset($_POST['userRole']) && $_POST['userRole'] === 'basic') {
                    $sql .= " AND UserRole = 'basic'";
                }

                $orderBy = 'UserName';
                if (isset($_POST['sort'])) {
                    $sortOption = $_POST['sort'];
                    if ($sortOption === 'email') {
                        $orderBy = 'Email';
                    } elseif ($sortOption === 'suspension_date') {
                        $orderBy = 'SuspensionDate';
                    }
                }

                $sql .= " ORDER BY $orderBy";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["UID"] . "</td>";
                        echo "<td>" . $row["UserName"] . "</td>";
                        echo "<td>" . $row["Email"] . "</td>";
                        echo "<td>" . $row["AccountStatus"] . "</td>";
                        echo "<td>" . $row["UserRole"] . "</td>";
                        echo "<td>" . $row["FlagCount"] . "</td>";

                        if ($_SESSION['UserRole'] === 'admin' && $row["UserRole"] === 'basic') {

                            echo "<td>";
                            echo "<form method='POST' action='userStatus.php'>";
                            echo "<input type='hidden' name='userID' value='" . $row["UID"] . "'>";
                            echo "<button type='submit' name='action' value='ban'>Ban</button>";
                            echo "<button type='submit' name='action' value='suspend'>Suspend</button>";
                            echo "<button type='submit' name='action' value='reactivate'>Reactivate</button>";
                            echo "</form>";
                            echo "</td>";

                        } else {
                            echo "<td>You can't do that</td>";
                        }

                        if ($row["AccountStatus"] === 'suspended') {
                            echo "<td>" . $row["SuspensionDate"] . "</td>";
                        } else {
                            echo "<td>Not</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No user accounts found</td></tr>";
                }
                mysqli_close($conn);
            ?>
        </table>
    </div>
    <script>
    function resetFilters() {

        document.getElementById('accountStatus').value = 'all';
        document.getElementById('userRole').value = 'all';
        document.getElementById('sort').value = 'username';
        document.getElementsByName('search')[0].value = '';
        document.querySelector('form').submit();
        
    }
</script>
</body>
</html>
