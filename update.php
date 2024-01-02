<?php
include 'database.php';

$obj = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $studentName = $_POST['sname'];
    $age = $_POST['sage'];
    $city = $_POST['scity'];

    $updateData = [
        'student_name' => $studentName,
        'age' => $age,
        'city' => $city
    ];

    if ($obj->update('students', $updateData, "id = $id")) {
        echo "<h2>Record Updated Successfully.</h2>";
    } else {
        echo "<h2>Error Updating Record.</h2>";
    }

    echo '<a href="index.php">Back To Index</a>';
} else {
    echo "<h2>Invalid request.</h2>";
}
?>