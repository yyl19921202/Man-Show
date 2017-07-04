<?php
use yii\helpers\Html;
?>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！</li>
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
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <?php foreach ($cates as $cat):?>
        <tbody>
        <tr data-goods_id="<?=$cat['id']?>">
                <td class="col1"><a href=""><?=Html::img('http://admin.yii2shop.com'.$cat['logo'])?></a><center><strong><a href=""><?=$cat['name']?></a></strong></center></td>
                <td class="col3">￥<span><?=$cat['shop_price']?></span></td>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?=$cat['amount']?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>
                <td class="col5">￥<span><?=$cat['shop_price']*$cat['amount']?></span></td>
                <td class="col6"><a href="" class="del">删除</a></td>
            </tr>
            </tbody>
        <?php endforeach;?>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"><?=$sum?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <a href="<?=\yii\helpers\Url::to(['user/flow2'])?>" class="checkout">结 算</a>
    </div>
</div>
<!-- 主体部分 end -->
<div style="clear:both;"></div>
<?php
/**
 * @var $this \yii\web\View;
 */
$url = \yii\helpers\Url::to(['user/update-cart']);
$token=Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
    // 点击监听事件
    $(".reduce_num,.add_num").click(function() {
       // console.log($(this));
       var goods_id=$(this).closest('tr').attr('data-goods_id');
       var amount = $(this).parent().find('.amount').val();
       //通过ajax请求，发送post数据修改页面
       // console.debug(goods_id,amount);
       $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"},function(data) {
           // console.debug(data);
       });
    });
       
        //删除
        //找到节点，点击事件
    $(".del").click(function() {
      if(confirm('是否删除该商品')){
            var goods_id=$(this).closest('tr').attr('data-goods_id');
            $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
            //删除该商品
            $(this).closest('tr').remove();
      }
    })
JS

));
?>
