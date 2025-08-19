<?php
session_start();
include "Main.php";
$db = new Index;
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
$con = $db->con;

if (isset($_POST['backup'])) {
    $tables = [];
    $sql = "SHOW TABLES";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die("Error fetching tables: " . mysqli_error($con));
    }

    // Collect all table names
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    if (empty($tables)) {
        die("No tables found in the database.");
    }

    // Debug: show tables
    // echo "Tables to backup: " . implode(", ", $tables);

    $sqlScript = "";

    foreach ($tables as $table) {
        // Get the CREATE TABLE statement
        $createResult = mysqli_query($con, "SHOW CREATE TABLE `$table`");
        if (!$createResult) {
            die("Error getting CREATE TABLE for $table: " . mysqli_error($con));
        }
        $createRow = mysqli_fetch_row($createResult);
        $sqlScript .= "\n\n" . $createRow[1] . ";\n\n";

        // Get table data
        $dataResult = mysqli_query($con, "SELECT * FROM `$table`");
        if (!$dataResult) {
            die("Error selecting data from $table: " . mysqli_error($con));
        }
        $columnCount = mysqli_num_fields($dataResult);

        // Loop through data rows and build INSERT statements
        while ($row = mysqli_fetch_row($dataResult)) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                if ($row[$j] === null) {
                    $sqlScript .= 'NULL';
                } else {
                    $sqlScript .= '"' . mysqli_real_escape_string($con, $row[$j]) . '"';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n";
    }

    if (empty(trim($sqlScript))) {
        die("Backup script is empty. No data saved.");
    }

    // Ensure backup directory exists
    $backup_dir = __DIR__ . '/backup/file/';
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }

    // Use timestamped backup filename
$backup_file_name = $backup_dir . '_backup_.sql';


    // Save the backup file
    if (file_put_contents($backup_file_name, $sqlScript) === false) {
        die("Failed to write backup file.");
    }

    $_SESSION['message'] = ['type' => 'success', 'message' => 'Backup saved successfully to file folder: ' . basename($backup_file_name)];
    header("Location: backup.php");
    exit();
}

if (isset($_POST['restore'])) {
    $sql = '';
    $error = '';
    $statements = [];

    $backupFile = __DIR__ . '/backup/file/_backup_.sql';
if (file_exists($backupFile)) {
    mysqli_query($con, 'SET foreign_key_checks = 0');

    // Drop all existing tables
    $result = mysqli_query($con, 'SHOW TABLES');
    while ($row = mysqli_fetch_array($result)) {
        mysqli_query($con, 'DROP TABLE IF EXISTS `' . $row[0] . '`');
    }

    // Read SQL file
    $lines = file($backupFile);
    $current = '';
    $statements = [];

    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || trim($line) === '') continue;

        $current .= $line;
        if (substr(trim($line), -1) == ';') {
            $statements[] = $current;
            $current = '';
        }
    }

    // Execute all statements (both CREATE TABLE and others)
    foreach ($statements as $query) {
        if (!mysqli_query($con, $query)) {
            $error .= "Query error: " . mysqli_error($con) . "\nQuery: $query\n\n";
        }
    }

    mysqli_query($con, 'SET foreign_key_checks = 1');

    if ($error) {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Error during restoration. Check log.'];
    } else {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Database successfully restored.'];
    }
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'No backup file found. Please try again.'];
}

    header('Location: backup.php'); // Adjust to your actual page
    exit;
}

?>


<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>
<div id="main">
    <div class="main-container">

        <!-- will use it later to improve the ui -->
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                       
                        <li class="breadcrumb-item active" aria-current="page">Backup</li>
                    </ol>
                </nav>
            </div>
        </div>


        <section class="section">
            <div class="row g-4">

                <!-- Backup Card -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow rounded-4 h-100">
                        <div class="card-body">
                            <?php include '../layouts/sweetalert.php' ?>

                            <h5 class="card-title">Back Up</h5>
                            <form action="" method="POST" class="mt-3">
                                <p>
                                    Creating regular backups of your database is crucial for data integrity and system recovery. In case of unexpected events or data loss, having a recent backup ensures that you can restore your system to a known, stable state.
                                </p>


                                <div class="text-end mt-4">
                                    <button name="backup" class="btn btn-primary">
                                        Perform System Backup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Restore Card -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow rounded-4 h-100">
                        <div class="card-body">
                            <?php include '../layouts/sweetalert.php' ?>

                            <h5 class="card-title">Restore</h5>
                            <form action="" method="POST" class="mt-3">
                                <p>
                                    Restoring your database is essential for system recovery and maintaining data integrity. In the event of unexpected issues or data loss, a recent backup allows you to restore your system to a known, stable state.
                                </p>


                                <div class="text-end mt-4">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#restoreModal">
                                        Perform System Restore
                                    </button>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="restoreModalLabel">Confirm Restore</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to restore the system to the last backup?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="restore" class="btn btn-primary">Yes, Restore</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>


        </section>


    </div>
</div>


<?php include '../layouts/footer.php' ?>