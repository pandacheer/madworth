<?php
/**
 * @文件： role_dialog_view
 * @时间： 2013-3-28 17:06:57
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：role_dialog_view视图
 */
?>
<style type="text/css">
    #roleTable tr {
        height: 40px;
    }
</style>
<form method="post" id="roleForm" name="role-form">
    <input name="role_access_text" id="role_access_text" type="hidden" />
    <input name="role_access_id" id="role_access_id" type="hidden" />
    <table width="490" border="0" id="roleTable" style=" font-size: 12px">
        <tr>
            <th width="110" align="right">编号：</th>
            <td width="170"><input name="role_id" readonly="readonly" class="easyui-textbox" style="width: 100px;height:30px;padding:8px"  /><span style="color:red"> *自动生成</span></td>
            <th width="80" align="right">角色名称：</th>
            <td width="130"><input name="role_name" type="type" id="role_name" class="easyui-textbox" style="height:30px;padding:8px"   data-options="required:true,validType:'chinese'"  size="18" maxlength="18" /></td>
        </tr>
        <tr>
            <th align="right">简单说明：</th>
            <td colspan="3"><input name="role_remark" type="text" id="role_remark" class="easyui-textbox" style="height:30px;width: 400px;padding:8px"   data-options="required:true,validType:'chinese'"  size="15" maxlength="15"  /></td>
        </tr> 
        <tr>
            <th align="right">权限：</th>
            <td colspan="3"><input name="role_access_tree" type="text" id="role_access_tree" style="width: 400px;height:30px;padding:8px"   data-options="required:true"  size="15" maxlength="15" /></td>
        </tr>                  
    </table>
</form>
