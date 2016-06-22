<script type="text/javascript">
    $(function () {
        $('#dg').datagrid({
            url: '<?php echo site_url('domain/loadData') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true, pagination: true,
            toolbar: [{
                    text: '添加域名',
                    iconCls: 'icon-add',
                    handler: function () {
                        $('<div id="domainDialog"><div/>').dialog({
                            title: '添加域名',
                            width: 520,
                            height: 220,
                            closable: true,
                            closed: false,
                            cache: false,
                            modal: true,
                            href: '<?php echo site_url('domain/loadEditDialog') ?>',
                            buttons: [{text: '保存', iconCls: 'icon-save',
                                    handler: function () {
                                        $("#domainForm").removeAttr("action").attr("action", "<?php echo site_url('domain/insert') ?>");
                                        $('#domainForm').form('submit', {
                                            onSubmit: function () {
                                                return $(this).form('validate');
                                            },
                                            success: function (result) {
                                                var result = $.parseJSON(result);
                                                if (result.success) {
                                                    $.messager.alert('成功', '添加域名信息修改成功！！！', 'info');
                                                    $('#dg').datagrid('updateRow', {
                                                        index: $('#dg').datagrid('getRowIndex', row),
                                                        row: {
                                                            about: result.about
                                                        }
                                                    });
                                                    $('#domainDialog').dialog('close');
                                                } else {
                                                    $.messager.alert('成功', result.error, 'error');
                                                }
                                            }
                                        });
                                    }
                                }],
                            onLoad: function () {
                                $('#code').attr('readonly', false);
                            },
                            onClose: function () {
                                $(this).dialog('destroy');
                            }
                        });


                    }
                }, '-', {
                    text: '修改域名',
                    iconCls: 'icon-edit',
                    handler: function () {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $('<div id="domainDialog"><div/>').dialog({
                                title: '修改域名',
                                width: 520,
                                height: 220,
                                closable: true,
                                closed: false,
                                cache: false,
                                modal: true,
                                href: '<?php echo site_url('domain/loadEditDialog') ?>',
                                buttons: [{text: '保存', iconCls: 'icon-save',
                                        handler: function () {
                                            $("#domainForm").removeAttr("action").attr("action", "<?php echo site_url('domain/update') ?>");
                                            $('#domainForm').form('submit', {
                                                onSubmit: function () {
                                                    return $(this).form('validate');
                                                },
                                                success: function (result) {
                                                    var result = $.parseJSON(result);
                                                    if (result.success) {
                                                        $.messager.alert('成功', '域名信息修改成功！！！', 'info');
                                                        $('#dg').datagrid('updateRow', {
                                                            index: $('#dg').datagrid('getRowIndex', row),
                                                            row: {
                                                                about: result.about
                                                            }
                                                        });
                                                        $('#domainDialog').dialog('close');
                                                    } else {
                                                        $.messager.alert('成功', result.error, 'error');
                                                    }
                                                }
                                            });
                                        }
                                    }],
                                onLoad: function () {
                                    $('#domainForm').form('load', row);
                                },
                                onClose: function () {
                                    $(this).dialog('destroy');
                                }
                            });

                        }
                    }
                }],
            columns: [[
                    {field: 'id', title: '编号', width: 60, align: 'center'},
                    {field: 'domain', title: '域名', width: 200, halign: 'center'},
                    {field: 'country', title: '国家代码', width: 100, align: 'center'},
                    {field: 'status', title: '状态', width: 100, align: 'center',
                        formatter: function (value, row, index) {
                            switch (parseInt(value)) {
                                case 1:
                                    return '<a href="javascript:void(0)" onClick="statusCountry(\'' + row.domain + '\',' + value + ',' + index + ')"><img src="img/icons/16/lock.png" width="16" height="16" style="border:none" title="已关闭"></a>';
                                    break;
                                case 2:
                                    return '<a href="javascript:void(0)" onClick="statusCountry(\'' + row.domain + '\',' + value + ',' + index + ')"><img src="img/icons/16/success.png" width="16" height="16" style="border:none" title="正常"></a>';
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                ]]
        });
    });



    //更改语种状态
    function statusCountry(domain, status, index) {
        var msg;
        if (status === 1) {
            msg = '您确定启用该域名？';
        } else {
            msg = '<br>您确定要关闭该域名？';
        }
        $.messager.confirm('警告', msg, function (r) {
            if (r) {
                $.post('<?php echo site_url('domain/statusChange') ?>', {
                    domain: domain,
                    status: status
                }, function (result) {
                    if (result.success) {
                        $('#dg').datagrid('updateRow', {
                            index: index,
                            row: {
                                status: result.status
                            }
                        });
                    } else {
                        $.messager.show({// show error message
                            title: '错误',
                            msg: result.error
                        });
                    }
                }, 'json');
            }
        });
    }

</script>


<table id="dg"></table>



