<?php
/**
 * @文件： v_rbacUserSelf.php
 * @时间： 2015-2-3 15:03:15
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：显示帐号信息视图
 */
?>
<style type="text/css">
    #rbacUserSelfTable tr {
        height: 40px;
    }
</style>
<script type="text/javascript">
    $(function() {
        $('#rbacUserSelfFormShow').form('load', "<?php echo site_url('rbacUser/getSelf') ?>");
        $('#btn_edit').bind('click', function() {
            $('<div id="rbacUserSelfDialog"><div/>').dialog({
                title: '修改我的信息',
                width: 520,
                height: 300,
                closable: true,
                closed: false,
                cache: false,
                modal: true,
                href: '<?php echo site_url('rbacUser/rbacUserSelfDialog') ?>',
                buttons: [{text: '保存', iconCls: 'icon-save',
                        handler: function() {
                            $("#rbacUserSelfFormUpdate").removeAttr("action").attr("action", "<?php echo site_url('rbacUser/updateSelf') ?>");
                            $('#rbacUserSelfFormUpdate').form('submit', {
                                onSubmit: function() {
                                    return $(this).form('validate');
                                },
                                success: function(result) {
                                    var result = $.parseJSON(result);
                                    if (result.success) {
                                        $('#rbacUserSelfFormShow').form('load', "<?php echo site_url('rbacUser/getSelfBase') ?>");
                                        $.messager.alert('成功', '注册信息修改成功！！！', 'Info');
                                        $('#rbacUserSelfDialog').dialog('close');
                                    } else {
                                        $.messager.alert('失败', result.msg, 'error');
                                    }
                                }
                            });
                        }
                    }],
                onLoad: function() {
                    $('#rbacUserSelfFormUpdate').form('load', "<?php echo site_url('rbacUser/getSelfBase') ?>");
                },
                onClose: function() {
                    $(this).dialog('destroy');
                }
            });
        });
    });
</script>

<form id="rbacUserSelfFormShow">
    <table width="440" border="0" id="rbacUserSelfTable" style=" font-size: 12px">
        <tr>
            <td align="right" width="100">编号：</td>
            <td width="340"><input name="user_id" type="text" id="netbar_id" size="11" maxlength="11" class="easyui-numberbox" style="height:30px;padding:8px" readonly="readonly" /></td>
        </tr>           
        <tr>
            <td align="right">帐号：</td>
            <td><input name="user_account" type="text" id="user_account" class="easyui-textbox"  style="height:30px;padding:8px"  size="20" maxlength="20" readonly="readonly"  /></td>
        </tr>
        <tr>
            <td align="right">姓名：</td>
            <td><input type="text" name="user_name" id="user_name" size="13" maxlength="13" class="easyui-textbox" style="height:30px;padding:8px"  readonly="readonly" /></td>
        </tr>
        <tr>
            <td align="right">邮箱：</td>
            <td><input type="text" name="user_email" id="user_email" size="25" maxlength="25" class="easyui-textbox" style="height:30px;padding:8px"  readonly="readonly" /></td>
        </tr>
        <tr>
            <td align="right">注册时间：</td>
            <td><input type="text" name="create_time" id="create_time" size="15" maxlength="15" class="easyui-textbox" style="height:30px;padding:8px"  readonly="readonly" /></td>
        </tr>
        <tr>
            <td align="right">登录时间：</td>
            <td><input type="text" name="login_time" id="login_time" size="15" maxlength="15" class="easyui-textbox" style="height:30px;padding:8px"  readonly="readonly" /></td>
        </tr>
        <tr>
            <td align="right">角色：</td>
            <td><input type="text" name="user_role" id="user_role" class="easyui-textbox" data-options="multiline:true" style="width:300px;height:100px"  readonly="readonly"/></td>
        </tr>

        <tr>
            <td align="right"></td>
            <td><a href="javascript:void(0);" id="btn_edit" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">编辑</a></td>
        </tr>
    </table>
</form> 