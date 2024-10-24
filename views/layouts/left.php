<?php

use yii\bootstrap\Nav;
use app\models\Menu;

?>
<!--overflow-y: auto;overflow-x: hidden;-->
<aside class="main-sidebar" style="height: 100%;overflow-y: auto;overflow-x: hidden;">
    <section class="sidebar">
        <?php
        $menu = new Menu();
        echo dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'items' => $menu->getMenu()
        ]);
        ?>
    </section>
</aside>
