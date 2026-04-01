<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Commerce Admin' ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/lib/simple-datatables.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/layouts/sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
</head>

<script>
    // APPLY THEME BEFORE RENDER (CRITICAL)
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>