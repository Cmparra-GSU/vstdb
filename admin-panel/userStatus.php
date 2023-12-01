

<?php
require 'functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'ban') {
        ban();
    } elseif ($action === 'suspend') {
        suspend();
    } elseif ($action === 'reactivate') {
        reactivate();
    } else {
        echo "Invalid action";
    }
} else {

    echo "Action parameter is missing";
}

function ban(){

    $conn = connect();
    $userID = $_POST['userID'];

    $sql = "UPDATE User SET AccountStatus = 'banned' WHERE UID = $userID";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "User banned successfully! <a href='accounts.php'>Back to User Accounts</a>";
    } else {
        echo "Error banning user: " . mysqli_error($conn) . "<a href='accounts.php'>Back to User Accounts</a>";
    }    

    mysqli_close($conn);
}

function suspend(){

    $conn = connect();
    $userID = $_POST['userID'];
    $currentDateTime = date("Y-m-d H:i:s");

    $sql = "UPDATE User SET AccountStatus = 'suspended', SuspensionDate = '$currentDateTime' WHERE UID = $userID";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "User suspended successfully! <a href='accounts.php'>Back to User Accounts</a>" ;
    } else {
        echo "Error suspending user: " . mysqli_error($conn) .  "<a href='accounts.php'>Back to User Accounts</a>";
    }

    mysqli_close($conn);
}

function reactivate(){

    $conn = connect();
    $userID = $_POST['userID'];

    $sql = "UPDATE User SET AccountStatus = 'active' WHERE UID = $userID";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "User reactivated successfully! <a href='accounts.php'>Back to User Accounts</a>";
    } else {
        echo "Error reactivating user: " . mysqli_error($conn) . "<a href='accounts.php'>Back to User Accounts</a>";
    }

    mysqli_close($conn);
}

?>
