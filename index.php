<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>

    <link rel="stylesheet" href="asset/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="asset/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="loading">
    <div class="title">Loading...</div>

    <div class="progress progress-sm">
        <div
            id="loadingBar"
            class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
            role="progressbar"
            style="width:0%"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-valuenow="0">
        </div>
    </div>

</div>

<script src="script.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>