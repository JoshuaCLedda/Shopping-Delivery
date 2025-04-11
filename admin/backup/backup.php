<?php
session_start();
include "../Main.php";
$db = new Index;
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = $db->con;

if (isset($_POST['backup'])) {
    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_row($result);
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";

        $query = "SELECT * FROM $table";
        $result = mysqli_query($con, $query);
        $columnCount = mysqli_num_fields($result);

        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                if (isset($row[$j])) {
                    $sqlScript .= '"' . mysqli_real_escape_string($con, $row[$j]) . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n";
    }

    if (!empty($sqlScript)) {
        $backup_file_name = 'backup_' . date('Y-m-d_H-i-s') . '.sql'; // Dynamic file name
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $backup_file_name . '"');
        header('Content-Length: ' . strlen($sqlScript));
        echo $sqlScript;
        exit();
    }
}

if (isset($_POST['restore'])) {
    $sql = '';
    $error = '';

    if (file_exists(__DIR__ . '/file/_backup_.sql')) {
        mysqli_query($con, 'SET foreign_key_checks = 0');

        // Drop all tables
        $result = mysqli_query($con, 'SHOW TABLES');
        while ($row = mysqli_fetch_array($result)) {
            mysqli_query($con, 'DROP TABLE IF EXISTS ' . $row[0]);
        }

        mysqli_query($con, 'SET foreign_key_checks = 1');

        // Restore the backup
        $lines = file(__DIR__ . '/file/_backup_.sql');
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || trim($line) == '') {
                continue;
            }
            $sql .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $result = mysqli_query($con, $sql);
                if (!$result) {
                    $error .= mysqli_error($con) . "\n";
                }
                $sql = '';
            }
        }

        if ($error) {
            $message1 = "Error during restoration: " . $error;
        } else {
            $message1 = "Database restored successfully";
        }
    } else {
        $messageRed = "No backup file found for restoration.";
    }
}
?>

<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>All Menu</title>
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

<body class="fix-header fix-sidebar">


    <div id="main-wrapper">
        <?php include '../layouts/navbar.php' ?>


        <?php include '../layouts/sidebar.php' ?>

        <div class="page-wrapper">


            <div class="container-fluid my-2">

                <div id="main">
                    <div class="main-container">

                    <!-- will use it later to improve the ui -->
                        <!-- <div class="row">
                            <div class="col">
                                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Backup</li>
                                    </ol>
                                </nav>
                            </div>
                        </div> -->


                        <section class="section">
                            <div class="row g-4">

                                <!-- Backup Card -->
                                <div class="col-md-6">
                                    <div class="card shadow rounded-4 h-100">
                                        <div class="card-body">
                                            <!-- Alert can be placed here -->
                                            <h5 class="card-title">Back Up</h5>
                                            <form action="" method="POST" class="mt-3">
                                                <p>
                                                    Creating regular backups of your database is crucial for data integrity and system recovery. In case of unexpected events or data loss, having a recent backup ensures that you can restore your system to a known, stable state.
                                                </p>
                                                <ul>
                                                    <li>Click the "Perform System Backup" button below.</li>
                                                    <li>Wait for the backup process to complete.</li>
                                                    <li>A notification will show once complete.</li>
                                                </ul>

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
                                <div class="col-md-6">
                                    <div class="card shadow rounded-4 h-100">
                                        <div class="card-body">
                                            <!-- Alert can be placed here -->
                                            <h5 class="card-title">Restore</h5>
                                            <form method="POST" class="mt-3">
                                                <p>
                                                    Restoring your database is essential for system recovery and maintaining data integrity. In the event of unexpected issues or data loss, a recent backup allows you to restore your system to a known, stable state.
                                                </p>
                                                <ul>
                                                    <li>Restore the database to a previous state.</li>
                                                    <li>Undo any changes made after the backup date.</li>
                                                    <li>Data added or modified after the backup will be lost.</li>
                                                </ul>

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
            </div>

        </div>

    </div>
    </div>

    </div>


    </div>

    </div>

    <?php include '../layouts/footer.php' ?>