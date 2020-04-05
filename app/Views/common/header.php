<?php use App\Core\Helpers; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Tasker</title>
        <base href="<?php echo BASE; ?>">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo Helpers::asset('css/style.css'); ?>">
    </head>

    <body>
        <div id="wrapper">
            <section id="content" class="container">
                <header id="header" class="py-3">
                    <div class="row flex-nowrap justify-content-between align-items-center">
                        <div class="col-4 pt-1">
                            <a href="https://github.com/maksimgru/tasker-mvc" class="text-muted" title="Fork me on GitHub" target="_blank">view on GitHub</a>
                        </div>
                        <div class="col-4 text-center">
                            <h1>Tasker</h1>
                        </div>
                        <div class="col-4 d-flex justify-content-end align-items-center">
                            <?php if (Helpers::isAuth()) { ?>
                                <a class="btn btn-sm btn-outline-dark" href="<?php echo Helpers::path('user/logout'); ?>">Logout</a>
                            <?php } else { ?>
                                <a class="btn btn-sm btn-outline-dark" href="<?php echo Helpers::path('user/login'); ?>">Sign in</a>
                            <?php } ?>
                        </div>
                    </div>
                    <nav id="top-nav">
                        <ul class="main-menu fl">
                            <li><a href="<?php echo Helpers::path(); ?>" class="<?php echo Helpers::isCurrentURI('task/table') ? 'active' : ''; ?>">Home</a></li>
                            <li><a href="<?php echo Helpers::path('task/create'); ?>" class="<?php echo Helpers::isCurrentURI('task/create') ? 'active' : ''; ?>">Add New Task</a></li>
                        </ul>
                    </nav>
                </header>

