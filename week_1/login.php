<?php
session_start();
require_once "pdo.php";

if (isset($_POST['cancel'])) {
    // Redirect the browser to index.php
    header("Location: index.php");
    exit();
}

$salt = 'XyZzy12*_';
if (isset($_POST['pass']) && isset($_POST['email'])) {
    $check = hash('md5', $salt . $_POST['pass']);

    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];

        // Redirect the browser to index.php
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = 'Invalid email or password';
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Autos Database 99c6da1d</title>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST" action="login.php">
        User Name <input type="text" name="email"><br/>
        Password <input type="text" name="pass"><br/>
        <input type="submit" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find a password hint
        in the HTML comments.
        <!-- Hint: The password is the four character sound a cat
        makes (all lower case) followed by 123. -->
    </p>
</div>
<div class="container">
    <?php
    if (isset($_SESSION['name'])) {
        echo '<p><a href="logout.php">Logout</a></p>';
        echo '<p><a href="add.php">Add New Entry</a></p>';
    } else {
        echo '<p><a href="login.php">Please log in</a></p>';
    }
    ?>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Headline</th>
            <?php if (isset($_SESSION['name'])) { echo "<th>Action</th>"; } ?>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM users JOIN Profile ON users.user_id = Profile.user_id");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "<tr><td>";
            echo('<a href="view.php?profile_id=' . htmlentities($row['profile_id']) . '">' . htmlentities($row['first_name']) . ' ' . htmlentities($row['last_name']) . '</a>');
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
        ?>
    </table>
    <p><a href="add.php">Add New Entry</a></p>
</div>
</body>
</html>
