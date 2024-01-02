<?php
include 'database.php';

$obj = new Database();

$colName = "students.id,students.student_name,students.age,citytb.cname";
$join = "citytb ON students.city = citytb.cid";
$limit = 3;

$obj->select('students', '*', $join, null, null, $limit);
$result = $obj->getResult();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Data</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;

        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            border-collapse: collapse;
            width: 500px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
        }

        th,
        td {
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .pagination-container {
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            align-items: center;
            justify-content: center;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active a {
            background-color: #4CAF50;
            color: white;
        }

        .add-btn {
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="add-btn" onclick="location.href='add.php'">Add</button>

        <table>
            <tr>
                <th>Id</th>
                <th>Student Name</th>
                <th>Age</th>
                <th>City</th>
                <th>Action</th>
            </tr>

            <?php foreach ($result as list("id" => $id, "student_name" => $name, "age" => $age, "cname" => $city)): ?>
                <tr>
                    <td>
                        <?php echo $id; ?>
                    </td>
                    <td>
                        <?php echo $name; ?>
                    </td>
                    <td>
                        <?php echo $age; ?>
                    </td>
                    <td>
                        <?php echo $city; ?>
                    </td>
                    <td>
                        <a class="edit-btn" href="edit.php?id=<?php echo $id; ?>">Edit</a>
                        <a class="delete-btn" href="delete.php?id=<?php echo $id; ?>"
                            onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination-container">
            <ul class="pagination">
                <?php
                echo $obj->pagination('students', $join, null, $limit);
                ?>
            </ul>
        </div>
    </div>
</body>

</html>