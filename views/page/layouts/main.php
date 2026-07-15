<?php include_once __DIR__ . '/header.php'; ?>

<main class="main-content-wrapper" style="min-height: calc(100vh - 80px - 350px);">

    <?php 
        if (isset($content)) {
            include_once $content;
        } else {
            include_once __DIR__ . '/../home/index.php';
        }
    ?>
</main>

<?php include_once __DIR__ . '/footer.php'; ?>