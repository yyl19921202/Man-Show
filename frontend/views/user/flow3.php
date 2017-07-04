<!-- 顶部导航 start -->
<?php
use yii\helpers\Html;
?>
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！ </li>
                <?php
                if(Yii::$app->user->isGuest){
                    echo \yii\helpers\Html::a('登陆',['user/login']);
                    echo '       ';
                    echo \yii\helpers\Html::a('免费注册',['user/register']);
                }else{
                    echo Yii::$app->user->identity->username;
                    echo '      ';
                    echo \yii\helpers\Html::a('注销',['user/logout']);
                }
                ?>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=Html::img(Yii::getAlias('@web/images/logo.png'))?></a></h2>
        <div class="flow fr flow3">
            <ul>
                <li>1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li class="cur">3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="success w990 bc mt15">
    <div class="success_hd">
        <h2>订单提交成功</h2>
    </div>
    <div class="success_bd">
        <p><span></span>订单提交成功，我们将及时为您处理</p>

        <p class="message">完成支付后，你可以 <a href="">查看订单状态</a>  <a href="">继续购物</a> <a href="">问题反馈</a></p>
    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>