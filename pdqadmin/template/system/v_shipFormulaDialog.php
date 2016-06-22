<?php
/**
 * @文件： v_shipFormulaDialog
 * @时间： 2013-4-1 14:34:49
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：国家修改视图
 */
?>
<style type="text/css">
    #shipFormulaTable tr {
        height: 40px;
    }
</style>

<form method="post" id="shipFormulaForm" name="shipFormulaForm">
    <table width="500" border="0" id="shipFormulaTable" style=" font-size: 12px">
        <tr>
            <td align="right">编号：</td>
            <td><input name="id" type="text" id="id" size="10" maxlength="10" class="easyui-validatebox" readonly="readonly"   /><span style="color:red">  *自动生成</span></td>
        </tr>
        <tr>
            <td align="right">国家代码：</td>
            <td><input name="country_code" type="text" id="country_code" size="10" maxlength="2" class="easyui-validatebox" readonly="readonly"   /></td>
        </tr>
        <tr>
            <td align="right">货物重量（g）：</td>
            <td><input name="weight" type="text" id="weight" size="10" maxlength="8" class="easyui-validatebox"/></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td style=" color: red;">注示：小于以上重量的包裹使用以下的运费公式计算运费；<br>　　　（运费公式中字母为小写）</td>
        </tr>        
        <tr>
            <td align="right">运费公式：</td>
            <td><input name="formula" type="text" id="formula" maxlength="60" class="easyui-validatebox"  style="width:300px;"  /></td>
        </tr>
<!--        <tr>
            <td align="right">需要特殊处理：</td>
            <td><input name="special" type="checkbox" id="special" size="4" maxlength="1" value="2"/><span style="color:red">  x-1000如果是负数，则归零，其余情况小数点进一位</span></td>
        </tr>-->
    </table>
</form>
