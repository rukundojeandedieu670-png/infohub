<?php
// 404 Error Page
ob_start();
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">404</div>
        <div class="error-message">Page Not Found</div>
        <div class="error-description">
            The page you're looking for doesn't exist or has been removed.
        </div>
        <a href="<?php echo APP_URL; ?>" class="btn btn-lg">Back to Home</a>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = '404 - Page Not Found';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/errors.css">
</head>
<body>
    <?php echo $content; ?>
</body>
</html>
