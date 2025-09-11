<?php
/**
* CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
* @link          https://cakephp.org CakePHP(tm) Project
* @since         0.10.0
* @license       https://opensource.org/licenses/mit-license.php MIT License
* @var \App\View\AppView $this
*/
$paramController = $this->request->getParam('controller');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?></title>
    <meta name="description" content="<?= $this->fetch('meta_description') ?>" />
    <meta name="keywords" content="<?= $this->fetch('meta_keywords') ?>" />
    <meta name="robots" content="<?= $this->fetch('meta_robot') ?>" />
    <?php echo $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
    <link href="<?= $this->Url->build('/img/favicon.png');?>" type="image/x-icon" rel="icon" />
    <?= $this->Html->css('/admin/css/bootstrap');?>
    <?= $this->Html->css('/admin/css/font-awesome');?>
    <?= $this->Html->css('/admin/css/style');?>
    <?= $this->Html->css('/admin/css/main-style');?>
    <?= $this->Html->css('/admin/css/custom');?>
    <?= $this->Html->script('/admin/js/jquery-2.1.1.min');?>
    <?= $this->Html->script('/admin/js/validation');?>
    <?= $this->Html->script('/admin/js/jquery.maskedinput');?>
    <?= $this->Html->script('site.local');?>
</head>
<body class="body-Login-back">
    <?= $this->fetch('content') ?>
    <?= $this->Html->script('/admin/js/bootstrap.min');?>
    <?= $this->Html->script('/admin/js/jquery.metisMenu');?>
</body>
</html>
