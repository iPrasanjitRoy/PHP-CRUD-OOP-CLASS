<?php
include 'database.php';

$obj = new Database();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $studentId = $_GET['id'];

    $obj->select('students', '*', null, "id = $studentId");
    $result = $obj->getResult();

    if (!empty($result)) {

        if ($obj->delete('students', "id = $studentId")) {
            echo "<h2>Record Deleted Successfully.</h2>";
            header('Location: index.php');
        } else {
            echo "<h2>Error Deleting Record.</h2>";
        }
    } else {
        echo "<h2>Student Not Found.</h2>";
    }
} else {
    echo "<h2>Invalid Request.</h2>";
}
?>