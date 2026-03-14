<?php
include("config.php");
session_start();

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    // Redirect to the login page or another page
    header("Location: index.php");
    exit();
}

// Process form submission for approval, cancellation, and deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_approval'])) {
        // Iterate through the submitted approvals and update the database
        foreach ($_POST['approval'] as $paymentId => $approvalStatus) {
            // Assuming that the payment_proofs table has columns: id, approval
            $paymentId = mysqli_real_escape_string($db, $paymentId);
            $approvalStatus = ($approvalStatus == 'on') ? 1 : 0;

            // Update the database with the approval status
            $updateQuery = "UPDATE payment_proofs SET approval = '$approvalStatus' WHERE id = '$paymentId'";
            mysqli_query($db, $updateQuery);
        }
    }

    if (isset($_POST['submit_cancel_approval'])) {
        // Iterate through the submitted cancellations and update the database
        foreach ($_POST['cancel_approval'] as $paymentId => $cancelStatus) {
            // Assuming that the payment_proofs table has columns: id, approval
            $paymentId = mysqli_real_escape_string($db, $paymentId);
            $cancelStatus = ($cancelStatus == 'on') ? 1 : 0;

            // Update the database by setting approval to 0
            if ($cancelStatus == 1) {
                $updateQuery = "UPDATE payment_proofs SET approval = '0' WHERE id = '$paymentId'";
                mysqli_query($db, $updateQuery);
            }
        }
    }

    if (isset($_POST['submit_delete'])) {
        // Iterate through the submitted deletions and delete entries from the database
        foreach ($_POST['delete_entry'] as $paymentId => $deleteStatus) {
            // Assuming that the payment_proofs table has columns: id
            $paymentId = mysqli_real_escape_string($db, $paymentId);

            // Delete the entry from the database
            $deleteQuery = "DELETE FROM payment_proofs WHERE id = '$paymentId'";
            mysqli_query($db, $deleteQuery);
        }
    }
}

// Retrieve payment proofs from the database
$query = "SELECT id, username, film_id, selected_seats, proof_path, timestamp, approval FROM payment_proofs";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Proof Approvals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .approval-checkbox,
        .cancel-approval-checkbox {
            transform: scale(1.5);
        }

        #submit-approval-btn,
        #submit-cancel-approval-btn {
            margin-bottom: 20px;
        }

        .proof-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <h1>Payment Proof Approvals</h1>
    <button><a href="admin_dashboard.php">Go to Admin Dashboard</a></button>
    <!-- Form for submitting approvals, cancellations, and deletions -->
    <form method="post" action="approval.php">
        <button type="submit" id="submit_approval_btn" name="submit_approval">Submit Approval</button>
        <button type="submit" id="submit_cancel_approval_btn" name="submit_cancel_approval">Submit Cancel Approval</button>
        <button type="submit" id="submit_delete_btn" name="submit_delete">Submit Delete</button>

        <!-- Table displaying payment proofs -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Film ID</th>
                    <th>Selected Seats</th>
                    <th>Proof Path</th>
                    <th>Timestamp</th>
                    <th>Approval</th>
                    <th>Cancel Approval</th>
                    <th>Delete Entry</th>
                </tr>
                </thead>
            <tbody>
                <?php
                // Display payment proofs in the table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['film_id']}</td>";
                    echo "<td>{$row['selected_seats']}</td>";
                    echo "<td><img src='{$row['proof_path']}' alt='Proof Image' class='proof-image'></td>";
                    echo "<td>{$row['timestamp']}</td>";
                    
                    // Display a permanently checked checkbox if approved
                    if ($row['approval'] == 1) {
                        echo "<td><input type='checkbox' class='approval-checkbox' name='approval[{$row['id']}]' checked disabled></td>";
                    } else {
                        echo "<td><input type='checkbox' class='approval-checkbox' name='approval[{$row['id']}]'></td>";
                    }

                    // Display a checkbox for cancel approval
                    echo "<td><input type='checkbox' class='cancel-approval-checkbox' name='cancel_approval[{$row['id']}]'></td>";
                    echo "<td><input type='checkbox' class='delete-entry-checkbox' name='delete_entry[{$row['id']}]'></td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>

    <?php
    // Close the database connection
    mysqli_close($db);
    ?>
</body>
</html>
