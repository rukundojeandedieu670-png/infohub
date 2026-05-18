<?php
// 403 Forbidden Page
ob_start();
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">403</div>
        <div class="error-message">Access Denied</div>
        <div class="error-description">
            You don't have permission to access this resource.
        </div>
        <a href="<?php echo APP_URL; ?>" class="btn btn-lg">Back to Home</a>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = '403 - Access Denied';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/errors.css">
</head>
<body>
    <?php echo $content; ?>
</body>
</html>
