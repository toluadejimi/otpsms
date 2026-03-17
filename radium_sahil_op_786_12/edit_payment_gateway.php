<?php
session_start();
if (!isset($_SESSION['admin'])) {
	header('location: ../index');
	return;
}
include("auth.php");

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
	header('location: payment_gateways');
	return;
}

$q = mysqli_query($conn, "SELECT * FROM payment_gateways WHERE id='$id' LIMIT 1");
$gateway = $q ? mysqli_fetch_assoc($q) : null;
if (!$gateway) {
	header('location: payment_gateways');
	return;
}

if (isset($_POST['save'])) {
	$name = trim((string)($_POST['name'] ?? ''));
	$api_key = trim((string)($_POST['api_key'] ?? ''));
	$secret_key = trim((string)($_POST['secret_key'] ?? ''));
	$status = (int)($_POST['status'] ?? 0);

	$name_s = mysqli_real_escape_string($conn, $name);
	$api_s = mysqli_real_escape_string($conn, $api_key);
	$sec_s = mysqli_real_escape_string($conn, $secret_key);
	$status_s = ($status === 1) ? 1 : 0;

	mysqli_query($conn, "UPDATE payment_gateways SET name='$name_s', api_key='$api_s', secret_key='$sec_s', status='$status_s' WHERE id='$id'");
	header('location: payment_gateways');
	return;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Edit Payment Gateway</title>
	<?php include("include/head.php"); ?>
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
							<li class="breadcrumb-item"><a href="payment_gateways">Payment Gateways</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit</li>
						</ol>
					</div>

					<div class="row">
						<div class="col-lg-8">
							<div class="card mb-4">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">Edit Gateway</h6>
								</div>
								<div class="card-body">
									<form method="post">
										<div class="form-group">
											<label>Name</label>
											<input name="name" class="form-control" value="<?php echo htmlspecialchars($gateway['name'] ?? ''); ?>" required>
										</div>
										<div class="form-group">
											<label>API Key</label>
											<input name="api_key" class="form-control" value="<?php echo htmlspecialchars($gateway['api_key'] ?? ''); ?>">
										</div>
										<div class="form-group">
											<label>Secret Key</label>
											<input name="secret_key" class="form-control" value="<?php echo htmlspecialchars($gateway['secret_key'] ?? ''); ?>">
										</div>
										<div class="form-group">
											<label>Status</label>
											<select name="status" class="form-control">
												<option value="1" <?php echo ((int)($gateway['status'] ?? 0) === 1) ? 'selected' : ''; ?>>Enabled</option>
												<option value="0" <?php echo ((int)($gateway['status'] ?? 0) === 0) ? 'selected' : ''; ?>>Disabled</option>
											</select>
										</div>
										<div class="d-flex" style="gap: 10px;">
											<button class="btn btn-primary" type="submit" name="save">Save</button>
											<a class="btn btn-secondary" href="payment_gateways">Cancel</a>
										</div>
									</form>
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
</body>

</html>

