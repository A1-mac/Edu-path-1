<?php
$servername = "localhost";
$dbusername = "mac"; 
$dbpassword = "pass";
$dbname = "Edupath_db";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($query)) {
    $sql = "SELECT DISTINCT head_office FROM universities WHERE head_office LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $searchParam = "%$query%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['head_office'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['head_office'], ENT_QUOTES, 'UTF-8') . "</option>";
        }
    } else {
        echo "<option disabled value=''>Location not available</option>";
    }

    $stmt->close();
}

$conn->close();
?>
