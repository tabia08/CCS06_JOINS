<?php

require "config.php";

use App\Employee;

$emps = Employee::list($_GET['dept']);

$department = Employee::getByDeptId($_GET['dept']);
$manager = Employee::getByEmpId($_GET['emp']);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Employees</title>
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
            <div class="col-md-6">
                <h2><strong>Department:</strong> <?php echo $department->getDeptName();?></h2>
                <h4><strong>Manager:</strong> <?php echo $manager->getEmpName();?></h4>
            </div>
        <table id="employeeTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Complete Name</th>
                    <th>Birthday</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Hire Date</th>
                    <th>Latest Salary</th>
                    <th>Salary History</th>
                </tr>
            </thead>
            <tbody>
                 <?php 
                    foreach ($emps as $row) {
                        echo "<tr>";
                        
                        $cols = get_object_vars($row);
                        echo "<td>".$cols["title"]."</td>";
                        echo "<td>".$cols["complete_name"]."</td>";
                        echo "<td>".$cols["birthday"]."</td>";
                        echo "<td>".$cols["age"]."</td>";
                        echo "<td>".$cols["gender"]."</td>";
                        echo "<td>".$cols["hire_date"]."</td>";
                        echo "<td>$".$cols["salary"]."</td>";
                        echo "<td><a href='salary-history.php?emp=".$cols["emp_no"]."&dept=".$_GET['dept']."
                                  '>View</a></td>";
                        echo "</tr>";  
                      }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('#employeeTable').DataTable();
        } );
    </script>
</body>
</html>
