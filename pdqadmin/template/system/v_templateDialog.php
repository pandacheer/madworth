<?php
/**
 * @文件： v_templateDialog
 * @时间： 2013-4-1 14:34:49
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：国家修改视图
 */
?>
<style type="text/css">
    #templateTable tr {
        height: 40px;
    }
</style>

<form method="post" id="templateForm" name="templateForm">
    <input name="country_code" id="country_code" type="hidden" >
    <input name="terminal_code" id="terminal_code" type="hidden" >
    <table width="450" border="0" id="templateTable" style=" font-size: 12px">
        <tr>
            <td colspan="2" align="center"><label id="select"></label></td>
        </tr>
        <tr>
            <td align="right">编号：</td>
            <td><input name="id" type="text" id="id" size="15" maxlength="15" class="easyui-validatebox" readonly="readonly" /><span style="color:red">  *自动生成</span></td>
        </tr>
        <tr>
            <td align="right">模板键值：</td>
            <td><input name="key" type="text" id="key" size="30" maxlength="30" class="easyui-validatebox" /></td>
        </tr>        
        <tr>
            <td align="right">原视图文件名：</td>
            <td><input name="public" type="text" id="public" size="30" maxlength="30" class="easyui-validatebox" /></td>
        </tr>
        <tr>
            <td align="right">原视图说明：</td>
            <td><input name="pub_about" type="text" id="pub_about" size="20" maxlength="20" class="easyui-validatebox" /></td>
        </tr>
        <tr>
            <td align="right">新视图文件名：</td>
            <td><input name="private" type="text" id="private" size="30" maxlength="30" class="easyui-validatebox"/></td>
        </tr>
        <tr>
            <td align="right">说明：</td>
            <td><input name="pri_about" type="text" id="pri_about" size="40" maxlength="40" class="easyui-validatebox"/></td>
        </tr>
    </table>
</form>
