<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $_FILES['photo']['type'];
    
    if (in_array($fileType, $allowedTypes)) {
        $target_dir = "uploads/photos/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO photos (photo_path) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $target_file);

            if ($stmt->execute()) {
                echo "Photo uploaded successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Photo Gallery</title>
</head>
<body>
    <h1>Photo Gallery</h1>
    <form action="gallery.php" method="post" enctype="multipart/form-data">
        <input type="file" name="photo" required>
        <input type="submit" value="Upload Photo">
    </form>

    <h2>Gallery</h2>
    <div class="gallery">
        <?php
        include 'includes/db.php';

        $sql = "SELECT photo_path FROM photos ORDER BY uploaded_at DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<img src='" . htmlspecialchars($row['photo_path']) . "' alt='Photo'>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>