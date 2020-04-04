<?php use App\Core\Helpers; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Tasker</title>
        <base href="<?php echo BASE_PATH; ?>">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo Helpers::asset('css/style.css'); ?>">
        <script src="<?php echo Helpers::asset('js/jquery.min.js'); ?>"></script>
        <script src="<?php echo Helpers::asset('js/script.js'); ?>"></script>
    </head>

    <body>
        <div id="wrapper">
            <header id="header">
                <h1 class="text-center">Tasker</h1>
                <div class="text-center">
                    <a href="https://github.com/maksimgru/tasker" class="download" title="Fork me on GitHub" target="_blank">view on GitHub</a>
                </div>
                <nav id="top-nav">
                    <ul class="main-menu fl">
                        <li><a href="<?php echo Helpers::path(); ?>" class="<?php echo Helpers::isCurrentURI('task/table') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo Helpers::path('task/add'); ?>" class="<?php echo Helpers::isCurrentURI('task/add') ? 'active' : ''; ?>">Add New Task</a></li>
                    </ul>
                </nav>
            </header><!-- #header-->

            <section id="content" class="container">
