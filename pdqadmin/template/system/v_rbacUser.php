<script type="text/javascript">
    $(function() {
        $('#dg').datagrid({
            url: '<?php echo site_url('rbacUser/select') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true, pagination: true,
            toolbar: [{
                    text: '增加',
                    iconCls: 'icon-add',
                    handler: function() {
                        $('<div id="userDialog"><div/>').dialog({
                            title: '增加管理员',
                            width: 520,
                            height: 370,
                            closable: true,
                            closed: false,
                            cache: false,
                            modal: true,
                            href: '<?php echo site_url('rbacUser/dialog') ?>',
                            buttons: [{
                                    text: '保存',
                                    iconCls: 'icon-save',
                                    handler: function() {
                                        $('#user_role_text').val($('#user_role_tree').combobox('getText'));
                                        $('#user_role_id').val($('#user_role_tree').combobox('getValues'));
                                        $("#userForm").removeAttr("action").attr("action", "<?php echo site_url('rbacUser/insert') ?>");
                                        $('#userForm').form('submit', {
                                            onSubmit: function() {
                                                return $(this).form('validate');
                                            },
                                            success: function(result) {
                                                var result = $.parseJSON(result);
                                                if (result.success) {
                                                    $.messager.alert('成功', '用户【' + $('#user_name').textbox('getText') + '】添加成功', 'Info');
                                                    $('#dg').datagrid('reload');
                                                    $('#userDialog').dialog('close');
                                                } else {
                                                    $.messager.alert('失败', result.msg, 'error');
                                                }
                                            }
                                        });
                                    }
                                }],
                            onLoad: function() {
                                $('#user_role_tree').combotree({
                                    url: '<?php echo site_url('rbacRole/combotree') ?>',
                                    lines: true,
                                    multiple: true
                                });
                            },
                            onClose: function() {
                                $(this).dialog('destroy');
                            }
                        });

                    }
                }, '-', {
                    text: '删除',
                    iconCls: 'icon-remove',
                    handler: function() {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $.messager.confirm('警告', '<br>您确定要删除选中的管理员？<br>', function(r) {
                                if (r) {
                                    $.post('<?php echo site_url('rbacUser/del') ?>', {
                                        user_id: row.user_id
                                    }, function(result) {
                                        if (result.success) {
                                            $('#dg').datagrid('deleteRow', $('#dg').datagrid('getRowIndex'));
                                            $.messager.alert('成功', '管理员删除成功！！！', 'Info');
                                        } else {
                                            $.messager.alert('失败', result.msg, 'error');
                                        }
                                        ;
                                    }, 'json');
                                }
                            });
                        }
                    }
                }, '-', {
                    text: '修改角色',
                    iconCls: 'icon-edit',
                    handler: function() {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $('<div id="userDialog"><div/>').dialog({
                                title: '修改角色',
                                width: 520,
                                height: 220,
                                closable: true,
                                closed: false,
                                cache: false,
                                modal: true,
                                href: '<?php echo site_url('rbacUser/roleDialog') ?>',
                                buttons: [{text: '保存', iconCls: 'icon-save',
                                        handler: function() {
                                            $("#userForm").removeAttr("action").attr("action", "<?php echo site_url('rbacUser/updateRole') ?>");
                                            $('#user_role_text').val($('#user_role_tree').combotree('getText'));
                                            $('#user_role_id').val($('#user_role_tree').combotree('getValues'));
                                            $('#userForm').form('submit', {
                                                onSubmit: function() {
                                                    return $(this).form('validate');
                                                },
                                                success: function(result) {
                                                    var result = $.parseJSON(result);
                                                    if (result.success) {
                                                        $.messager.alert('成功', '用户角色修改成功！！！', 'Info');
                                                        $('#dg').datagrid('reload');
                                                        $('#userDialog').dialog('close');
                                                    } else {
                                                        $.messager.alert('失败', result.msg, 'error');
                                                    }
                                                }
                                            });
                                        }
                                    }],
                                onLoad: function() {
                                    $('#user_role_tree').combotree({
                                        url: '<?php echo site_url('rbacRole/combotree') ?>' + '/' + row.user_id,
                                        lines: true,
                                        multiple: true,
                                        onLoadSuccess: function() {
                                            $('#userForm').form('load', row);
                                        }
                                    });
                                },
                                onClose: function() {
                                    $(this).dialog('destroy');
                                }
                            });

                        }
                    }
                }],
            columns: [[
                    {field: 'user_id', title: '编码', width: 100, align: 'center'},
                    {field: 'user_account', title: '帐号', width: 100, align: 'center'},
                    {field: 'user_name', title: '姓名', width: 140, align: 'center'},
                    {field: 'user_email', title: '邮箱', width: 180, align: 'center'},
                    {field: 'user_createdate', title: '注册时间', width: 140, align: 'center'},
                    {field: 'user_lastdate', title: '最后登录时间', width: 140, align: 'center'},
                    {field: 'user_status', title: '状态', width: 100, align: 'center',
                        formatter: function(value, row, index) {
                            switch (parseInt(value)) {
                                case 1:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.user_id + ',' + value + ',' + index + ')"><img src="images/icos/new.png" width="16" height="16" style="border:none" title="新增加"></a>';
                                    break;
                                case 2:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.user_id + ',' + value + ',' + index + ')"><img src="images/icos/ok.png" width="16" height="16" style="border:none" title="正常"></a>';
                                    break;
                                case 3:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.user_id + ',' + value + ',' + index + ')"><img src="images/icos/lock.png" width="16" height="16" style="border:none" title="已关闭"></a>';
                                    break;
                                default:
                                    break;
                            }
                        }
                    },
                    {field: 'user_role', title: '所属角色', width: 120, align: 'center'}
                ]]
        });
    });



    //更改管理员状态
    function statusChange(user_id, user_status, index) {
        var msg;
        switch (user_status) {
            case 1:
                msg = '您确定启用该用户？<br><br>若是误操作请立即关闭该角色！';
                break;
            case 2:
                msg = '<br>您确定要禁用该用户？';
                break;
            case 3:
                msg = '<br>您确定要重新启用该用户？';
                break;
            default:
                return false;

        }
        $.messager.confirm('警告', msg, function(r) {
            if (r) {
                $.post('<?php echo site_url('rbacUser/status') ?>', {
                    user_id: user_id,
                    user_status: user_status
                }, function(result) {
                    if (result.success) {
                        $('#dg').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.show({// show error message
                            title: '错误',
                            msg: result.msg
                        });
                    }
                }, 'json');
            }
        });
    }

</script>


<table id="dg"></table>
<!--<div class="easyui-layout" data-options="fit : true,border : true" id='customerLayout'>
    <div data-options="region:'north',border:false" style="padding: 10px;height: 40px;overflow: hidden;" align="center">
        <span>　查找：</span>
        <select name="select" id="select">
            <option value="user" selected="selected">帐号</option>
            <option value="name">单位名称</option>
        </select>
        <span>　为：</span><input id="value" type="text">
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doSearch();">查找</a>
    </div>
    <div data-options="region:'center',border:false" style=' margin-left:0px;'>
       
    </div>
</div> -->



