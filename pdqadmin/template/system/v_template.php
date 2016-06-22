<script type="text/javascript">
    $(function () {
        $('#dg').datagrid({
            url: '<?php echo site_url('template/loadData') ?>',
            fit: true,
            singleSelect: true,
            rownumbers: true, pagination: true,
            pageSize: 20,
            columns: [[
                    {field: 'id', title: '流水号', width: 160, align: 'center'},
                    {field: 'key', title: '调用键值', width: 160, align: 'center'},
                    {field: 'pub_about', title: '原视图说明', width: 160, halign: 'center', sortable: true},
                    {field: 'public', title: '原视图文件名', width: 160, halign: 'center', sortable: true},
                    {field: 'private', title: '新视图文件名', width: 160, halign: 'center', sortable: true},
                    {field: 'pri_about', title: '说明', width: 260, halign: 'center', sortable: true}
                ]]

        });

        $('#btnAdd').bind('click', function () {
            if ($('#country_id').combobox('getValue') === '') {
                $.messager.alert('警告', '请选择国家！', 'warning');
                return false;
            }
            $('<div id="templateDialog"><div/>').dialog({
                title: '添加模板',
                width: 470,
                height: 420,
                closable: true,
                closed: false,
                cache: false,
                modal: true,
                href: '<?php echo site_url('template/loadEditDialog') ?>',
                buttons: [{text: '保存', iconCls: 'icon-save',
                        handler: function () {
                            $("#templateForm").removeAttr("action").attr("action", "<?php echo site_url('template/insert') ?>");
                            $('#templateForm').form('submit', {
                                onSubmit: function () {
                                    return $(this).form('validate');
                                },
                                success: function (result) {
                                    var result = $.parseJSON(result);
                                    if (result.success) {
                                        $.messager.alert('成功', '视图模板添加成功！！！', 'info');
                                        $('#dg').datagrid('appendRow', {
                                            _id: result.id,
                                            key: $('#key').val(),
                                            pub_about:$('#pub_about').val(),
                                            public: $('#public').val(),
                                            private: $('#private').val(),
                                            pri_about: $('#pri_about').val()
                                        });
                                        $('#templateDialog').dialog('close');
                                    } else {
                                        $.messager.alert('失败', result.error, 'error');
                                    }
                                }
                            });
                        }
                    }],
                onLoad: function () {
                    $('#country_code').val($('#country_id').combobox('getValue'));
                    $('#terminal_code').val($('#terminal').combobox('getValue'));
                    $('#select').text($('#country_id').combobox('getText') + '    ' + $('#terminal').combobox('getText'))
                },
                onClose: function () {
                    $('#templateDialog').dialog('destroy');
                }
            });
        });

        $('#btnEdit').bind('click', function () {
            if ($('#country_id').combobox('getValue') === '') {
                $.messager.alert('警告', '请选择国家！', 'warning');
                return false;
            }
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $('<div id="templateDialog"><div/>').dialog({
                    title: '修改模板',
                    width: 470,
                    height: 420,
                    closable: true,
                    closed: false,
                    cache: false,
                    modal: true,
                    href: '<?php echo site_url('template/loadEditDialog') ?>',
                    buttons: [{text: '保存', iconCls: 'icon-save',
                            handler: function () {
                                $("#templateForm").removeAttr("action").attr("action", "<?php echo site_url('template/update') ?>");
                                $('#templateForm').form('submit', {
                                    onSubmit: function () {
                                        return $(this).form('validate');
                                    },
                                    success: function (result) {
                                        var result = $.parseJSON(result);
                                        if (result.success) {
                                            $.messager.alert('成功', '视图模板修改成功！！！', 'info');
                                            $('#dg').datagrid('updateRow', {
                                                index: $('#dg').datagrid('getRowIndex', row),
                                                row: {
                                                    key: $('#key').val(),
                                                    pub_about:$('#pub_about').val(),
                                                    public: $('#public').val(),
                                                    private: $('#private').val(),
                                                    pri_about: $('#pri_about').val()
                                                }
                                            });
                                            $('#templateDialog').dialog('close');
                                        } else {
                                            $.messager.alert('失败', result.error, 'error');
                                        }
                                    }
                                });
                            }
                        }],
                    onLoad: function () {
                        $('#templateForm').form('load', row);
                        $('#country_code').val($('#country_id').combobox('getValue'));
                        $('#terminal_code').val($('#terminal').combobox('getValue'));
                        $('#select').text($('#country_id').combobox('getText') + '    ' + $('#terminal').combobox('getText'));
                    },
                    onClose: function () {
                        $(this).dialog('destroy');
                    }
                });
            }
        });
        $('#btnDelete').bind('click', function () {
            if ($('#country_id').combobox('getValue') === '') {
                $.messager.alert('警告', '请选择国家！', 'warning');
                return false;
            }
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $.messager.confirm('警告', '<br>您确定要删除选中的'+$('#country_id').combobox('getText')+$('#terminal').combobox('getText')+'模板？<br>', function (r) {
                    if (r) {
                        $.post('<?php echo site_url('template/del') ?>', {
                            id: row.id,
                            terminal_code: $('#terminal').combobox('getValue'),
                            country_code: $('#country_id').combobox('getValue')
                        }, function (result) {
                            if (result.success) {
                                $('#dg').datagrid('deleteRow', $('#dg').datagrid('getRowIndex'));
                                $.messager.alert('成功', '模板删除成功！！！', 'info');
                            } else {
                                $.messager.alert('失败', result.msg, 'error');
                            }
                        }, 'json');
                    }
                });
            }
        });
        $('#btnSearch').bind('click', function () {
            $('#dg').datagrid('load', {
                country_code: $('#country_id').combobox('getValue'),
                terminal_code: $('#terminal').combobox('getValue')
            });
        });

    });
    //更改国家状态
    function statusCountry(template_id, template_code, template_status, index) {
        var msg;
        if (template_status === 1) {
            msg = '您确定启用该国家？';
        } else {
            msg = '<br>您确定要关闭该国家？';
        }
        $.messager.confirm('警告', msg, function (r) {
            if (r) {
                $.post('<?php echo site_url('template/statusChange') ?>', {
                    template_id: template_id,
                    template_code: template_code,
                    template_status: template_status
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


<table id="dg" data-options="toolbar:'#tb'"></table>

<div id="tb" style="padding:5px 5px;">
    <a href="javascript:void(0);"  class="easyui-linkbutton" id="btnAdd" data-options="plain:true,iconCls:'icon-add'">添加模板</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" id="btnEdit" data-options="plain:true,iconCls:'icon-edit'">编辑模板</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" id="btnDelete" data-options="plain:true,iconCls:'icon-remove'">删除模板</a>
    <span style="margin-left: 50px;">选择国家：</span> 
    <input name="country_id" type="text" id="country_id" size="10" maxlength="2" class="easyui-combobox" data-options="valueField:'code',textField:'name',editable:false" style="width:200px;" url="<?php echo site_url('country/combobox') ?>" />
    选择终端：
    <select id="terminal" class="easyui-combobox" name="terminal" style="width:100px;">
        <option value="1" selected>电脑版</option>
        <option value="2">移动版</option>
    </select>
    <a href="javascript:void(0);" id="btnSearch" class="easyui-linkbutton" iconCls="icon-search">查找</a>
</div>

