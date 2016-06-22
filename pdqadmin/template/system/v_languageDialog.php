<?php
/**
 * @文件： v_languageDialog
 * @时间： 2013-4-1 14:34:49
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：国家修改视图
 */
?>
<style type="text/css">
    #languageTable tr {
        height: 40px;
    }
</style>

<form method="post" id="languageForm" name="languageForm">
    <table width="500" border="0" id="languageTable" style=" font-size: 12px">
        <tr>
            <td align="right">语种代码：</td>
            <td><input name="code" type="text" id="code" size="10" maxlength="10" class="easyui-validatebox" readonly="readonly"/><span style="color:red">  *保存后不可修改</span></td>
        </tr>
        <tr>
            <td align="right">语种说明：</td>
            <td><input name="about" type="text" id="about" size="40" maxlength="40" class="easyui-validatebox" /></td>
        </tr>
    </table>
</form>
