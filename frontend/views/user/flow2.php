
<div style="clear:both;"></div>

<!-- 主体部分 start -->

<div class="fillin w990 bc mt15">
    <form action="<?=\yii\helpers\Url::to(['user/flow3'])?>" method="post">
<!--        解决跨域问题，必须添加-->
    <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addres as $addr):?>
                <p><input type="radio" value="<?=$addr->id?>" name="address_id"/><?=$addr->receiver?> <?=$addr->tel?> <?=$addr->site?> <?=$addr->site2?> <?=$addr->site3?> <?=$addr->address?></p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->
        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>

            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$_method as $k=>$mod):?>
                    <tr class="<?=$k==0 ?'cur':''?>">
                        <td>
                            <input type="radio" name="delivery" checked="checked" value="<?=$k?>"><?=$mod['delivery_name']?>
                        </td>
                        <td>￥<?=$mod['delivery_price']?></td>
                        <td><?=$mod['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$_pay as $s=>$pa):?>
                    <tr class="<?=$s==0?'cur':''?>">
                        <td class="col1"><input type="radio" name="pay" value="<?=$s?>"/><?=$pa['payment_name']?></td>
                        <td class="col2"><?=$pa['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
<!--                <form action="">-->
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
<!--                </form>-->

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cates as $cat):?>
                <tr>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.$cat['logo'])?></a>  <center><strong><a href=""><?=$cat['name']?></a></strong></center></td>
                    <td class="col3">￥<?=$cat['shop_price']?></td>
                    <td class="col4"> <?=$cat['amount']?></td>
                    <td class="col5"><span>￥<?=$cat['shop_price']?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<?=$sum?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <!-- 商品清单 end -->
    </div>

    <div class="fillin_ft">
<!--        <a href=""><span>提交订单</span></a>-->
        <input type="submit" value="提交订单">
        <p>应付总额：<strong>￥<?=$sum?>元</strong></p>
    </div>
</form>
</div>
<!-- 主体部分 end -->