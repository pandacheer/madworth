<?php
/**
 * @文件： v_domainDialog
 * @时间： 2013-4-1 14:34:49
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：国家修改视图
 */
?>
<style type="text/css">
    #domainTable tr {
        height: 40px;
    }
</style>

<form method="post" id="domainForm" name="domainForm">
    <table width="500" border="0" id="domainTable" style=" font-size: 12px">
        <tr>
            <td align="right">编号：</td>
            <td><input name="id" type="text" id="id" size="10" maxlength="10" class="easyui-validatebox" readonly="readonly"/><span style="color:red">  *自动生成</span></td>
        </tr>
        <tr>
            <td align="right">域名：</td>
            <td><input name="domain" type="text" id="domain" size="36" maxlength="36" class="easyui-validatebox"/></td>
        </tr>
        <tr>
            <td align="right">国家代码：</td>
            <td><input name="country" type="text" id="country" size="5" maxlength="5" class="easyui-validatebox" /></td>
        </tr>
    </table>
</form>
