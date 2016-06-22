<?php
/**
 * @文件： v_rbacUserRoleDialog
 * @时间： 2013-3-28 17:06:57
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：user_dialog_view视图
 */
?>
<style type="text/css">
    #userTable tr {
        height: 40px;
    }
</style>
<form method="post" id="userForm" name="user-form">
    <input name="user_id" type="hidden" id="user_id" />
    <input name="user_role_text" id="user_role_text" type="hidden" />
    <input name="user_role_id" id="user_role_id" type="hidden" />
    <table width="460" border="0" id="userTable" style=" font-size: 12px">
        <tr>
            <td align="right" width="60">帐号：</td>
            <td><input name="user_account" type="text" id="user_account" size="16" maxlength="16" readonly="readonly"  class="easyui-textbox" style="height:30px;padding:8px" data-options="validType:'username'"  /><span style="color:red"> *不能修改</span> </td>
        </tr>                    
         <tr>
            <td align="right">姓名：</td>
            <td><input name="user_name" type="text" id="user_name" size="30" maxlength="40" readonly="readonly" class="easyui-textbox" style="height:30px;padding:8px" /><span style="color:red"> *不能修改</span></td>
        </tr>                    
        <tr>
            <td align="right">角色：</td>
            <td><input name="user_role_tree" type="text" id="user_role_tree" size="15" maxlength="15"  style="width: 330px;height:30px;padding:8px" data-options="required:true" /><span style="color:red"> *必填</span></td>
        </tr>         

    </table>
</form>
