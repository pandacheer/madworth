<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Unicorn Admin</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/paddy.css" />  
        <!--[if lt IE 9]>
        <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->

    </head>	
    <body>
        <div class="container">
        <div class="row">
            <div class="col-md-12">
              <!-- Table -->
              <BR>
              <p align="center"><img src="img/logo_sell.png" style="width: 300px;margin-top: 30px;" ></p><br>
              <table class="table selllist">
                <?php foreach ($salesData as $key=>$sales): ?>
                    <tr>
                        <td><img src="http://static.drgrab.com/template_pc/en/image/flag/<?=$key?>.png" style="width: 30px" title="<?php echo $key;?>"></td>
                        <td>Today:<span><?=  is_numeric($sales['currentSalesData'])?round($sales['currentSalesData']/100,2):$sales['currentSalesData'];?></span></td>
                        <td>Yesterday:<span><?= is_numeric($sales['yesterdaySalesData'])?round($sales['yesterdaySalesData']/100,2):$sales['yesterdaySalesData']?></span></td>
                    </tr>
                <?php endforeach ?>
                

                <?php if (!empty($totalSalesData)): ?>
                    <tr>
                        <td>Total Today:<span><?=round($totalSalesData['currentSalesData']/100,2)?></span></td>
                        <td>Total Yesterday:<span><?= round($totalSalesData['yesterdaySalesData']/100,2)?></span></td>
                        <td>Total This Month:<span><?= round($totalSalesData['currentMonthSalesData']/100,2)?></span></td>
                        <td>Total Last Month:<span><?= round($totalSalesData['lastMonthSalesData']/100,2)?></span></td>
                    </tr>
                <?php endif ?>
                

                <?php foreach ($salesData as $key=>$sales): ?>
                    <tr>
                        <td><img src="http://static.drgrab.com/template_pc/en/image/flag/<?=$key?>.png" style="width: 30px" title="<?php echo $key;?>"></td>
                        <td>This Month:<span><?= is_numeric($sales['currentMonthSalesData'])?round($sales['currentMonthSalesData']/100,2):$sales['currentMonthSalesData']?></span></td>
                        <td>Last Month:<span><?= is_numeric($sales['lastMonthSalesData'])?round($sales['lastMonthSalesData']/100,2):$sales['lastMonthSalesData']?></span></td>
                    </tr>
                <?php endforeach ?>  
              </table>
            </div>
        </div>
        </div>
    </body>
</html>
