<?php

require "config.php";

use App\Employee;

$emps = Employee::historylist($_GET['emp']);

$department = Employee::getByDeptId($_GET['dept']);
$employee = Employee::getByEmpId($_GET['emp']);
$titles = Employee::getByTitleId($_GET['emp']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salary History</title>
    <style>
table {
width: 100%;
color: #944897;
font-family: arial;
font-size: 20px;
text-align: center;
}
th {
background-color: #944897;
color: white;
}
tr:nth-child(even) {background-color: #f2f2f2}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container-fluid mt-3">
        <a href="javascript:history.back()" class="btn btn-primary">Back</a><br><br>
        <h2>Salary History of <?php echo $employee ->getEmpName();?></h2>
        <h4><strong>Department:</strong> <?php echo $department->getDeptName();?> | <strong>Title:</strong> <?php echo $titles ->getTitle();?> | <strong>Birthdate:</strong> <?php echo $employee ->getBirthdate();?> | <strong>Gender:</strong>
            <?php 
                if($employee ->getGender() == "M"){
                    echo "Male";
                }else{
                    echo "Female";
                }
            ?>
        </h4>
        <table id="salaryTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    foreach ($emps as $row) {
                        echo "<tr>";
                        $cols = get_object_vars($row);
                        echo "<td>".$cols["from_date"]."</td>";
                        echo "<td>".$cols["to_date"]."</td>";
                        echo "<td>$".$cols["salary"]."</td>";

                        echo "</tr>";  
                      }
                    ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('#salaryTable').DataTable();
        } );
    </script>
</body>
</html>