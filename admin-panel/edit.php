<?php
include('functions.php');
include('plugin-functions.php');
session_start(); // Start the session
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
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Plugin Edit</div>
                <div class="button-group">
                    <a href ="admin.php" class = "button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class = "edit">
            <h1>Plugins</h1><br>
            <form method="POST" action="edit.php" class="searchContainer">
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
                    <th>Actions</th>
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
                            echo "<td>";
                            echo "<div class = 'action-buttons'>";
                            echo "<form method='POST' action='plugin-edit.php'>";
                            echo "<input type='hidden' name='pluginID' value='" . $row["PID"] . "'>";
                            echo "<input type='hidden' name='pluginName' value='" . $row["Name"] . "'>";
                            echo "<input type='hidden' name='developerName' value='" . $row["DevName"] . "'>";
                            echo "<button type='submit'>Edit</button>";
                            echo "</form>";
                            echo "<button onclick='toggleVisibility(" . $row["PID"] . "," . $row["Visible"] . ")'>" . ($row["Visible"] ? "Hide" : "Display") . "</button>";
                            echo "</div>";
                            echo "</td>";
                            
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

        function toggleVisibility(pluginID, currentVisibility) {

            var xhr = new XMLHttpRequest();


            xhr.open("POST", "changeVis.php", true); 

            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            var postData = "action=toggleVisibility&pluginID=" + pluginID + "&newVisibility=" + (currentVisibility ? 0 : 1);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var button = document.querySelector("button[onclick='toggleVisibility(" + pluginID + "," + currentVisibility + ")']");
                    if (currentVisibility) {
                        button.textContent = "Display";
                    } else {
                        button.textContent = "Hide";
                    }
                }
            };
            xhr.send(postData);
        }

    </script>
</body>
</html>
