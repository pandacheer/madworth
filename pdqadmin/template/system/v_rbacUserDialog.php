<?php
/**
 * @文件： user_dialog_view
 * @时间： 2013-3-28 17:06:57
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：user_dialog_view视图
 */
?>
<script type="text/javascript">
    $.extend($.fn.validatebox.defaults.rules, {
        username: {// 验证用户名
            validator: function(value) {
                return /^[a-zA-Z][a-zA-Z0-9_]{5,15}$/i.test(value);
            },
            message: '用户名不合法（字母开头，允许6-16个字符，允许字母数字下划线）'
        },
        password: {// 验证密码
            validator: function(value) {
                return /^[a-zA-Z0-9_]{6,16}$/i.test(value);
            },
            message: '密码不合法（允许6-16个字符，允许字母数字下划线）'
        },
        equals: {
            validator: function(value, param) {
                if ($("#" + param[0]).val() != "" && value != "") {
                    return $("#" + param[0]).val() == value;
                } else {
                    return true;
                }
            },
            message: '密码与密码确认不一致！！'
        },
        mobile: {// 验证手机号码
            validator: function(value) {
                return /^(13|14|15|17|18)\d{9}$/i.test(value);
            },
            message: '手机号码格式不正确(正确格式如：13408080808)'
        },
        chinese: {// 验证中文
            validator: function(value) {
                return /^[\u0391-\uFFE5]+$/i.test(value);
            },
            message: '请输入中文'
        }
    });
    $(function() {
        $('#time').val($('#regtime').val());
        $('#token').val($('#regtoken').val());
        $('#netbar_id').val($('#regnetbar_id').val());
    });
</script>
<style type="text/css">
    #userTable tr {
        height: 42px;
    }
</style>
<form method="post" id="userForm" name="user-form">
    <input name="user_id" type="hidden" id="user_id" />
    <input name="user_role_text" id="user_role_text" type="hidden" />
    <input name="user_role_id" id="user_role_id" type="hidden" />
    <table width="460" border="0" id="userTable" style=" font-size: 12px">
        <tr>
            <td align="right" width="80">帐号：</td>
            <td width="380"><input name="user_account" type="text" id="user_account" size="16" maxlength="16"  class="easyui-textbox" style="height:30px;padding:8px"  data-options="required:true,validType:'username'"   /><span style="color:red"> *必填，注册后不能修改</span> </td>
        </tr>                    
        <tr>
            <td align="right">密码：</td>
            <td><input name="user_password" type="password" id="user_password" size="16" maxlength="16"  class="easyui-textbox" style="height:30px;padding:8px" data-options="required:true,validType:'password'" /></td>
        </tr>                    
        <tr>
            <td align="right">密码确认：</td>
            <td><input name="user_password2" type="password" id="user_password2" size="16" maxlength="16"  class="easyui-textbox" style="height:30px;padding:8px" data-options="required:true"  validType="equals['user_password']" /></td>
        </tr> 
        <tr>
            <td align="right">姓名：</td>
            <td><input name="user_name" type="text" id="user_name" size="30" maxlength="40" class="easyui-textbox" style="height:30px;padding:8px"  data-options="required:true,validType:'chinese'"  /><span style="color:red"> *填后不能修改</span></td>
        </tr>                    
        <tr>
            <td align="right">邮箱：</td>
            <td><input name="user_email" type="text" id="user_email" size="30" maxlength="30" class="easyui-textbox" style="height:30px;padding:8px"  data-options="prompt:'请输入邮箱',required:true,validType:'email'"  /><span style="color:red"> *用于找回密码</span></td>
        </tr>                    
        <tr>
            <td align="right">角色：</td>
            <td><input name="user_role_tree" type="text" id="user_role_tree" size="15" maxlength="15"  style="height:30px;width: 330px;padding:8px" data-options="required:true" /></td>
        </tr>         

    </table>
</form>
