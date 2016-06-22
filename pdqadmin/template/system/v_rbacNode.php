<script type="text/javascript">
    $(function() {
        $('#dg').treegrid({
            url: '<?php echo site_url('rbacNode/select') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true,
            idField: 'node_id',
            treeField: 'node_title',
            toolbar: [{
                    text: '增加',
                    iconCls: 'icon-add',
                    handler: function() {
                        $('<div id="nodeDialog"><div/>').dialog({
                            title: '增加节点',
                            width: 540,
                            height: 360,
                            closable: true,
                            closed: false,
                            cache: false,
                            modal: true,
                            href: '<?php echo site_url('rbacNode/dialog') ?>',
                            buttons: [{
                                    text: '保存',
                                    iconCls: 'icon-save',
                                    handler: function() {
                                        $("#nodeForm").removeAttr("action").attr("action", "<?php echo site_url('rbacNode/insert') ?>");
                                        $('#node_ptitle').val($('#node_pid').combotree('getText'))
                                        $('#nodeForm').form('submit', {
                                            onSubmit: function() {
                                                return $(this).form('validate');
                                            },
                                            success: function(result) {
                                                var result = $.parseJSON(result);
                                                if (result.success) {
                                                    $.messager.alert('成功', '节点【' + result.node_title + '】添加成功', 'Info');
                                                    $('#dg').treegrid('append', {
                                                        parent: result.node_pid,
                                                        data: [{
                                                                node_id: result.node_id,
                                                                node_title: result.node_title,
                                                                node_url: result.node_url,
                                                                node_sort: result.node_sort,
                                                                node_ptitle: result.node_ptitle,
                                                                node_status: 0,
                                                                node_menu: result.node_menu
                                                            }]
                                                    });
//                                                    $('#nodeForm').form('clear');
//                                                    $('#nodeDialog').dialog('close');
                                                } else {
                                                    $.messager.alert('失败', result.msg, 'error');
                                                }
                                            }
                                        });
                                    }
                                }],
                            onLoad: function() {
                                $('input:radio[name=node_menu]')[0].checked = true;
                                $('#node_pid').combotree({
                                    url: '<?php echo site_url('rbacNode/combotree') ?>',
                                    lines: true
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
                        var row = $('#dg').treegrid('getSelected');
                        if (row) {
                            $.messager.confirm('警告', '<br>您确定要删除节点【' + row.title + '】吗？<br>', function(r) {
                                if (r) {
                                    $.post('<?php echo site_url('rbacNode/del') ?>', {
                                        node_id: row.node_id
                                    }, function(result) {
                                        if (result.success) {
                                            $.messager.alert('成功', '操作节点删除成功！！！', 'Info');
                                            $('#dg').treegrid('remove', row.node_id);
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
                        var row = $('#dg').treegrid('getSelected');
                        if (row) {
                            $('<div id="nodeDialog"><div/>').dialog({
                                title: '修改节点',
                                width: 540,
                                height: 360,
                                closable: true,
                                closed: false,
                                cache: false,
                                modal: true,
                                href: '<?php echo site_url('rbacNode/dialog') ?>',
                                buttons: [{text: '保存', iconCls: 'icon-save',
                                        handler: function() {
                                            $("#nodeForm").removeAttr("action").attr("action", "<?php echo site_url('rbacNode/update') ?>");
                                            $('#node_ptitle').val($('#node_pid').combotree('getText'));
                                            $('#nodeForm').form('submit', {
                                                onSubmit: function() {
                                                    return $(this).form('validate');
                                                },
                                                success: function(result) {
                                                    var result = $.parseJSON(result);
                                                    if (result.success) {
                                                        $.messager.alert('成功', '操作节点修改成功！！！', 'Info');
                                                        $('#dg').treegrid('reload');
                                                        $('#nodeDialog').dialog('close');
                                                    } else {
                                                        $.messager.alert('失败', result.msg, 'error');
                                                    }
                                                }
                                            });
                                        }
                                    }],
                                onLoad: function() {
                                    $('#node_pid').combotree({
                                        url: '<?php echo site_url('rbacNode/combotree') ?>',
                                        lines: true,
                                        onLoadSuccess: function() {
                                            $('#nodeForm').form('load', row);
                                        }
                                    });
                                },
                                onClose: function() {
                                    $(this).dialog('destroy');
                                }
                            });

                        }
                    }
                }, {
                    text: '展开',
                    iconCls: 'icon-edit',
                    handler: function() {
                        var node = $('#dg').treegrid('getSelected');
                        if (node) {
                            $('#dg').treegrid('expandAll', node.id);
                        } else {
                            $('#dg').treegrid('expandAll');
                        }
                        //                        $('#dg').treegrid('expandAll')
                    }
                }],
            columns: [[
                    {field: 'node_id', title: '编号', width: 100, align: 'center', hidden: true},
                    {field: 'node_title', title: '节点名称', width: 220, align: 'left'},
                    {field: 'node_url', title: '节点路径', width: 180, align: 'left'},
                    {field: 'node_sort', title: '排序', width: 80, align: 'center'},
                    {field: 'node_ptitle', title: '上级节点', width: 120, align: 'center'},
                    {field: 'node_status', title: '状态', width: 100, align: 'center',
                        formatter: function(value, row, index) {
                            if (parseInt(value) === 0) {
                                return '<a href="javascript:void(0)" onClick="statusChange(' + row.node_id + ',' + value + ',' + index + ')"><img src="images/icos/down.png" width="16" height="16" style="border:none" title="已关闭"></a>';
                            } else {
                                return '<a href="javascript:void(0)" onClick="statusChange(' + row.node_id + ',' + value + ',' + index + ')"><img src="images/icos/ok.png" width="16" height="16" style="border:none" title="正常"></a>';
                            }
                        }
                    },
                    {field: 'node_menu', title: '是否菜单', width: 100, align: 'center',
                        formatter: function(value, row, index) {
                            if (parseInt(value) === 0) {
                                return '';
                            } else {
                                return '<img src="images/icos/ok.png" width="16" height="16" style="border:none" title="此条目为菜单">';
                            }
                        }
                    }
                ]]
        });
    });



    //更改管理员状态
    function statusChange(node_id, node_status, index) {
        var msg;
        if (node_status === 0) {
            msg = '您确定启用该节点？<br><br>若是误操作请立即关闭该结点！';
        } else {
            msg = '<br>您确定要关闭该结点？';
        }

        $.messager.confirm('警告', msg, function(r) {
            if (r) {
                $.post('<?php echo site_url('rbacNode/status') ?>', {
                    node_id: node_id,
                    node_status: node_status
                }, function(result) {
                    if (result.success) {
                        $('#dg').treegrid('reload');	// reload the node data                       
                        $('#dg').treegrid('expandAll', node_id);

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

    function doSave() {
        $('#nodeForm').form('submit', {
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = $.parseJSON(result);
                if (result.success) {
                    $.messager.alert(result.title, result.node_id, 'Info');
                    $('#dg').treegrid('reload');
                    $('#nodeDialog').dialog('close');
                } else {
                    $.messager.alert(result.title, result.msg, 'error');
                }
            }
        });
    }

</script>
<table id="dg"></table>


