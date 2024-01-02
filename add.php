<?php
include 'database.php';

$obj = new Database();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sname = $_POST['sname'];
    $sage = $_POST['sage'];
    $scity = $_POST['scity'];


    $values = [
        'student_name' => $sname,
        'age' => $sage,
        'city' => $scity
    ];


    if ($obj->insert('students', $values)) {

        header('Location: index.php');
        exit();
    } else {
        echo "<h2>Data is Not Inserted Successfully.</h2>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        form {
            width: 300px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add Student</h2>
        <form action="" method="post">
            <label for="sname">Name</label>
            <input type="text" name="sname" id="sname" required>

            <label for="sage">Age</label>
            <input type="number" name="sage" id="sage" required>

            <label for="scity">City</label>
            <select name="scity" id="scity" required>
                <?php
                $obj->select('citytb');
                $cities = $obj->getResult();

                foreach ($cities as $city) {
                    $cid = $city['cid'];
                    $cname = $city['cname'];
                    echo "<option value='$cid'>$cname</option>";
                }
                ?>
            </select>

            <input type="submit" value="Add">
        </form>
    </div>
</body>

</html>