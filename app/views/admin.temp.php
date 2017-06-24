<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backoffice</title>
    <link rel="stylesheet" type="text/css" href="<?php echo Helpers::getAsset('css/admin.css'); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo Helpers::getAsset('font-awesome/css/font-awesome.min.css'); ?>">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <nav class="slide-nav slide-nav-large">
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute(''); ?>">
            <i class="fa fa-pie-chart" aria-hidden="true"></i>&emsp;Dashboard
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('update'); ?>"> <!-- 404 -->
            <i class="fa fa-arrow-up" aria-hidden="true"></i>&emsp;Updates
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('page'); ?>">
            <i class="fa fa-file" aria-hidden="true"></i>&emsp;Pages
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('article'); ?>">
            <i class="fa fa-file-text" aria-hidden="true"></i>&emsp;Articles
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('category'); ?>">
            <i class="fa fa-list-ul" aria-hidden="true"></i>&emsp;Catégories
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('comment'); ?>"> <!-- 404 -->
            <i class="fa fa-pencil" aria-hidden="true"></i>&emsp;Comments
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('media'); ?>"> <!-- 404 -->
            <i class="fa fa-video-camera" aria-hidden="true"></i>&emsp;Médias
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('page'); ?>">
            <i class="fa fa-magic" aria-hidden="true"></i>&emsp;Styles
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('user'); ?>">
            <i class="fa fa-user" aria-hidden="true"></i>&emsp;Users
        </a>
    </nav>

    <nav class="slide-nav slide-nav-small is-visible">
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute(''); ?>">
            <i class="fa fa-pie-chart" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('update'); ?>">
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('page'); ?>">
            <i class="fa fa-file" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('article'); ?>">
            <i class="fa fa-file-text" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('category'); ?>">
            <i class="fa fa-list-ul" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('comment'); ?>">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('media'); ?>">
            <i class="fa fa-video-camera" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('page'); ?>">
            <i class="fa fa-magic" aria-hidden="true"></i>
        </a>
        <a class="nav-link" href="<?php echo Helpers::getAdminRoute('user'); ?>">
            <i class="fa fa-user" aria-hidden="true"></i>
        </a>
    </nav>

<!--
    <div class="settings-nav">
        <div class="settings-title">
            Settings
        </div>

        <a class="nav-link"><i class="fa fa-cog" aria-hidden="true"></i>&emsp;Mon compte</a>
        <a class="nav-link"><i class="fa fa-eye" aria-hidden="true"></i>&emsp;Accès front</a>

        <a href="<?php echo Helpers::getAdminRoute('login/logout'); ?>" class="logout">Logout</a>
    </div>
-->

    <header class="header">
        <div id="nav-icon" class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="title-header">Qwarkz</div>

        <div id="nav-icon-config" class="nav-settings-toogle">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </header>
</div>

<div class="container">
    <?php if (count(Session::getErrors()) > 0): ?>
        <div class="flash-messages errors">
            <ul>
                <?php foreach (Session::getErrors() as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php Session::resetErrors(); ?>
    <?php endif; ?>

    <?php if (count(Session::getSuccess()) > 0): ?>
        <div class="flash-messages success">
            <ul>
                <?php foreach (Session::getSuccess() as $success): ?>
                    <li><?php echo $success; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php Session::resetSuccess(); ?>
    <?php endif; ?>

    <?php include $this->view; ?>
</div>

<script type="text/javascript" src="<?php echo Helpers::getAsset('js/admin.js'); ?>"></script>
</body>
</html>