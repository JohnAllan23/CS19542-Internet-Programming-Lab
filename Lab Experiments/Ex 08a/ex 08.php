<?php
$conn = new mysqli("localhost", "root", "", "banking_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "Insert Customer") {
        $cname = $_POST['cname'];
        if (!empty($cname)) {
            $sql = "INSERT INTO CUSTOMER (CNAME) VALUES ('$cname')";
            if ($conn->query($sql) === TRUE) {
                echo "Customer inserted successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if ($action == "Update Customer") {
        $cname = $_POST['cname'];
        if (!empty($cname)) {
            $sql = "UPDATE CUSTOMER SET CNAME='$cname' WHERE CID=(SELECT CID FROM CUSTOMER WHERE CNAME='$cname')";
            if ($conn->query($sql) === TRUE) {
                echo "Customer updated successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if ($action == "Delete Customer") {
        $cname = $_POST['cname'];
        if (!empty($cname)) {
            $sql = "DELETE FROM CUSTOMER WHERE CNAME='$cname'";
            if ($conn->query($sql) === TRUE) {
                echo "Customer deleted successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if ($action == "Select Customers") {
        $sql = "SELECT * FROM CUSTOMER";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "CID: " . $row["CID"] . " - Name: " . $row["CNAME"] . "<br>";
            }
        } else {
            echo "No customers found!<br>";
        }
    }

    if ($action == "Insert Account") {
        $atype = $_POST['atype'];
        $balance = $_POST['balance'];
        $cid = $_POST['cid'];
        if (!empty($atype) && ($atype == 'S' || $atype == 'C') && is_numeric($balance) && $balance >= 0) {
            $sql = "INSERT INTO ACCOUNT (ATYPE, BALANCE, CID) VALUES ('$atype', '$balance', '$cid')";
            if ($conn->query($sql) === TRUE) {
                echo "Account inserted successfully!<br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if ($action == "Select Accounts") {
        $sql = "SELECT * FROM ACCOUNT";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "ANO: " . $row["ANO"] . " - Type: " . $row["ATYPE"] . " - Balance: " . $row["BALANCE"] . " - CID: " . $row["CID"] . "<br>";
            }
        } else {
            echo "No accounts found!<br>";
        }
    }
}

$conn->close();
?>