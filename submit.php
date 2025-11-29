<?php
$servername = "localhost";
$username = "root";       
$password = "";          
$database = "employment_db";

// connection
$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $position = $_POST['position'];
    $qualification = $_POST['qualification'];

    // Handle CV upload
    $cv = "";
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $cv = $target_dir . basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], $cv);
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO applications (fullname, email, phone, gender, dob, position, qualification, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $fullname, $email, $phone, $gender, $dob, $position, $qualification, $cv);

    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
