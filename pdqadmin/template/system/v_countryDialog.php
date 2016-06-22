<?php
/**
 * @文件： v_countryDialog
 * @时间： 2013-4-1 14:34:49
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：国家修改视图
 */
?>
<style type="text/css">
    #countryTable tr {
        height: 40px;
    }
</style>

<form method="post" id="countryForm" name="countryForm">
    <table width="500" border="0" id="countryTable" style=" font-size: 12px">
        <tr>
            <td align="right">编号：</td>
            <td><input name="country_id" type="text" id="country_id" size="10" maxlength="10" class="easyui-validatebox" readonly="readonly"   /><span style="color:red">  *自动生成</span></td>
        </tr>
        <tr>
            <td align="right">国家代码：</td>
            <td><input name="iso_code_2" type="text" id="iso_code_2" size="10" maxlength="10" class="easyui-validatebox" readonly="readonly"   /><span style="color:red">  *禁止修改</span></td>
        </tr>        
        <tr>
            <td align="right">国家名称：</td>
            <td><input name="name" type="text" id="name" size="40" maxlength="40" class="easyui-validatebox" readonly="readonly" /><span style="color:red">  *禁止修改</span></td>
        </tr>
        <tr>
            <td align="right">域名：</td>
            <td><input name="domain" type="text" id="domain" size="40" maxlength="40" class="easyui-validatebox"/></td>
        </tr>
        <tr>
            <td align="right">语种：</td>
            <td><input name="language_code" type="text" id="language_code" size="10" maxlength="2" class="easyui-combobox" data-options="valueField:'code',textField:'about',editable:false" style="width:300px;" url="<?php echo site_url('language/combobox') ?>" /></td>
        </tr>
        <tr>
            <td align="right">货币符号：</td>
            <td><input name="currency_symbol" type="text" id="currency_symbol" size="4" maxlength="1" class="easyui-validatebox"/>  货币类型：<input name="currency_payment" type="text" id="currency_payment" size="4" maxlength="3" class="easyui-validatebox"/></td>
        </tr>  

        <tr>
            <td align="right">澳币对换：</td>
            <td><input name="au_rate" type="text" id="au_rate" size="30" maxlength="30" class="easyui-validatebox"/></td>
        </tr> 
        <tr>
            <td align="right">时区：</td>
            <td><input name="timezone" type="text" id="timezone" size="30" maxlength="30" class="easyui-validatebox"/></td>
        </tr>
        <tr>
            <td align="right">国旗排序：</td>
            <td><input name="flag_sort" type="text" id="flag_sort" size="40" maxlength="40" class="easyui-validatebox"/><span style="color:red">  *逗号分隔</span></td>
        </tr>
        <tr>
            <td align="right">Google码：</td>
            <td><input name="google" type="text" id="google" size="14" maxlength="13" class="easyui-validatebox"/><span style="color:red">  *谷歌统计码</span></td>
        </tr>
        <tr>
            <td align="right">FaceBook ID：</td>
            <td><input name="facebook_id" type="text" id="facebook_id" size="30" maxlength="150" class="easyui-validatebox"/><span style="color:red">  *谷歌统计码</span></td>
        </tr>
        <tr>
            <td align="right">FaceBook推广：</td>
            <td><input name="facebook" type="text" id="facebook" size="30" maxlength="150" class="easyui-validatebox"/><span style="color:red">  *谷歌统计码</span></td>
        </tr>
        <tr>
            <td align="right">服务邮箱：</td>
            <td><input name="service_mail" type="text" id="service_mail" size="30" maxlength="40" class="easyui-validatebox"/></td>
        </tr>

    </table>
</form>
