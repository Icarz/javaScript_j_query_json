<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM users JOIN Profile ON users.user_id = Profile.user_id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chuck Severance's Resume Registry 99c6da1d</title>
</head>
<body>
<div class="container">
    <h2>Chuck Severance's Resume Registry</h2>
    <?php
    if (isset($_SESSION['name'])) {
        echo '<p><a href="logout.php">Logout</a></p>';
    }
    ?>
    <?php
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
        unset($_SESSION['success']);
    }
    ?>

    <ul>
        <?php
        if (!isset($_SESSION['name'])) {
            echo "<p><a href='login.php'>Please log in</a></p>";
        }
        if (true) {
            if (count($rows) > 0) { // Ensures there are rows to display
                echo "<table border='1'>";
                echo " <thead><tr>";
                echo "<th>Name</th>";
                echo " <th>Headline</th>";
                if (isset($_SESSION['name'])) {
                    echo("<th>Action</th>");
                }
                echo " </tr></thead>";
                foreach ($rows as $row) {
                    echo "<tr><td>";
                    echo("<a href='view.php?profile_id=" . $row['profile_id'] . "'>" . htmlentities($row['first_name']) . " " . htmlentities($row['last_name']) . "</a>");
                    echo("</td><td>");
                    echo(htmlentities($row['headline']));
                    echo("</td>");
                    if (isset($_SESSION['name'])) {
                        echo("<td>");
                        echo('<a href="edit.php?profile_id=' . $row['profile_id'] . '">Edit</a> / <a href="delete.php?profile_id=' . $row['profile_id'] . '">Delete</a>');
                        echo("</td>");
                    }
                    echo("</tr>\n");
                }
                echo "</table>";
            } else {
                echo 'No rows found';
            }
        }
        ?>
    </ul>
    <a href="add.php">Add New Entry</a></p> <!-- Ensure this is always visible -->
    
        <b>Note:</b> Your implementation should retain data across multiple
        logout/login sessions. This sample implementation clears all its
        data periodically - which you should not do in your implementation.
    </p>
</div>
</body>
</html>
