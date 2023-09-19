<?php
session_start();

include_once "../common/user_function.php";
include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
include_once "../common/dbconn.php";
include_once "../inc/top_menu.php";
include_once "../inc/left_menu_marketgraph.php";
include_once "../data.php";
include_once "../data_buy.php";
include_once "../data2.php";
?>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!-- <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/maps/modules/map.js"></script> -->


<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<style>
.active {
  color: red !important;
}
</style>
<script>
  //  function ShowHideDiv(btc) {
  //       var dvPassport = document.getElementById("dvPassport");
  //       dvPassport.style.display = btnPassport.value == "Yes" ? "block" : "none";
  //   }
</script>
<div style="color:#000; ">
      <h1><?php echo $_GET['m_pay']; ?></h1>

</div>
<div style="background-color:#eeeeee; height:25px;line-height: 2;text-align:left;padding-left:10%;">
	<a href='../d_dashboard/member_buy.php?m_pay=BTC&c_div=1' style="margin-right:10px;color:#d4af37;font-weight:bold"><?=M_GRAPH_BUY?></a>
	<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
	<a href='../d_dashboard/member_sell.php?m_pay=BTC&c_div=1' style="margin-right:10px;"><?=M_GRAPH_SELL?></a>
<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
</div>
<?php 
 
$m_pay = $_GET['m_pay']; 
$c_div = $_GET['c_div'];
      $_SESSION['c_div'] = $c_div;
      $_SESSION['m_pay'] = $m_pay;
    
$active = $_SESSION['m_pay'];
$c =  $_SESSION['new'];
?>




<form name="form" method="POST">

<div style="float:right;margin-top:2%;">
       <select class="form-control" name="coinselect" id="coinselect" required >
                          <?php
                              $query = "SELECT c.c_coin, m.m_pay, c.c_no FROM coexstar.c_setup AS c
                              INNER JOIN coexstar.m_setup AS m
                                  ON m.m_div = c.c_no
                              WHERE c.c_basecoin <> 1 AND
                                  m.m_pay = 'PHP' AND
                                                c.c_use = 1
                              GROUP BY c.c_no
                                        ORDER BY c.c_rank ASC";
                              $stmt = $pdo->prepare($query);
                              $stmt->execute();
                
                              $allcoins = $stmt->rowCount();
                              ?>
                              <option value="">Select Coin</option>
                              <?php
                                
                                $m_pay= $_GET['m_pay'];

                                 if($m_pay == 'PHP'){
                               
                                  if($allcoins > 0){
                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type']?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php } ?>><?php echo $row['c_coin'] ?></option>
                                  <?php endwhile;}
                              
                                else {
                                  echo '<option value="">Coin is not available</option>';
                                } 
                              
                              }else if($m_pay == 'KRWC'){
                                  
                                if($allcoins > 0){
                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php }
                                     else if($row['c_coin']== 'QTUM'){ ?>hidden <?php }?>><?php echo $row['c_coin'] ?></option>
                                  <?php endwhile;}
                              
                                else {
                                  echo '<option value="">Coin is not available</option>';
                                }                   
                                }else if($m_pay == 'USDT'){
                                  if($allcoins > 0){
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                      <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php }
                                      else if ($row['c_coin']== 'QTUM') { ?> hidden <?php } ?>><?php echo $row['c_coin'] ?></option>
                                    <?php endwhile;}
                                
                                  else {
                                    echo '<option value="">Coin is not available</option>';
                                  }                                 
                                }else if($m_pay == 'BTC'){
                                  if($allcoins > 0){
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                      <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" 
                                      <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>
                                      selected 
                                      <?php }else if ($row['c_coin'] == 'QTUM' || $row['c_coin'] == 'EOS' || $row['c_coin'] == 'TRX'
                                    || $row['c_coin'] == 'BTC'){ ?> hidden <?php } ?>>
                                      <?php echo $row['c_coin'] ?>
                                      </option>
                                    <?php endwhile;}
                                
                                  else {
                                    echo '<option value="">Coin is not available</option>';
                                  }

                                }
                              ?>
                            </select> 
                            &nbsp;
							<input type="radio" name="type" required <? if ($type == "Daily") { ?>checked<? } ?> value="Daily"> Daily
							<input type="radio" name="type" required <? if ($type == "Monthly") { ?>checked<? } ?> value = "Monthly" >Monthly
                        <input type="submit" value="Generate" class="adminbttn" name="Generate" id="Generate">
      </div>
                    <?php 
                 if(!isset($_POST['Generate']))
                            {   
                                 if($_SESSION['m_pay']=='BTC' && $_SESSION['c_div']== 1){
                                  echo '<div id="container" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                  echo '<br>';
                                  echo '<div id="container1" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                }else if($_SESSION['m_pay']=='PHP' && $_SESSION['c_div']== 0){
                                  echo '<div id="container" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                  echo '<br>';
                                  echo '<div id="container1" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                }else if($_SESSION['m_pay']=='KRWC' && $_SESSION['c_div']== 3){
                                  echo '<div id="container" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                  echo '<br>';
                                  echo '<div id="container1" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';

                                }else if($_SESSION['m_pay']=='USDT' && $_SESSION['c_div']== 4){
                                  echo '<div id="container" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
                                  echo '<br>';
                                  echo '<div id="container1" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';

                                }
                            } else{

                              $b = $_POST['type'];
                             
                              $_SESSION['new'] = $b;
                              $coinselect = $_POST['coinselect'];
                              $coinselectexplode = explode('_',$coinselect);
                              $coinname =  $coinselectexplode[0];
                              $coinno = $coinselectexplode[1];
                              $coinrep = $coinselectexplode[2];
                              $_SESSION['coinno'] = $coinno;
                              $_SESSION['coinname'] = $coinname;
                              $_POST['type'] = $coinrep;
                              $m_pay = $_GET['m_pay']; 
                                    header("Location:./data_buy.php");

                            echo '<div id="dailygraph" target="2"  style="width: 100%; height: 100%;margin-top:30px;%"></div>';
                            }
                            
                    ?>
</form>

<!-- <select name="chart" id="chart">
    <option selected value="area">Area</option>
    <option value="line">Line</option>
    <option value="column">Column</option>
    <option value="columnpyramid">Column Pyramid</option>
</select>  -->



<script type="text/javascript">
  var seriesOptions1 = [],
    seriesCounter1 = 0,
    <?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
    names = ['ETH','LTC','BCH','BTG','XRP','RVN','ENJ']; // BTC
    <?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
    names = ['BTC','ETH','LTC','BCH','BTG','XRP','QTUM','RVN','EOS','ENJ','TRX']; // PHP
    <?php } else { ?>
    names = ['BTC','ETH','LTC','BCH','BTG','XRP','RVN','EOS','ENJ','TRX']; // KRWC or USDT
    <?php } ?>
    totalText_likes = 0;

  function createChart1() {

    Highcharts.stockChart('container', {
        rangeSelector: {
            selected: 4
        },

        legend: {
          enabled: true,
        },

        title: {
          text: 'Daily Market Buy Analysis'
        },
        subtitle: {
          style: {
            fontSize: '20px'
          },
          text: ''
        },
        yAxis: {
            labels: {
                formatter: function () {
                    return this.value;
                }
            },
            title:{
                text: "Total Amount of Coin"
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },

        plotOptions: {
            series: {
                compare: this.value,
                showInNavigator: true,
            }
        },

        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            split: true
        },

        series: seriesOptions1
    });
  }

  function success(data) {
    <?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
    var name = this.url.match(/(eth|ltc|bch|btg|xrp|rvn|enj)/)[0].toUpperCase(); // BTC
    <?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
    var name = this.url.match(/(btc|eth|ltc|bch|btg|xrp|qtum|rvn|eos|enj|trx)/)[0].toUpperCase(); //PHP
    <?php } else { ?>
    var name = this.url.match(/(btc|eth|ltc|bch|btg|xrp|rvn|eos|enj|trx)/)[0].toUpperCase(); // KRWC or USDT
    <?php } ?>

    var i = names.indexOf(name);
    seriesOptions1[i] = {
        name: name,
        data: data
    };

    seriesCounter1 += 1;

    if (seriesCounter1 === names.length) {
        createChart1();
    }
  }  

<?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/eth-btc-c.json', success);
  Highcharts.getJSON('data-json/ltc-btc-c.json', success);
  Highcharts.getJSON('data-json/bch-btc-c.json', success);
  Highcharts.getJSON('data-json/btg-btc-c.json', success);
  Highcharts.getJSON('data-json/xrp-btc-c.json', success);
  Highcharts.getJSON('data-json/rvn-btc-c.json', success);
  Highcharts.getJSON('data-json/enj-btc-c.json', success);
<?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/btc-php-c.json', success);
  Highcharts.getJSON('data-json/eth-php-c.json', success);
  Highcharts.getJSON('data-json/ltc-php-c.json', success);
  Highcharts.getJSON('data-json/bch-php-c.json', success);
  Highcharts.getJSON('data-json/btg-php-c.json', success);
  Highcharts.getJSON('data-json/xrp-php-c.json', success);
  Highcharts.getJSON('data-json/qtum-php-c.json', success);
  Highcharts.getJSON('data-json/rvn-php-c.json', success);
  Highcharts.getJSON('data-json/eos-php-c.json', success);
  Highcharts.getJSON('data-json/enj-php-c.json', success);
  Highcharts.getJSON('data-json/trx-php-c.json', success);
<?php } elseif($_SESSION['m_pay'] == 'KRWC' && !isset($_POST['Generate'])) { ?> 
  Highcharts.getJSON('data-json/btc-krwc-c.json', success);
  Highcharts.getJSON('data-json/eth-krwc-c.json', success);
  Highcharts.getJSON('data-json/ltc-krwc-c.json', success);
  Highcharts.getJSON('data-json/bch-krwc-c.json', success);
  Highcharts.getJSON('data-json/btg-krwc-c.json', success);
  Highcharts.getJSON('data-json/xrp-krwc-c.json', success);
  Highcharts.getJSON('data-json/rvn-krwc-c.json', success);
  Highcharts.getJSON('data-json/eos-krwc-c.json', success);
  Highcharts.getJSON('data-json/enj-krwc-c.json', success);
  Highcharts.getJSON('data-json/trx-krwc-c.json', success);
<?php } else { ?>
  Highcharts.getJSON('data-json/btc-usdt-c.json', success);
  Highcharts.getJSON('data-json/eth-usdt-c.json', success);
  Highcharts.getJSON('data-json/ltc-usdt-c.json', success);
  Highcharts.getJSON('data-json/bch-usdt-c.json', success);
  Highcharts.getJSON('data-json/btg-usdt-c.json', success);
  Highcharts.getJSON('data-json/xrp-usdt-c.json', success);
  Highcharts.getJSON('data-json/rvn-usdt-c.json', success);
  Highcharts.getJSON('data-json/eos-usdt-c.json', success);
  Highcharts.getJSON('data-json/enj-usdt-c.json', success);
  Highcharts.getJSON('data-json/trx-usdt-c.json', success);
<?php } ?>
(function (H) {
        H.Series.prototype.point = {}; // The active point
        H.Chart.prototype.callbacks.push(function (chart) {

            $(chart.container).bind('mousemove', function () {
                var legendOptions = chart.legend.options,
                    hoverPoints = chart.hoverPoints,
                    total = 0;

                if (!hoverPoints && chart.hoverPoint) {
                    hoverPoints = [chart.hoverPoint];
                }
                if (hoverPoints) {
                    var total = 0,
                        ctr = 0;
                    H.each(hoverPoints, function (point) {
                        point.series.point = point;
                        total += point.y;

                    });
                    H.each(chart.legend.allItems, function (item) {
                        item.legendItem.attr({
                            text: legendOptions.labelFormat ?
                                H.format(legendOptions.labelFormat, item) :
                                legendOptions.labelFormatter.call(item)
                        });
                    });

                    chart.legend.render();

                    chart.subtitle.update({ text: 'Total Results: ' + total });
                }
            });
        });
        // Hide the tooltip but allow the crosshair
        // H.Tooltip.prototype.defaultFormatter = function () { return false; };
    }(Highcharts));
</script>

<script type="text/javascript">
  var seriesOptions = [],
    seriesCounter = 0,
    <?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
    names = ['ETH','LTC','BCH','BTG','XRP','RVN','ENJ']; // BTC
    <?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
    names = ['BTC','ETH','LTC','BCH','BTG','XRP','QTUM','RVN','EOS','ENJ','TRX']; // PHP
    <?php } else { ?>
    names = ['BTC','ETH','LTC','BCH','BTG','XRP','RVN','EOS','ENJ','TRX']; // KRWC or USDT
    <?php } ?>
    totalText_likes = 0;


  function createChart() {

    Highcharts.stockChart('container1', {
        rangeSelector: {
            selected: 4
        },

        legend: {
          enabled: true,
        
        },

        title: {
          text: 'Monthly Market Buy Analysis'
        },
        subtitle: {
          style: {
            fontSize: '20px'
          },
          text: ''
        },
        yAxis: {
            labels: {
                formatter: function () {
                    return this.value;
                }
            },
            title:{
                text: "Total Amount of Coin"
            },
 
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },

        plotOptions: {
            series: {
                compare: this.value,
                showInNavigator: true,
                dataGrouping: {
                  approximation: 'sum',
                  forced: true,
                  units: [
                    ['month', [1]]
                  ]
                }
            }
        },

        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            split: true
        },

        series: seriesOptions
    });
  }

  function success(data) {
    <?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
    var name = this.url.match(/(eth|ltc|bch|btg|xrp|rvn|enj)/)[0].toUpperCase(); // BTC
    <?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
    var name = this.url.match(/(btc|eth|ltc|bch|btg|xrp|qtum|rvn|eos|enj|trx)/)[0].toUpperCase(); //PHP
    <?php } else { ?>
    var name = this.url.match(/(btc|eth|ltc|bch|btg|xrp|rvn|eos|enj|trx)/)[0].toUpperCase(); // KRWC or USDT
    <?php } ?>

    var i = names.indexOf(name);
    seriesOptions[i] = {
        name: name,
        data: data
    };

    seriesCounter += 1;

    if (seriesCounter === names.length) {
        createChart();
    }
  }

<?php if($_SESSION['m_pay'] == 'BTC' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/eth-btc-c.json', success);
  Highcharts.getJSON('data-json/ltc-btc-c.json', success);
  Highcharts.getJSON('data-json/bch-btc-c.json', success);
  Highcharts.getJSON('data-json/btg-btc-c.json', success);
  Highcharts.getJSON('data-json/xrp-btc-c.json', success);
  Highcharts.getJSON('data-json/rvn-btc-c.json', success);
  Highcharts.getJSON('data-json/enj-btc-c.json', success);
<?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/btc-php-c.json', success);
  Highcharts.getJSON('data-json/eth-php-c.json', success);
  Highcharts.getJSON('data-json/ltc-php-c.json', success);
  Highcharts.getJSON('data-json/bch-php-c.json', success);
  Highcharts.getJSON('data-json/btg-php-c.json', success);
  Highcharts.getJSON('data-json/xrp-php-c.json', success);
  Highcharts.getJSON('data-json/qtum-php-c.json', success);
  Highcharts.getJSON('data-json/rvn-php-c.json', success);
  Highcharts.getJSON('data-json/eos-php-c.json', success);
  Highcharts.getJSON('data-json/enj-php-c.json', success);
  Highcharts.getJSON('data-json/trx-php-c.json', success);
<?php } elseif($_SESSION['m_pay'] == 'KRWC' && !isset($_POST['Generate'])) { ?> 
  Highcharts.getJSON('data-json/btc-krwc-c.json', success);
  Highcharts.getJSON('data-json/eth-krwc-c.json', success);
  Highcharts.getJSON('data-json/ltc-krwc-c.json', success);
  Highcharts.getJSON('data-json/bch-krwc-c.json', success);
  Highcharts.getJSON('data-json/btg-krwc-c.json', success);
  Highcharts.getJSON('data-json/xrp-krwc-c.json', success);
  Highcharts.getJSON('data-json/rvn-krwc-c.json', success);
  Highcharts.getJSON('data-json/eos-krwc-c.json', success);
  Highcharts.getJSON('data-json/enj-krwc-c.json', success);
  Highcharts.getJSON('data-json/trx-krwc-c.json', success);
<?php } else { ?>
  Highcharts.getJSON('data-json/btc-usdt-c.json', success);
  Highcharts.getJSON('data-json/eth-usdt-c.json', success);
  Highcharts.getJSON('data-json/ltc-usdt-c.json', success);
  Highcharts.getJSON('data-json/bch-usdt-c.json', success);
  Highcharts.getJSON('data-json/btg-usdt-c.json', success);
  Highcharts.getJSON('data-json/xrp-usdt-c.json', success);
  Highcharts.getJSON('data-json/rvn-usdt-c.json', success);
  Highcharts.getJSON('data-json/eos-usdt-c.json', success);
  Highcharts.getJSON('data-json/enj-usdt-c.json', success);
  Highcharts.getJSON('data-json/trx-usdt-c.json', success);
<?php } ?>
(function (H) {
        H.Series.prototype.point = {}; // The active point
        H.Chart.prototype.callbacks.push(function (chart) {

            $(chart.container).bind('mousemove', function () {
                var legendOptions = chart.legend.options,
                    hoverPoints = chart.hoverPoints,
                    total = 0;

                if (!hoverPoints && chart.hoverPoint) {
                    hoverPoints = [chart.hoverPoint];
                }
                if (hoverPoints) {
                    var total = 0,
                        ctr = 0;
                    H.each(hoverPoints, function (point) {
                        point.series.point = point;
                        total += point.y;

                    });
                    H.each(chart.legend.allItems, function (item) {
                        item.legendItem.attr({
                            text: legendOptions.labelFormat ?
                                H.format(legendOptions.labelFormat, item) :
                                legendOptions.labelFormatter.call(item)
                        });
                    });

                    chart.legend.render();

                    chart.subtitle.update({ text: 'Total Results: ' + total });
                }
            });
        });
        // Hide the tooltip but allow the crosshair
        // H.Tooltip.prototype.defaultFormatter = function () { return false; };
    }(Highcharts));
</script>
<script type="text/javascript">
       $(document).ready(function() { // onload

        var coinname = "<?php  
        
      if(empty($coinname)) {
          if($_GET['m_pay'] == 'BTC'){
        echo 'ETH';} else {
          echo 'BTC';
        }
      }else{
      echo $coinname;} ?>/<?php echo  $_GET['m_pay'] ?>";
       var cointitle = "<?php echo $_SESSION['new']?> <?php if(empty($coinname)) {
          if($_GET['m_pay'] == 'BTC'){
            echo 'ETH';} else {
              echo 'BTC';
            }
          }else{
      echo $coinname;}?>/PHP Buy";

      var coinname = "<?php  
      if(empty($coinname)) {
        if($_GET['m_pay'] == 'BTC'){
          echo 'ETH';} else {
            echo 'BTC';
          }
        }else{
      echo $coinname;} ?>/<?php echo  $_GET['m_pay'] ?>";
       var cointitle = "<?php echo $_SESSION['new'] ?> <?php if(empty($coinname)) {
          if($_GET['m_pay'] == 'BTC'){
            echo 'ETH';} else {
              echo 'BTC';
            }
          }else{
      echo $coinname;}?>/<?php echo  $_GET['m_pay'] ?> Buy";
      d = 'area';

      var options =  {
        mapNavigation: {
          enabled: true,
        enableButtons: false
        },
        chart: {
          renderTo: 'dailygraph',
          type: d,
          panning: true
        },
        title: {
        text: cointitle
        },
        subtitle: {
      text: 'Click and drag in the plot area to zoom in or move'
            },
        series: [{name:coinname}]
      };
      $.getJSON('data_buy.php', function(data) {
          options.series[0].data = data;
          var chart = new Highcharts.Chart(options);
      }); 
  
      Highcharts.extend(options, Highcharts.merge(options, {
        "yAxis": [{
          "labels": {
            "format": "{value}"
          },
          "title": {
            text: "Total Count of Coin"
          }
        }],
        "xAxis": [{
          "type": "category",
          max: 10,
          "labels": {
            format: "{value}"
          },
          "title": {
            text: "Dates"
          }
        }]
      }));
    });
    
    $(document).on('change', function() { // onchange
        var coinname = "<?php  
      if(empty($coinname)) {
        echo 'BTC';
      }else{
      echo $coinname;} ?>/<?php echo  $_GET['m_pay'] ?>";
       var cointitle = "<?php echo $_SESSION['new'] ?> <?php if(empty($coinname)) {
        echo 'BTC';
      }else{
      echo $coinname;}?>/<?php echo  $_GET['m_pay'] ?> Buy";
      d = 'area';
      d = document.getElementById("chart").value;
       
        var options =  {
          mapNavigation: {
            enabled: true,
        enableButtons: false
          },
          chart: {
            renderTo: 'dailygraph',
            type: d,
            panning: true
          },
          title: {
        text: cointitle
        },
        subtitle: {
      text: 'Click and drag in the plot area to zoom in or move'
            },
          series: [{name: coinname}]
        };
      
      $.getJSON('data_buy.php', function(data) {
        options.series[0].data = data;
        var chart = new Highcharts.Chart(options);
      }); 
      
      Highcharts.extend(options, Highcharts.merge(options, {
        "yAxis": [{
          "labels": {
            "format": "{value}"
          },
          "title": {
            text: "Total Count of Coin"
          }
        }],
        "xAxis": [{
          "type": "category",
          max: 10,
          "labels": {
            format: "{value}"
          },
          "title": {
            text: "Dates"
          }
        }]
      }));
    });
   
</script>