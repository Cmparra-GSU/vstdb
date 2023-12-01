<?php
include('functions.php');
include('article-functions.php');
session_start();
isAdmin();
$conn = connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Edit</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Article Edit</div>
                <div class="button-group">
                    <a href="admin.php" class="button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="edit">
            <h1>Articles</h1><br>
            <form method="POST" action="edit-article.php" class="searchContainer">
                <input type="text" name="search" class="articleSearch" placeholder="Search by Article ID, Title, or Author">
                <div class="searchThings">
                    <button type="submit" class="searchreset">Search</button>
                    <button type="button" class="searchreset" onclick="resetFilters()">Reset</button>
                </div>
            </form><br><br>
            <table>
                <tr>
                    <th>Article ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>

                <?php
                 $sql = "SELECT a.ArticleID, a.Title, u.UserName, a.Visible, a.Featured 
                 FROM article a
                 JOIN user u ON a.AuthorUID = u.UID
                 WHERE 1=1";

                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $searchTerm = $_POST['search'];
                    $sql .= " AND (ArticleID LIKE '%$searchTerm%' OR Title LIKE '%$searchTerm%' OR AuthorUID LIKE '%$searchTerm%')";
                }

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["ArticleID"] . "</td>";
                        echo "<td>" . $row["Title"] . "</td>";
                        echo "<td>" . $row["UserName"] . "</td>";
                        echo "<td>";
                        echo "<div class='action-buttons'>";
                        echo "<form method='POST' action='article-edit.php'>";
                        echo "<input type='hidden' name='articleID' value='" . $row["ArticleID"] . "'>";
                        echo "<input type='hidden' name='Title' value='" . $row["Title"] . "'>";
                        echo "<input type='hidden' name='Author' value='" . $row["UserName"] . "'>";
                        echo "<button type='submit'>Edit</button>";
                        echo "</form>";


                
                        echo "<button onclick='toggleVisibility(" . $row["ArticleID"] . "," . $row["Visible"] . ")'>" . ($row["Visible"] ? "Hide" : "Display") . "</button>";
                        echo "<button onclick='toggleFeatured(" . $row["ArticleID"] . "," . $row["Featured"] . ")'>" . ($row["Featured"] ? "Featured" : "Feature") . "</button>";

                        
                        echo "</div>";
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No articles found</td></tr>";
                }
                mysqli_close($conn);
                ?>
            </table>
        </div>
    </div>
    <script>
        function resetFilters() {
            document.querySelector('.articleSearch').value = '';
            document.querySelector('form').submit();
        }

        function editArticle(articleID) {
            console.log("Editing article with ID:", articleID);
            var url = "article-edit.php?articleID=" + articleID;
            console.log("Navigating to URL:", url);
            window.location.href = url;
        }
        function toggleVisibility(articleID, currentVisibility) {
            var xhr = new XMLHttpRequest();

            xhr.open("POST", "changeArticleVis.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            var postData = "action=toggleVisibility&articleID=" + articleID + "&newVisibility=" + (currentVisibility ? 0 : 1); 

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var button = document.querySelector("button[onclick='toggleVisibility(" + articleID + "," + currentVisibility + ")']");

                    if (currentVisibility) {
                        button.textContent = "Display";
                    } else {
                        button.textContent = "Hide";
                    }

                }
            };

            xhr.send(postData);
        }

        function toggleFeatured(articleID, currentFeatured) {
            var xhr = new XMLHttpRequest();

            xhr.open("POST", "changeFeatured.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            var postData = "action=toggleFeatured&articleID=" + articleID + "&newFeatured=" + (currentFeatured ? 0 : 1); ;

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var button = document.querySelector("button[onclick='toggleFeatured(" + articleID + "," + currentFeatured + ")']");
                    
                    if (currentFeatured) {
                        button.textContent = "Feature";
                    } else {
                        button.textContent = "Featured";
                    }

                }
            };

            xhr.send(postData);
        }


    </script>
</body>
</html>
