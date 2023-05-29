<?php

namespace App;

use Exception;

class Department
{
    public static function list()
    {
        global $conn;

        try {
            $sql = 'SELECT d.dept_no Department_Number, 
                           d.dept_name Department_Name,
                           dmr.emp_no Manager_Number, 
                           CONCAT(e.first_name, " ", e.last_name) Manager_Name,
                           dmr.from_date From_Date,
                           IF(dmr.to_date = "9999-01-01", "Current", dmr.to_date) To_Date,
                           IF(dmr.to_date = "9999-01-01", YEAR(NOW())-YEAR(dmr.from_date), YEAR(dmr.to_date)-YEAR(dmr.from_date)) Number_of_Years
                    FROM departments d
                    INNER JOIN dept_manager dmr ON d.dept_no=dmr.dept_no
                    INNER JOIN employees e ON dmr.emp_no=e.emp_no
                    WHERE dmr.to_date = "9999-01-01"
                    GROUP BY Department_Name
                    ORDER BY Department_Name, Number_of_Years DESC
                        ;
                ';

            $statement = $conn->prepare($sql);
            $statement->execute();
            $records = [];

            while ($row = $statement->fetchObject('App\Department')) {
                array_push($records, $row);
            }

            return $records;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }
}