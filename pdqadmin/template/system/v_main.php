<html>
    <head>
        <base href="<?php echo $template ?>">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>系统设置</title>
        <link rel="stylesheet" type="text/css" href="easyui/themes/bootstrap/easyui.css" />
        <link rel="stylesheet" type="text/css" href="easyui/themes/icon.css" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>         
        <script type="text/javascript" src="easyui/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript">
            function  loadpage(url, title) {
                $('#webbody').layout('panel', 'center').panel('setTitle', title);
                $('#webbody').layout('panel', 'center').panel('refresh', url);
            }
        </script>
    </head>
    <body id="webbody" class="easyui-layout">
        <div data-options="region:'west',split:true,title:'系统菜单'" style="width:150px;">
            <div class="easyui-accordion" fit="true" border="false">
                <div title="数据字典管理" iconCls="icon-system" style="padding:10px;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('language/index') ?>', '平台语种管理');">平台语种管理</a> <br>
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('country/index') ?>', '国家模板管理');">国家模板管理</a> <br>
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('domain/index') ?>', '域名配置管理');">域名配置管理</a> <br>
                </div>


                <div title="前端模板管理" iconCls="icon-system" style="padding:10px;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('template/index') ?>', '前端模板管理');">前端模板管理</a> <br>
                </div>                
                <div title="RBAC控制管理" iconCls="icon-system" style="padding:10px;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('rbacNode/loadGrid') ?>', '操作节点管理');">操作节点管理</a> <br>
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('rbacRole/loadGrid') ?>', '用户角色管理');">用户角色管理</a> <br>
                    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-systemset"  onClick="loadpage('<?php echo site_url('rbacUser/loadGrid') ?>', '平台用户管理');">平台用户管理</a> <br>
                </div> 
            </div>
        </div>
        <!--<div data-options="region:'east',split:true,collapsed:false,title:'East'" style="width:100px;padding:10px;">east region</div>-->
        <!--<div data-options="region:'south',border:false" style="height:30px;background:#A9FACD;padding:5px;margin-top: 2px;">版权所有：@Legentec.com 南昌朗杰科技</div>-->
        <div data-options="region:'center',title:'系统设置管理平台',href:''" id="webcontent" style="padding:0px;">
        </div>
    </body>
</html>