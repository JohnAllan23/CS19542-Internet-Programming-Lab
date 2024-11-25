<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "employee_ex9");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Insert Employee
    if ($action == "Insert Employee") {
        $ename = $_POST['ename'];
        $desig = $_POST['desig'];
        $dept = $_POST['dept'];
        $doj = $_POST['doj'];
        $salary = $_POST['salary'];

        if (!empty($ename) && !empty($desig) && !empty($dept) && !empty($doj) && is_numeric($salary) && $salary >= 0) {
            $sql = "INSERT INTO EMPDETAILS (ENAME, DESIG, DEPT, DOJ, SALARY) VALUES ('$ename', '$desig', '$dept', '$doj', '$salary')";
            if ($conn->query($sql) === TRUE) {
                echo "Employee inserted successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // Update Employee
    if ($action == "Update Employee") {
        $ename = $_POST['ename'];
        $desig = $_POST['desig'];
        $dept = $_POST['dept'];
        $doj = $_POST['doj'];
        $salary = $_POST['salary'];

        if (!empty($ename)) {
            $sql = "UPDATE EMPDETAILS SET DESIG='$desig', DEPT='$dept', DOJ='$doj', SALARY='$salary' WHERE ENAME='$ename'";
            if ($conn->query($sql) === TRUE) {
                echo "Employee updated successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // Delete Employee
    if ($action == "Delete Employee") {
        $ename = $_POST['ename'];
        if (!empty($ename)) {
            $sql = "DELETE FROM EMPDETAILS WHERE ENAME='$ename'";
            if ($conn->query($sql) === TRUE) {
                echo "Employee deleted successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // Select Employees
    if ($action == "Select Employees") {
        $sql = "SELECT * FROM EMPDETAILS";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "EMPID: " . $row["EMPID"] . " - Name: " . $row["ENAME"] . " - Designation: " . $row["DESIG"] . " - Department: " . $row["DEPT"] . " - DOJ: " . $row["DOJ"] . " - Salary: " . $row["SALARY"] . "<br>";
            }
        } else {
            echo "No employees found!<br>";
        }
    }
}

$conn->close();
?>