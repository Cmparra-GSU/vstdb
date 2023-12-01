<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();
$conn = connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plugin Selector</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <div class="main-content">
        <div class = "selectorContainer">
            <h1>Plugins</h1><br>
            <form method="POST" action="plugin-selector.php" class="searchContainer">
                <input type="text" name="search" class="pluginSearch" placeholder="Search by PID, Name, or Developer"><br>
                <button type="submit" class="searchreset">Search</button>
                <button type="button" class="searchreset" onclick="resetFilters()">Reset</button>
            </form><br>
            <br>
            <table>
                <tr>
                    <th>Plugin ID</th>
                    <th>Name</th>
                    <th>Developer</th>

                </tr>

                <?php
                    $sql = "SELECT PID, Name, DevName, Visible FROM Plugin WHERE 1=1";

                    if (isset($_POST['search']) && !empty($_POST['search'])) {
                        $searchTerm = $_POST['search'];
                        $sql .= " AND (PID LIKE '%$searchTerm%' OR Name LIKE '%$searchTerm%' OR DevName LIKE '%$searchTerm%')";
                    }

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row["PID"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["DevName"] . "</td>";
                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No plugins found</td></tr>";
                    }
                    mysqli_close($conn);
                ?>
            </table>
        </div>
    </div>
    <script>
        function resetFilters() {
            document.querySelector('.pluginSearch').value = '';
            document.querySelector('form').submit();
        }

    </script>
</body>
</html>
