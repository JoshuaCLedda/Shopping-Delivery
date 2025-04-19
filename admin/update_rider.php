<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;
$result = $index->viewRiderDetails($_GET['u_id']);
while ($row = mysqli_fetch_object($result)) {
    $u_id     = $row->u_id;
    $username = $row->username ?? '';
    $f_name = $row->f_name ?? '';
    $l_name = $row->l_name ?? '';
    $address = $row->address ?? '';
    $email = $row->email ?? '';
    $phone = $row->phone ?? '';
    $security_question = $row->security_question ?? '';
    $security_answer = $row->security_answer ?? '';
    $orcr = $row->orcr ?? '';
    $phyic_exam = $row->phyic_exam ?? '';
    $status = $row->status ?? '';
}


if (isset($_POST['submit'])) {
    $id = $_GET['u_id'];

    $status = $_POST['status'];
    $result = $index->updateRiderApplication(
        $id,
        $status
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Rider Updated Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Update failed! Please try again.'];
    }

    // Redirect to the update_rider.php page
    header("Location: update_rider.php?u_id=$id");  // Optionally pass the u_id in the URL to retain the page state
    exit();
}



?>



<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>

<div id="main">
    <div class="main-container">



        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register Rider</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="rider_details.php" class="btn btn-primary">Back</a>
        </div>



        <?php include 'layouts/alert.php'; ?>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register Rider</h5>
                    </div>

                    <div class="widget card-body shadow-sm">

                        <div class="widget-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">User Name</label>
                                        <input disabled class="form-control" type="text" name="username" value="<?= $username ?>">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input disabled class="form-control" type="text" name="f_name" value="<?= $f_name ?>">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input disabled class="form-control" type="text" name="l_name" value="<?= $l_name ?>">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">Address</label>
                                        <input disabled class="form-control" type="text" name="address" value="<?= $address ?>">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input disabled type="text" class="form-control" name="email" value="<?= $email ?>">
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input disabled class="form-control" type="number" name="phone" value="<?= $phone ?>">
                                    </div>


                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">ORCR <span class="text-danger"> *pdf</span></label><br>
                                        <?php if (!empty($orcr)) : ?>
                                            <a href="uploads/<?= htmlspecialchars($orcr) ?>" target="_blank" class="btn btn-outline-info col-md-12">View ORCR (PDF)</a>
                                        <?php else : ?>
                                            <p class="form-control-plaintext">No ORCR uploaded</p>
                                        <?php endif; ?>
                                    </div>


                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="form-label">Physical Examination <span class="text-danger"> *pdf</span></label><br>
                                        <?php if (!empty($phyic_exam)) : ?>
                                            <a href="uploads/<?= htmlspecialchars($phyic_exam) ?>" target="_blank" class="btn btn-outline-info col-md-12">View Physical Exam (PDF)</a>
                                        <?php else : ?>
                                            <p class="form-control-plaintext text-danger">No Physical Exam Uploaded</p>
                                        <?php endif; ?>
                                    </div>


                                    <div class="form-group col-sm-6 mb-3">
                                        <label class="control-label">Role</label>
                                        <select name="status" class="form-control">
                                            <option value="active" <?= ($status == 'active') ? 'selected' : ''; ?>>Approved</option>
                                            <option value="inactive" <?= ($status == 'inactive') ? 'selected' : ''; ?>>Pending</option>
                                        </select>
                                    </div>




                                    <div class="form-actions my-2">
                                        <button type="submit" name="submit" class="btn btn-success">Update Rider</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>



        <?php include 'layouts/footer.php' ?>