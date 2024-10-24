<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\AppUser;
use yii\helpers\Url;
use app\models\AppMenu;
use app\models\IndonesiaDate;
use app\models\AppSemester;

$pengguna = AppUser::findOne(Yii::$app->user->identity->userId);
$semester = AppSemester::find()->select(['smtNama'=>'CONCAT(smtNama," ",smtTahun)'])->where('smtIsAktif="1"')->one();
$inDate = new IndonesiaDate();

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?php // echo Html::a('<span class="logo-mini">' . Html::img(AppAsset::register($this)->baseUrl . '/images/logo-unand.png', ['style' => 'width:25px;margin-top:0px;border:2px solid;border-radius:3px;background-color:white;']) . '</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo'])  ?>
    <a href="<?php echo Yii::$app->homeUrl; ?>" class="logo">
        <span class="logo-mini art-object567354297-bo-mini"></span>
        <span class="logo-lg" style="text-align: left;">    
            <div class="art-object567354297-bo" data-left="0%"></div><h1 class="art-headline-bo" data-left="10%"><?php echo Yii::$app->versionApp->companyName(); ?></h1><h2 class="art-slogan-bo" data-left="10.09%">data.unand.ac.id</h2>
        </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <?php
                if (!empty($member) && $menu > 0) {
                    ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span id="notifikasi-retensi-jml" class="label label-warning" style="font-size:12px;">0</span>
                        </a>
                        <ul id="notifikasi-retensi" class="dropdown-menu">
                            <!-- Notofikasi List Retensi -->
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <!-- Tasks: style can be found in dropdown.less -->
                <?php
                $a = 0;
                if ($a == 1) {
                    ?>
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Design some buttons
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    <span class="sr-only">20% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Create a nice theme
                                                <small class="pull-right">40%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-green" style="width: 40%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    <span class="sr-only">40% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Some task I need to do
                                                <small class="pull-right">60%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-red" style="width: 60%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Make beautiful transitions
                                                <small class="pull-right">80%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    <span class="sr-only">80% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all tasks</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-calendar-check-o"></i> 
                        <?php echo $semester->smtNama; ?>
                    </a>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        if (empty($member)) {
                            ?>
                            <img src="<?php echo AppAsset::register($this)->baseUrl . '/images/nobody.png' ?>" class="user-image" alt="User Image"/>
                            <?php
                        } else {
                            if (empty($member->memberFoto)) {
                                ?>
                                <img src="<?php echo AppAsset::register($this)->baseUrl . '/images/nobody.png' ?>" class="user-image" alt="User Image"/>
                                <?php
                            } else {
                                ?>
                                <img src="<?php echo AppAsset::register($this)->baseUrl . '/photos/' . $member->memberFoto; ?>" class="user-image" alt="User Image"/>
                                <?php
                            }
                        }
                        ?>
                        <span class="hidden-xs">
                            Akun Saya
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php
                            if (empty($member)) {
                                ?>
                                <img src="<?php echo AppAsset::register($this)->baseUrl . '/images/nobody.png' ?>" class="img-circle" alt="User Image"/>
                                <?php
                            } else {
                                if (empty($member->memberFoto)) {
                                    ?>
                                    <img src="<?php echo AppAsset::register($this)->baseUrl . '/images/nobody.png' ?>" class="img-circle" alt="User Image"/>
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo AppAsset::register($this)->baseUrl . '/photos/' . $member->memberFoto; ?>" class="img-circle" alt="User Image"/>
                                    <?php
                                }
                            }
                            ?>
                            <p>
                                <?php
                                if (empty($member)) {
                                    $profilNama = $pengguna->nama;
                                } else {
                                    $profilNama = $member->memberNama;
                                }
                                if (strlen($profilNama) > 20) {
                                    $profil = str_split($profilNama, 20);
                                    echo $profil[0] . '...';
                                } else {
                                    echo $profilNama;
                                }
                                ?>
                                <small><?php echo 'Member sejak ' . Yii::$app->formatter->asDate(Yii::$app->user->identity->userTglEntri, 'php:M,Y'); ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php
                                echo Html::a(
                                        ' Profil', ['/profil/view'], ['class' => 'fa fa-user btn btn-default btn-flat']
                                );
                                ?>
                            </div>
                            <div class="pull-right">
                                <?php
                                echo Html::a(
                                        ' Sign out', ['/site/logout'], ['data-method' => 'post', 'class' => 'fa fa-power-off btn btn-default btn-flat']
                                );
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <!-- Control Sidebar Toggle Button -->
                <!--                <li>
                                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-user"></i></a>
                                </li>-->
            </ul>
        </div>
    </nav>
</header>
<?php
if (!empty($member) && $menu > 0) {
//Load Notifikasi Retensi
    $urlNotifList = Url::to(['/arsip/retensi/notifikasi', 'act' => 'list']);
    $urlNotifJml = Url::to(['/arsip/retensi/notifikasi', 'act' => 'jumlah']);
    $js = <<< JS
    //Load Notifikasi Retensi
    $('#notifikasi-retensi').load('{$urlNotifList}');
    $('#notifikasi-retensi-jml').load('{$urlNotifJml}');
JS;
    $this->registerJs($js);
}
?>