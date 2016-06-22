<style>
    .clear{
        clear:both;
    }
    div ul li{
        float:left;
        margin-right:12px;
    }
    .btn {
        background: #fff;
        margin: 2px 0;
        color: #479ccf !important;
        border: 1px solid #d3dbe2 !important;
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-user-select: none;
        transition:all 0.3s;
    }
    .btn:hover {
        background: #f1f1f1;
    }
    .btn-change {
        background-color: #479ccf !important;
        color: white !important;
        border: 1px solid #479ccf !important;
        margin-left: 15px;
        margin-top: 82px;
    }
    .dg-productedit-main ul,.dg-productedit-main li {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .dg-productedit-main li {
        width: 100px;
        height: 135px;
        overflow: hidden;
        line-height: 24px;
    }
    .dg-productedit-main li img {
        border:1px solid #f2f2f2;
    }
    .dg-productedit-main li input {
        width: 80px;
        color: #333;
        padding-left: 12px;
        border: 1px solid #f1f1f1;
        height: 24px;

    }
    .dg-productedit-main a {
        font-weight: bold;
        color: #444444;
        text-decoration: none;
        font-size: 11px;
    }

</style>
<div class="dg-productedit-main">
    <iframe style="width:0;height:0;border:none;display: none;" name="if1" id="if1"></iframe>
    <ul>
        <form method="post" action="/upimg/changesort/<?php echo $_id ?>" target="if1">
            <?php
            if (!empty($pics)) {
                foreach ($pics as $key => $vo) {
                    $sort = isset($vo['sort']) ? $vo['sort'] : 0;
                    echo '<li><img src="' . IMAGE_DOMAIN . $vo['img'] . '" width="80" height="80"><br><input type="text" name="sort[]" size="2" value="' . $sort . '" /><br><a href="javascript:void(0);" onclick="del(\''.$_id.'\','.$key.')">Remove Pic</a></li>';
                }
            }
            ?>
    </ul>
    <input name="country" id="country1" type="hidden" value="<?php echo $cty;?>" />
    <input type="submit" class="btn btn-change" value="change sort" />
</form>
</div>
<form method="post" enctype="multipart/form-data" action="/upimg/action" target="if1">
    <input name="_id" type="hidden" value="<?php echo $_id ?>">
    <input name="country" id="country" type="hidden" value="<?php echo $cty;?>" />
    <input name="pic[]" type="file" accept="image/jpeg" multiple>
    <input type="submit" class="btn" value="upload" />
</form>
<script>
    function del(id,key){
        var country = document.getElementById('country').value;
        if(country){
            window.location.href="/upimg/removepic/"+id+"/"+key+"/"+country;
        }else{
            window.location.href="/upimg/removepic/"+id+"/"+key
        }
    }
</script>