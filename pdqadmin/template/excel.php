<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>产品导入...</title>
  <script src="/template/js/jquery.min.js"></script>
</head>
<body>
<div class="container">
  <h1>等待跳转！</h1>
  <p><span></span>秒后录入下一条...</p>
</div>
<script>
$(function(){
  var i=1;
  var time = $('.container p span');
  time.text(i);
  var myCount = setInterval(myCount,1000);
  function myCount(){
    i--;
    time.text(i);
    if(i<=0){
      clearInterval(myCount);
      window.location = '<?=$goto?>';
    }
  }
})
</script>
</body>
</html>