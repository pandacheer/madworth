<?php
/**
 * @文件： v_rbacNodeDialog.php
 * @时间： 2015-1-28 17:06:57
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：视图
 */
?>
<style type="text/css">
    #nodeTable tr {
        height: 40px;
    }
</style>
<form method="post" id="nodeForm" name="nodeForm">
    <input name="node_ptitle" type="hidden" id="node_ptitle" />
    <table width="490" border="0" id="nodeTable" style=" font-size: 12px">
        <tr>
            <th width="100" align="right">编号：</th>
            <td width="130"><input name="node_id" class="easyui-numberbox"  readonly="readonly" style="height:30px;padding:8px" /><span style="color:red"> * 自动生成</span>
        </tr>
        <tr>
            <th align="right">名称：</th>
            <td><input name="node_title" type="type" id="node_title" class="easyui-textbox"  data-options="required:true"  size="16" maxlength="16" style="height:30px;padding:8px"  /></td>
        </tr>   
        <tr>
            <th align="right">排序：</th>
            <td><input name="node_sort" type="text" id="node_name" class="easyui-numberbox"  data-options="required:true" size="4" maxlength="4" value="20" style="height:30px;padding:8px"/></td>
        </tr>   
        <tr>
            <th align="right">路径：</th>
            <td><input name="node_url" type="type" id="node_url" class="easyui-textbox" data-options="required:false"   size="40" maxlength="40" style="height:30px;padding:8px"  /></td>
        </tr>                   
        <tr>
            <th align="right">父节点：</th>
            <td><input name="node_pid" type="text" id="node_pid" class="easyui-combotree" data-options="required:true"   size="15" maxlength="15"  style="width: 370px;height:30px;padding:8px"  /></td>
        </tr>
        <tr>
            <th align="right">是否为菜单：</th>
            <td><input name="node_menu" type="radio" value="1" />是 <input name="node_menu" type="radio" value="0"  />否</td>
        </tr>        
    </table>
</form>
