<div style="clear:both;"></div>
<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

<!-- 右侧内容区域 start -->
<div class="content fl ml10">
    <div class="address_hd">
        <h3>收货地址薄</h3>
        <?php foreach ($models as $data):?>
        <dl>
            <dt><?=$data->id?>、<?=$data->receiver?>  <?=$data->site?><?=$data->site2?><?=$data->site3?><?=$data->address?> <?=$data->tel?></dt>
            <dd>
                <?=\yii\helpers\Html::a('修改',['user/edit','id'=>$data->id])?>
                <?=\yii\helpers\Html::a('删除',['user/del','id'=>$data->id])?>
                <a href="">设为默认地址</a>
            </dd>
        </dl>
        <?php endforeach;?>
    </div>
    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
<?php

//注册表单  不需要使用bootstrap样式，所以使用\yii\widgets\ActiveForm
$form = \yii\widgets\ActiveForm::begin(
    ['fieldConfig'=>[
        'options'=>[
            'tag'=>'li',
        ],
        'errorOptions'=>[
            'tag'=>'p'
        ]
    ]]
);
echo '<ul>';
echo $form->field($model,'receiver')->textInput(['class'=>'txt']);//用户名
//验证码
echo $form->field($model,'address')->textInput(['class'=>'txt']);//用户名
echo '所在地区：';
echo $form->field($model,'site',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省='],['id'=>'address-site']);
echo $form->field($model,'site2',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市='],['id'=>'address-site2']);
echo $form->field($model,'site3',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县='],['id'=>'address-site3']);

echo $form->field($model,'tel')->textInput(['class'=>'txt']);
echo $form->field($model,'status')->checkbox(['1'=>'默认']);
echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="保存" />
                    </li>';
echo '</ul>';
\yii\widgets\ActiveForm::end();
?>
        <?php
/**
 * @var $this \yii\web\View;
 */
//引入js文件
$this->registerJsFile('@web/js/address.js');
$this->registerCssFile('@web/css/address.css');
$this->registerJs(new \yii\web\JsExpression(
        //写js文件
        <<<JS
        //填充省得数据
        $(address).each(function(){
          var option='<option value="'+this.name+'">'+this.name+'</option>';//拼写遍历之后省得数据
          $("#address-site").append(option);
        });
        //找到省级地区，并且获取里面的数据
        $("#address-site").change(function(){
        var site = $(this).val();//获取当前选中的省
        //获取省里面的数据
         $(address).each(function(){
           if(this.name==site){//判断遍历之后的省份信息跟点击获取的是不是一样？
           var option = '<option value="">=请选择市=</option>';
               $(this.city).each(function(){//如果相等，遍历市的数据
                  option+='<option value="'+this.name+'">'+this.name+'</option>';//拼写遍历之后省得数据
             })
             $("#address-site2").html(option);//拼凑之后的结果追加到下拉列表中
           }
         });
         $("#address-site3").html('<option value="">=请选择县=</option>')
        });
        //通过点击事件
        $("#address-site2").change(function(){
         var city=$(this).val();//获取市的数据
            $(address).each(function(){//遍历数据
                if(this.name==$("#address-site").val()){//判断是否相等
                    $(this.city).each(function(){//相等的话，遍历第二级
                        if(this.name==city){//判断第二级是否相等
                            var option = '<option value="">=请选择县=</option>';
                            $(this.area).each(function(i,v){
                            option += '<option value="'+v+'">'+v+'</option>';  
                            });
                            $("#address-site3").html(option);
                        }
                    })
                }
            })
        })
JS


));
$js = '';
if($model->site){
    $js .= '$("#address-site").val("'.$model->site.'");';
}
if($model->site2){
    $js .= '$("#address-site").change();$("#address-site2").val("'.$model->site2.'");';
}
if($model->site3){
    $js .= '$("#address-site2").change();$("#address-site3").val("'.$model->site3.'");';
}
$this->registerJs($js);
