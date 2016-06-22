<script type="text/javascript">
    $(function() {
        $('#dg').datagrid({
            url: '<?php echo site_url('rbacRole/select') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true,
            nowrap: false,
            toolbar: [{
                    text: '增加',
                    iconCls: 'icon-add',
                    handler: function() {
                        $('<div id="roleDialog"><div/>').dialog({
                            title: '增加角色',
                            width: 540,
                            height: 240,
                            closable: true,
                            closed: false,
                            cache: false,
                            modal: true,
                            href: '<?php echo site_url('rbacRole/dialog') ?>',
                            buttons: [{
                                    text: '保存',
                                    iconCls: 'icon-save',
                                    handler: function() {
                                        $('#role_access_text').val($('#role_access_tree').combotree('getText'));
                                        $('#role_access_id').val($('#role_access_tree').combotree('getValues'));
                                        $("#roleForm").removeAttr("action").attr("action", "<?php echo site_url('rbacRole/insert') ?>");
                                        $('#roleForm').form('submit', {
                                            onSubmit: function() {
                                                return $(this).form('validate');
                                            },
                                            success: function(result) {
                                                var result = $.parseJSON(result);
                                                if (result.success) {
                                                    $.messager.alert('成功', '角色【' + result.role_name + '】添加成功', 'Info');
                                                    $('#dg').datagrid('reload');
                                                    $('#roleDialog').dialog('close');
                                                } else {
                                                    $.messager.alert('失败', result.msg, 'error');
                                                }
                                            }
                                        });
                                    }
                                }],
                            onLoad: function() {
                                $('#role_access_tree').combotree({
                                    url: '<?php echo site_url('rbacNode/combotree') ?>',
                                    lines: true,
                                    cascadeCheck: false,
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
                            $.messager.confirm('警告', '<br>您确定要删除角色【' + row.role_name + '】吗？<br>', function(r) {
                                if (r) {
                                    $.post('<?php echo site_url('rbacRole/del') ?>', {
                                        role_id: row.role_id
                                    }, function(result) {
                                        if (result.success) {
                                            $.messager.alert('成功', '角色删除成功！！！', 'Info');
                                            $('#dg').datagrid('deleteRow', $('#dg').datagrid('getRowIndex'));
                                        } else {
                                            $.messager.alert('失败', result.msg, 'error');
                                        }
                                        ;
                                    }, 'json');
                                }
                            });
                        }
                    }
                }, {
                    text: '修改',
                    iconCls: 'icon-edit',
                    handler: function() {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $('<div id="roleDialog"><div/>').dialog({
                                title: '修改角色',
                                width: 520,
                                height: 270,
                                closable: true,
                                closed: false,
                                cache: false,
                                modal: true,
                                href: '<?php echo site_url('rbacRole/dialog') ?>',
                                buttons: [{text: '保存', iconCls: 'icon-save',
                                        handler: function() {
                                            $("#roleForm").removeAttr("action").attr("action", "<?php echo site_url('rbacRole/update') ?>");
                                            $('#role_access_text').val($('#role_access_tree').combotree('getText'));
                                            $('#role_access_id').val($('#role_access_tree').combotree('getValues'));

                                            $('#roleForm').form('submit', {
                                                onSubmit: function() {
                                                    return $(this).form('validate');
                                                },
                                                success: function(result) {
                                                    var result = $.parseJSON(result);
                                                    if (result.success) {
                                                        $('#dg').datagrid('updateRow', {
                                                            index: $('#dg').datagrid('getRowIndex', row),
                                                            row: {
                                                                role_name: $('#role_name').textbox('getText'),
                                                                role_access: $('#role_access_text').val(),
                                                                role_remark: $('#role_remark').textbox('getText'),
                                                                role_status: 0
                                                            }
                                                        });
                                                        $.messager.alert('成功', result.msg, 'Info');
//                                                        $('#dg').datagrid('reload');
                                                        $('#roleDialog').dialog('close');
                                                    } else {
                                                        $.messager.alert('失败', result.msg, 'error');
                                                    }
                                                }
                                            });
                                        }
                                    }],
                                onLoad: function() {
                                    $('#role_access_tree').combotree({
                                        url: '<?php echo site_url('rbacNode/combotree') ?>' + '/' + row.role_id,
                                        lines: true,
                                        cascadeCheck: false,
                                        multiple: true,
                                        onLoadSuccess: function() {
                                            $('#roleForm').form('load', row);
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
                    {field: 'role_id', title: '编号', width: 150, hidden: true},
                    {field: 'role_name', title: '角色名称', width: 180, align: 'left'},
                    {field: 'role_access', title: '可访问资源', width: 500, align: 'left'},
                    {field: 'role_remark', title: '说明', width: 180, align: 'left'},
                    {field: 'role_status', title: '状态', width: 100, align: 'center',
                        formatter: function(value, row, index) {
                            switch (parseInt(row.role_status)) {
                                case 1:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.role_id + ',' + value + ',' + index + ')"><img src="images/icos/new.png" width="16" height="16" style="border:none" title="新增"></a>';
                                    break;
                                case 2:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.role_id + ',' + value + ',' + index + ')"><img src="images/icos/ok.png" width="16" height="16" style="border:none" title="启用中"></a>';
                                    break;
                                case 3:
                                    return '<a href="javascript:void(0)" onClick="statusChange(' + row.role_id + ',' + value + ',' + index + ')"><img src="images/icos/down.png" width="16" height="16" style="border:none" title="已关闭"></a>';
                                    break;
                                default:
                                    return '<img src="images/icos/question.png" width="16" height="16" style="border:none" title="疑问">';
                                    break;

                            }
                        }
                    }
                ]]
        });
    });



    //更改角色状态
    function statusChange(role_id, role_status, index) {
        var msg;
        switch (role_status) {
            case 1:
                msg = '您确定启用该角色？<br><br>若是误操作请立即关闭该角色！';
                break;
            case 2:
                msg = '<br>您确定要禁用该角色？';
                break;
            case 3:
                msg = '<br>您确定要重新启用该角色？';
                break;
            default:
                return false;

        }
        $.messager.confirm('警告', msg, function(r) {
            if (r) {
                $.post('<?php echo site_url('rbacRole/status') ?>', {
                    role_id: role_id,
                    role_status: role_status
                }, function(result) {
                    if (result.success) {
                        $('#dg').datagrid('updateRow', {
                            index: index,
                            row: {
                                role_status: result.role_status
                            }
                        });
                    } else {
                        $.messager.show({// show error message
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }, 'json');
            }
        });
    }

</script>


<table id="dg"></table>


