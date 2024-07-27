<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $rsvp_status = $_POST['rsvp_status'];

    $sql = "UPDATE guests SET rsvp_status=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $rsvp_status, $email);

    if ($stmt->execute()) {
        echo "RSVP status updated successfully!";
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
    <title>RSVP</title>
</head>
<body>
    <form action="rsvp.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="rsvp_status">RSVP:</label>
        <select id="rsvp_status" name="rsvp_status">
            <option value="yes">Yes</option>
            <option value="no">No</option>
            <option value="maybe">Maybe</option>
        </select><br>
        <input type="submit" value="Submit RSVP">
    </form>
</body>
</html>
