<script type="text/javascript" src="easyui/datagrid-detailview.js"></script>

<script type="text/javascript">
    $(function () {
        $('#dg').datagrid({
            url: '<?php echo site_url('country/loadData') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true, pagination: true,
            pageSize: 20,
            toolbar: [{
                    text: '修改国家',
                    iconCls: 'icon-edit',
                    handler: function () {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $('<div id="countryDialog"><div/>').dialog({
                                title: '修改国家',
                                width: 520,
                                height: 640,
                                closable: true,
                                closed: false,
                                cache: false,
                                modal: true,
                                href: '<?php echo site_url('country/loadEditDialog') ?>',
                                buttons: [{text: '保存', iconCls: 'icon-save',
                                        handler: function () {
                                            $("#countryForm").removeAttr("action").attr("action", "<?php echo site_url('country/update') ?>");
                                            $('#countryForm').form('submit', {
                                                onSubmit: function () {
                                                    return $(this).form('validate');
                                                },
                                                success: function (result) {
                                                    var result = $.parseJSON(result);
                                                    if (result.success) {
                                                        $.messager.alert('成功', '国家信息修改成功！！！', 'info');
                                                        $('#dg').datagrid('updateRow', {
                                                            index: $('#dg').datagrid('getRowIndex', row),
                                                            row: {
                                                                domain: $('#domain').val(),
                                                                language_code: $('#language_code').combobox('getValue'),
                                                                currency_symbol: $('#currency_symbol').val(),
                                                                currency_payment:$('#currency_payment').val(),
                                                                au_rate: $('#au_rate').val(),
                                                                flag_sort: $('#flag_sort').val(),
                                                                timezone: $('#timezone').val(),
                                                                google: $('#google').val(),
                                                                facebook: $('#facebook').val(),
                                                                facebook_id: $('#facebook_id').val(),
                                                                service_mail:$('#service_mail').val()
                                                            }
                                                        });
                                                        $('#countryDialog').dialog('close');
                                                    } else {
                                                        $.messager.alert('失败', result.error, 'error');
                                                    }
                                                }
                                            });
                                        }
                                    }],
                                onLoad: function () {
                                    $('#countryForm').form('load', row);
                                },
                                onClose: function () {
                                    $(this).dialog('destroy');
                                }
                            });
                        }
                    }
                }],
            columns: [[
                    {field: 'country_id', title: '编码', width: 60, align: 'center'},
                    {field: 'name', title: '国家', width: 240, halign: 'center', sortable: true},
                    {field: 'domain', title: '域名', width: 160, halign: 'center', sortable: true},
                    {field: 'iso_code_2', title: '国标2', width: 60, align: 'center', sortable: true},
                    {field: 'iso_code_3', title: '国标3', width: 60, align: 'center'},
                    {field: 'language_code', title: '语言', width: 50, align: 'center', sortable: true},
                    {field: 'flag_sort', title: '国旗排序', width: 160, halign: 'center', sortable: true},
                    {field: 'currency_symbol', title: '货币符号', width: 70, align: 'center'},
                    {field: 'currency_payment', title: '货币类型', width: 70, align: 'center'},
                    {field: 'au_rate', title: '澳币对换', width: 70, align: 'center'},
                    {field: 'google', title: 'Google码', width: 100, align: 'center'},
                    {field: 'facebook_id', title: 'FaceBook ID', width: 180, halign: 'center'},
                    {field: 'facebook', title: 'FaceBook链接', width: 180, halign: 'center'},
                    {field: 'service_mail', title: '服务邮箱', width: 180, align: 'center'},
                    {field: 'timezone', title: '时区', width: 200, align: 'center'},
                    {field: 'status', title: '状态', width: 60, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            switch (parseInt(value)) {
                                case 1:
                                    return '<a href="javascript:void(0)" onClick="statusCountry(' + row.country_id + ',\'' + row.iso_code_2 + '\',' + value + ',' + index + ')"><img src="img/icons/16/lock.png" width="16" height="16" style="border:none" title="已关闭"></a>';
                                    break;
                                case 2:
                                    return '<a href="javascript:void(0)" onClick="statusCountry(' + row.country_id + ',\'' + row.iso_code_2 + '\',' + value + ',' + index + ')"><img src="img/icons/16/success.png" width="16" height="16" style="border:none" title="正常"></a>';
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                ]],
            view: detailview,
            detailFormatter: function (index, row) {
                return '<div style="padding:30px"><table class="ddv"></table></div>';
            },
            onExpandRow: function (index, row) {
                var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');
                ddv.datagrid({
                    url: '<?php echo site_url('shipFormula/getList') ?>/' + row.iso_code_2,
                    fitColumns: true,
                    singleSelect: true,
                    rownumbers: true,
                    loadMsg: '',
                    height: 'auto',
                    toolbar: [{
                            text: '添加运费公式',
                            iconCls: 'icon-add',
                            handler: function () {
//                                var row_child = ddv.datagrid('getSelected');
//                                if (row_child) {

                                $('<div id="shipFormulaDialog"><div/>').dialog({
                                    title: '添加运费公式',
                                    width: 520,
                                    height: 360,
                                    closable: true,
                                    closed: false,
                                    cache: false,
                                    modal: true,
                                    href: '<?php echo site_url('shipFormula/loadEditDialog') ?>',
                                    buttons: [{text: '保存', iconCls: 'icon-save',
                                            handler: function () {
                                                $("#shipFormulaForm").removeAttr("action").attr("action", "<?php echo site_url('shipFormula/insert') ?>");
                                                $('#shipFormulaForm').form('submit', {
                                                    onSubmit: function () {
                                                        return $(this).form('validate');
                                                    },
                                                    success: function (result) {
                                                        var result = $.parseJSON(result);
                                                        if (result.success) {
                                                            $.messager.alert('成功', '运费公式添加成功!!', 'info');
                                                            ddv.datagrid('appendRow', {
                                                                id: result.id,
                                                                country_code: $('#country_code').val(),
                                                                weight: $('#weight').val(),
                                                                formula: $('#formula').val(),
                                                                special: result.special
                                                            });
                                                            $('#shipFormulaDialog').dialog('close');
                                                        } else {
                                                            $.messager.alert('失败', result.msg, 'error');
                                                        }
                                                    }
                                                });
                                            }
                                        }],
                                    onLoad: function () {
                                        $('#country_code').val(row.iso_code_2);
                                    },
                                    onClose: function () {
                                        $(this).dialog('destroy');
                                    }
                                });
//                                }
                            }
                        }, '-', {
                            text: '修改运费公式',
                            iconCls: 'icon-edit',
                            handler: function () {
                                var row_child = ddv.datagrid('getSelected');
                                if (row_child) {
                                    $('<div id="shipFormulaDialog"><div/>').dialog({
                                        title: '修改国家',
                                        width: 520,
                                        height: 360,
                                        closable: true,
                                        closed: false,
                                        cache: false,
                                        modal: true,
                                        href: '<?php echo site_url('shipFormula/loadEditDialog') ?>',
                                        buttons: [{text: '保存', iconCls: 'icon-save',
                                                handler: function () {
                                                    $("#shipFormulaForm").removeAttr("action").attr("action", "<?php echo site_url('shipFormula/update') ?>");
                                                    $('#shipFormulaForm').form('submit', {
                                                        onSubmit: function () {
                                                            return $(this).form('validate');
                                                        },
                                                        success: function (result) {
                                                            var result = $.parseJSON(result);
                                                            if (result.success) {
                                                                $.messager.alert('成功', '运费公式修改成功!!', 'info');
                                                                ddv.datagrid('updateRow', {
                                                                    index: ddv.datagrid('getRowIndex', row_child),
                                                                    row: {
                                                                        weight: $('#weight').val(),
                                                                        formula: $('#formula').val(),
                                                                        special: result.special
                                                                    }
                                                                });
                                                                $('#shipFormulaDialog').dialog('close');
                                                            } else {
                                                                $.messager.alert('失败', result.msg, 'error');
                                                            }
                                                        }
                                                    });
                                                }
                                            }],
                                        onLoad: function () {
                                            $('#shipFormulaForm').form('load', row_child);
                                        },
                                        onClose: function () {
                                            $(this).dialog('destroy');
                                        }
                                    });
                                }
                            }
                        }, '-', {
                            text: '删除运费公式',
                            iconCls: 'icon-remove',
                            handler: function () {
                                var row_child = ddv.datagrid('getSelected');
                                if (row_child) {
                                    $.messager.confirm('警告', '确定要删除这种运费计价方式？', function (r) {
                                        if (r) {
                                            $.post('<?php echo site_url('shipFormula/delete') ?>', {
                                                shipFormulaID: row_child.id
                                            }, function (result) {
                                                if (result.success) {
                                                    var index_child = ddv.datagrid('getRowIndex', row_child);
                                                    ddv.datagrid('deleteRow', index_child);
                                                } else {
                                                    $.messager.alert('错误', result.error, 'error');
                                                }
                                            }, 'json');
                                        }
                                    });
                                }
                            }
                        }],
                    columns: [[
                            {field: 'id', title: '流水号', width: 100, align: 'center'},
                            {field: 'country_code', title: '国家代码', width: 100, halign: 'center', hidden: true},
                            {field: 'weight', title: '货物重量(g)', width: 100, halign: 'center', align: 'right', sortable: true},
                            {field: 'formula', title: '运费计算公式', width: 300, halign: 'center'},
                            {field: 'special', title: '是否特殊处理', width: 150, align: 'center',
                                formatter: function (value, row, index) {
                                    if (parseInt(value) === 2) {
                                        return '是';
                                    } else {
                                        return '否';
                                    }
                                }
                            }
                        ]],
                    onResize: function () {
                        $('#dg').datagrid('fixDetailRowHeight', index);
                    },
                    onLoadSuccess: function () {
                        setTimeout(function () {
                            $('#dg').datagrid('fixDetailRowHeight', index);
                        }, 0);
                    }
                });
                $('#dg').datagrid('fixDetailRowHeight', index);
            }
        });
    });
    //更改国家状态
    function statusCountry(country_id, country_code, country_status, index) {
        var msg;
        if (country_status === 1) {
            msg = '您确定启用该国家？';
        } else {
            msg = '<br>您确定要关闭该国家？';
        }
        $.messager.confirm('警告', msg, function (r) {
            if (r) {
                $.post('<?php echo site_url('country/statusChange') ?>', {
                    country_id: country_id,
                    country_code: country_code,
                    country_status: country_status
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



