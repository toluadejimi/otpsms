<?php
session_start();
if (!isset($_SESSION['admin'])) {
	header('location: ../index');
	return;
}
include("auth.php");

// Create
if (isset($_POST['create'])) {
	$name = trim((string)($_POST['name'] ?? ''));
	$api_key = trim((string)($_POST['api_key'] ?? ''));
	$secret_key = trim((string)($_POST['secret_key'] ?? ''));
	$status = (int)($_POST['status'] ?? 0);

	if ($name !== '') {
		$name_s = mysqli_real_escape_string($conn, $name);
		$api_s = mysqli_real_escape_string($conn, $api_key);
		$sec_s = mysqli_real_escape_string($conn, $secret_key);
		$status_s = ($status === 1) ? 1 : 0;
		// Some schemas require business_id (NOT NULL). Reuse an existing business_id if present, else default to 1.
		$biz_id = 1;
		$biz_q = mysqli_query($conn, "SELECT business_id FROM payment_gateways WHERE business_id IS NOT NULL LIMIT 1");
		if ($biz_q && $biz_row = mysqli_fetch_assoc($biz_q)) {
			$biz_id = (int) ($biz_row['business_id'] ?? 1);
			if ($biz_id <= 0) $biz_id = 1;
		}
		mysqli_query($conn, "INSERT INTO payment_gateways (business_id, name, api_key, secret_key, status) VALUES ('$biz_id','$name_s','$api_s','$sec_s','$status_s')");
		echo "<meta http-equiv='refresh' content='0'>";
	}
}

// Delete
if (isset($_POST['delete'])) {
	$id = (int)($_POST['id'] ?? 0);
	if ($id > 0) {
		mysqli_query($conn, "DELETE FROM payment_gateways WHERE id='$id'");
		echo "<meta http-equiv='refresh' content='0'>";
	}
}

// Toggle status
if (isset($_POST['toggle'])) {
	$id = (int)($_POST['id'] ?? 0);
	$next = (int)($_POST['next'] ?? 0);
	if ($id > 0) {
		$next_s = ($next === 1) ? 1 : 0;
		mysqli_query($conn, "UPDATE payment_gateways SET status='$next_s' WHERE id='$id'");
		echo "<meta http-equiv='refresh' content='0'>";
	}
}

$sql = mysqli_query($conn, "SELECT * FROM payment_gateways ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Payment Gateways</title>
	<?php include("include/head.php"); ?>
	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<script>
	$(document).ready(function() {
		$('#dashboard').removeClass("active");
		$('#payment_gateways').addClass("active");
	});
</script>

<body id="page-top">
	<div id="wrapper">
		<?php include("include/slidebar.php"); ?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<?php include("include/topbar.php"); ?>

				<div class="container-fluid" id="container-wrapper">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Payment Gateways</li>
						</ol>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">Manage Payment Gateways</h6>
								</div>
								<div class="card-body">
									<form method="post" class="mb-4">
										<div class="form-row">
											<div class="form-group col-md-3">
												<label>Name</label>
												<input name="name" class="form-control" placeholder="SprintPay" required>
											</div>
											<div class="form-group col-md-3">
												<label>API Key</label>
												<input name="api_key" class="form-control" placeholder="Public key">
											</div>
											<div class="form-group col-md-3">
												<label>Secret Key</label>
												<input name="secret_key" class="form-control" placeholder="Secret / Bearer token">
											</div>
											<div class="form-group col-md-2">
												<label>Status</label>
												<select name="status" class="form-control">
													<option value="1">Enabled</option>
													<option value="0">Disabled</option>
												</select>
											</div>
											<div class="form-group col-md-1 d-flex align-items-end">
												<button class="btn btn-primary btn-block" type="submit" name="create">Add</button>
											</div>
										</div>
									</form>

									<div class="table-responsive p-2">
										<table class="table align-items-center table-flush" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th>Name</th>
													<th>Status</th>
													<th>API Key</th>
													<th>Secret Key</th>
													<th>Edit</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php while ($row = mysqli_fetch_assoc($sql)) { ?>
													<tr>
														<td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
														<td>
															<?php if ((int)($row['status'] ?? 0) === 1) { ?>
																<span class="badge badge-success">Enabled</span>
															<?php } else { ?>
																<span class="badge badge-secondary">Disabled</span>
															<?php } ?>
														</td>
														<td><?php echo htmlspecialchars($row['api_key'] ?? ''); ?></td>
														<td><?php echo htmlspecialchars($row['secret_key'] ?? ''); ?></td>
														<td>
															<a href="edit_payment_gateway?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
														</td>
														<td class="d-flex" style="gap:8px;">
															<form method="post">
																<input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
																<input type="hidden" name="next" value="<?php echo ((int)($row['status'] ?? 0) === 1) ? 0 : 1; ?>">
																<button class="btn btn-sm btn-warning" type="submit" name="toggle">
																	<?php echo ((int)($row['status'] ?? 0) === 1) ? 'Disable' : 'Enable'; ?>
																</button>
															</form>
															<form method="post" onsubmit="return confirm('Delete this gateway?');">
																<input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
																<button class="btn btn-sm btn-danger" type="submit" name="delete">Delete</button>
															</form>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>
	<?php include("include/script.php"); ?>
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#dataTable').DataTable();
		});
	</script>
</body>

</html>

