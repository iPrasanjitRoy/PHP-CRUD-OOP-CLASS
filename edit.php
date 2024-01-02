<?php
include 'database.php';

$obj = new Database();

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $obj->select('students', '*', null, "id = $id");
    $result = $obj->getResult();

    if ($result) {

        $record = $result[0];
        $studentName = $record['student_name'];
        $age = $record['age'];
        $cityId = $record['city'];


        $obj->select('citytb', 'cid, cname');
        $cities = $obj->getResult();
    } else {
        echo "Record not found!";
        exit();
    }
} else {
    echo "Invalid Request!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        select {
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
    <div class="form-container">
        <h2>Edit Student</h2>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="sname">Name</label>
            <input type="text" name="sname" id="sname" value="<?php echo $studentName; ?>">

            <label for="sage">Age</label>
            <input type="number" name="sage" id="sage" value="<?php echo $age; ?>">

            <label for="scity">City</label>
            <select name="scity" id="scity">
                <?php
                foreach ($cities as $city) {
                    $cid = $city['cid'];
                    $cname = $city['cname'];
                    $selected = ($cid == $cityId) ? 'selected' : '';
                    echo "<option value='$cid' $selected>$cname</option>";
                }
                ?>
            </select>

            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>