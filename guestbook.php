<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];

    $sql = "INSERT INTO guestbook (name, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $message);

    if ($stmt->execute()) {
        echo "Message added to guest book!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Book</title>
</head>
<body>
    <form action="guestbook.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea><br>
        <input type="submit" value="Submit Message">
    </form>

    <h2>Guest Messages</h2>
    <ul>
        <?php
        $sql = "SELECT name, message, created_at FROM guestbook ORDER BY created_at DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($row['name']) . ":</strong> " . htmlspecialchars($row['message']) . " <em>[" . $row['created_at'] . "]</em></li>";
        }

        $conn->close();
        ?>
    </ul>
</body>
</html>
