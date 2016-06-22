<?php
/**
 * @文件： v_netbarInfoDialog.php
 * @时间： 2014-12-30 10:03:15
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：网吧信息编辑视图
 */
?>
<style type="text/css">
    #rbacUserSelfTable tr {
        height: 40px;
    }
</style>
<script type="text/javascript">

    $.extend($.fn.validatebox.defaults.rules, {
        chinese: {// 验证中文
            validator: function(value) {
                return /^[\u0391-\uFFE5]+$/i.test(value);
            },
            message: '请输入中文'
        }
    });
    $(function() {
    });
</script>
<form id="rbacUserSelfFormUpdate" method="post">
    <table width="500" border="0" id="rbacUserSelfTable" style=" font-size: 12px">
        <tr>
            <td width="90" align="right">编号：</td>
            <td width="410"><input name="user_id" type="text" id="user_id" size="11" maxlength="11" class="easyui-numberbox" style="height:30px;padding:8px"  readonly="readonly" /><span style="color:red"> *不能修改</span></td>
        </tr>           
        <tr>
            <td align="right">帐号：</td>
            <td><input name="user_account" type="text" id="user_account" size="20" maxlength="20" class="easyui-textbox" data-options="required:true" style="height:30px;padding:8px"  readonly="readonly"  /><span style="color:red"> *不能修改</span></td>
        </tr>
        <tr>
            <td align="right">姓名：</td>
            <td><input name="user_name" type="text" id="user_name" size="30" maxlength="40" class="easyui-textbox" style="height:30px;padding:8px"  data-options="required:true,validType:'chinese'"  /></td>
        </tr>                    
        <tr>
            <td align="right">邮箱：</td>
            <td><input name="user_email" type="text" id="user_email" size="30" maxlength="30" class="easyui-textbox" style="height:30px;padding:8px"  data-options="prompt:'请输入邮箱',required:true,validType:'email'"  /><span style="color:red"> *用于找回密码</span></td>
        </tr>
    </table>
</form>