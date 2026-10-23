<?php
/* @var $this \yii\web\View */
/* @var $content string */
?>

<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>

            <a href="/">
                <img src="/static/images/logo_light.svg" width="180" alt="logo" class="logo-default"/> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->
        <div class="page-actions">
        </div>
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <form class="search-form" action="<?= \yii\helpers\Url::to(['/tracker/jobs/index']) ?>" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="Search..." name="JobsSearch[query]">
                    <span class="input-group-btn">
                                <a href="javascript:;" class="btn submit">
                                    <i class="icon-magnifier"></i>
                                </a>
                            </span>
                </div>
            </form>
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide"></li>
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <span
                                    class="username username-hide-on-mobile"> <?= Yii::$app->user->identity->first_name ?> <?= Yii::$app->user->identity->last_name ?></span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            <img alt="" class="img-circle" src="<?= Yii::$app->user->identity->getAvatar(100) ?>"/> </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?= \yii\helpers\Url::to(['/core/profile/edit']) ?>">
                                    <i class="icon-user"></i> Profile </a>
                            </li>
                            <li>
                                <a href="<?= \yii\helpers\Url::to(['/tracker/description-templates/index']) ?>">
                                    <i class="icon-paper-plane"></i> Description Templates </a>
                            </li>
                            <li>
                                <a href="<?= \yii\helpers\Url::to(['/core/auth/logout']) ?>">
                                    <i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
    <div class="actions-bar">
        <a class="btn sbold uppercase btn-outline white"
           href="<?= \yii\helpers\Url::to(['/tracker/tasks/add']) ?>">
            Enter Daily Hours
        </a>
        <a class="btn sbold uppercase btn-outline white"
           href="<?= \yii\helpers\Url::to(['/tracker/tasks/add-weekly']) ?>">
            Enter Weekly Hours
        </a>
        <a class="btn sbold uppercase btn-outline white"
           href="<?= \yii\helpers\Url::to(['/accounting/user-reports/my']) ?>">
            Calendar view
        </a>
    </div>
</div>