<?php

namespace App;

use Exception;

class Employee
{
    protected $empName;
    protected $deptName;
    protected $empGender;
    protected $empBirthdate;
    protected $empTitle;

    public function getEmpName()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getBirthdate()
    {
        return $this->birth_date;
    }

    public function getDeptName()
    {
        return $this->dept_name;
    }

    public function getTitle()
    {
        return $this->title;
    }

     public static function getByDeptId($id)
    {
        global $conn;
        try {
            $sql = "
                SELECT * FROM departments
                WHERE dept_no=:id
                LIMIT 1
            ";
            $statement = $conn->prepare($sql);
            $statement->execute([
                'id' => $id
            ]);
            $result = $statement->fetchObject('App\Employee');
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }

        return null;
    }

     public static function getByEmpId($id)
    {
        global $conn;
        try {
            $sql = "
                SELECT * FROM employees
                WHERE emp_no=:id
                LIMIT 1
            ";
            $statement = $conn->prepare($sql);
            $statement->execute([
                'id' => $id
            ]);
            $result = $statement->fetchObject('App\Employee');
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }

        return null;
    }

     public static function getByTitleId($id)
    {
        global $conn;
        try {
            $sql = "
                SELECT * FROM titles
                WHERE emp_no=:id
                LIMIT 1
            ";
            $statement = $conn->prepare($sql);
            $statement->execute([
                'id' => $id
            ]);
            $result = $statement->fetchObject('App\Employee');
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return null;
    }




    public static function list($dept)
    {
        global $conn;

        try {
            $sql = '
                 SELECT 
                    t.title title,
                    CONCAT(first_name, " ", last_name) complete_name,
                    e.emp_no,
                    birth_date birthday,
                    FLOOR(DATEDIFF(NOW(), birth_date)/365) age,
                    IF(gender = "M", "Male", "Female") gender,
                    hire_date,
                    FORMAT(s.salary, "C") salary
                FROM employees e
                INNER JOIN dept_emp demp ON (demp.emp_no=e.emp_no AND demp.to_date = "9999-01-01") 
                INNER JOIN (
                    SELECT emp_no, MAX(to_date) to_date
                    FROM titles
                    GROUP BY emp_no
                ) tmax ON tmax.emp_no=e.emp_no
                INNER JOIN titles t ON t.emp_no=e.emp_no AND t.to_date=tmax.to_date
                INNER JOIN (
                    SELECT emp_no, MAX(to_date) to_date
                    FROM salaries
                    GROUP BY emp_no
                ) smax ON smax.emp_no=e.emp_no
                INNER JOIN salaries s ON s.emp_no=e.emp_no AND s.to_date=smax.to_date
                WHERE demp.dept_no=:id 
                    ';

            $statement = $conn->prepare($sql);
            $statement->execute([
                'id' => $dept
            ]);
            $records = [];

            while ($row = $statement->fetchObject('App\Employee')) {
                array_push($records, $row);
            }

            return $records;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }


        public static function historylist($emp)
    {
        global $conn;

        try {
            $sql = '
            SELECT from_date, IF(to_date="9999-01-01", "Current", to_date) to_date, FORMAT(salary, "C") salary
            FROM salaries
            WHERE emp_no = :emp
            ORDER BY to_date DESC
            ;
                   ';

            $statement = $conn->prepare($sql);
            $statement->execute([
                'emp' => $emp
            ]);
            $records = [];

            while ($row = $statement->fetchObject('App\Employee')) {
                array_push($records, $row);
            }

            return $records;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }
}