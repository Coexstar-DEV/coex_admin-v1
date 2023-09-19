<?php
  session_start();

  include_once "../common/user_function.php";
  include_once "../common/". ($_SESSION["language"] == "kr" ? "lang_kr.php" : "lang_en.php");
  include_once "../common/dbconn.php";
  include_once "../inc/top_menu.php";
  include_once "../inc/left_menu_marketgraphsell.php";
  include_once "../data.php";
  include_once "../data_sell.php";
?>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

<style>
  .active {
    color: red !important;
  }
</style>

<div style="color:#000;"><h1><?php echo $_GET['m_pay']; ?></h1></div>

<div style="background-color:#eeeeee; height:25px;line-height: 2;text-align:left;padding-left:10%;">
	<a href='../d_dashboard/member_buy.php?m_pay=BTC&c_div=1' style="margin-right:10px;"><?=M_GRAPH_BUY?></a>
	<span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
	<a href='../d_dashboard/member_sell.php?m_pay=BTC&c_div=1' style="margin-right:10px;color:#d4af37;font-weight:bold"><?=M_GRAPH_SELL?></a>
  <span style="border-right:1px solid #88b7da; margin-right:20px;"> </span>
</div>

<?php 
  $m_pay = $_GET['m_pay']; 
  $c_div = $_GET['c_div'];
  $_SESSION['c_div'] = $c_div;
  $_SESSION['m_pay'] = $m_pay;   
  $active = $_SESSION['m_pay'];
  $b = $_SESSION['new'];

    // Output all coins with their market payment
    $stmt = $pdo->prepare("SELECT c.c_coin, c.c_no, m.m_pay, '$c_div' AS c_pay FROM coexstar.m_setup AS m
                                                                          LEFT JOIN coexstar.c_setup AS c
                                                                                 ON c.c_no = m.m_div
                                                                              WHERE c.c_coin IS NOT NULL AND
                                                                                    c.c_use <> 0 AND
                                                                                    m.m_pay <> 'ETH' AND
                                                                                    m.m_pay <> c.c_coin AND
                                                                                    c.c_basecoin <> 1 AND
                                                                                    m.m_pay = '$m_pay'
                                                                           ORDER BY c.c_coin");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
      $c_coin2 = $row['c_coin'];
      $c_no2 = $row['c_no'];
      $m_pay2 = $row['m_pay'];
      $c_pay2 = $row['c_pay'];
    
        // Output date and sum of each coin based on market price
        $stmt1 = $pdo->prepare("SELECT '$m_pay2' AS payname, DATE(FROM_UNIXTIME(c_signdate+8*3600)) AS signdate, ROUND(sum(c_exchange+0), 6) as sumcoin, '$c_coin2' as coinname FROM coexstar.coin_point 
        WHERE c_signdate > (1570406400 + 8*3600) and c_signdate < (unix_timestamp(NOW()) + 8*3600) and c_id <> 'coex@miner.net' and c_pay = '$c_pay2' and c_div = '$c_no2' and c_category = 'tradesell'
        GROUP BY DATE(FROM_UNIXTIME(c_signdate+8*3600))
        ORDER BY signdate");
        $stmt1->execute();
        $json = [];
        while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)):
          extract($row1);

          $milliseconds = 1000 * strtotime($row1['signdate']);

          $json[] = [$milliseconds, $row1['sumcoin']];
        endwhile;

        $alldata = json_encode($json, JSON_NUMERIC_CHECK);

        $filename = strtolower($row['c_coin']).'-'.strtolower($row['m_pay']).'-sell'.'.json';

        file_put_contents('data-json/'.$filename, $alldata);
    
    endwhile;

    $stmt3 = $pdo->prepare("SELECT 'PHP' AS payname, DATE(FROM_UNIXTIME(c_signdate+8*3600)) AS signdate, ROUND(sum(c_exchange+0), 6) as sumcoin FROM coexstar.coin_point 
    WHERE c_signdate > (1570406400 + 8*3600) and c_signdate < (unix_timestamp(NOW()) + 8*3600) and c_id <> 'coex@miner.net' and c_pay = 0 and c_category = 'tradesell'
    GROUP BY DATE(FROM_UNIXTIME(c_signdate+8*3600))
    ORDER BY signdate");

    $stmt->execute();
    while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC));
?>

<br/>

<form method="POST"> 
  <div style="float:right; margin-top: 2%;">
  <select class="form-control" name="coinselect" id="coinselect" required>
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
    <?php if(!isset($_POST['coinselect'])) { ?>
      <option value="">Select Coin</option>
    <?php } else { ?>
      <option value="<?php if(isset($_POST['coinselect'])) echo $_POST['coinselect']; ?>"><?php if(isset($_POST['coinselect'])) { $coinselectexplode = explode('_', $_POST['coinselect']); echo $coinselectexplode[0]; }  ?></option>
    <?php } ?>
    <?php
      $m_pay= $_GET['m_pay'];
      if($m_pay == 'PHP')
      {
        if($allcoins > 0){
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type']?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php } ?>><?php echo $row['c_coin'] ?></option>
          <?php endwhile;
        }
        else { echo '<option value="">Coin is not available</option>'; } 
      }
      else if($m_pay == 'KRWC')
      {
        if($allcoins > 0) {
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php }
                                     else if($row['c_coin']== 'QTUM'){ ?>hidden <?php }?>><?php echo $row['c_coin'] ?></option>
          <?php endwhile;
        }
        else { echo '<option value="">Coin is not available</option>'; }                   
      }
      else if($m_pay == 'USDT')
      {
        if($allcoins > 0) {
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected <?php }
                                      else if ($row['c_coin']== 'QTUM') { ?> hidden <?php } ?>><?php echo $row['c_coin'] ?></option>
          <?php endwhile;
        }
        else { echo '<option value="">Coin is not available</option>'; }                                 
      }
      else if($m_pay == 'BTC')
      {
        if($allcoins > 0) {
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?php echo $row['c_coin'];echo'_';echo $row['c_no'];echo'_';echo $_POST['type'] ?>" <?php if(isset($coinselect) && $coinselect == $row['c_coin']) { ?>selected 
            <?php }else if ($row['c_coin'] == 'QTUM' || $row['c_coin'] == 'EOS' || $row['c_coin'] == 'TRX' || $row['c_coin'] == 'BTC'){ ?> hidden <?php } ?>>
            <?php echo $row['c_coin'] ?></option>
          <?php endwhile;
        }
        else { echo '<option value="">Coin is not available</option>'; }
      }
    ?>
    </select>&nbsp;
		<input type="radio" name="type"  <? if ($type == "Daily") { ?>checked<? } ?> value="Daily" required> Daily
		<input type="radio" name="type" <? if ($type == "Monthly") { ?>checked<? } ?> value = "Monthly" required>Monthly
    <input type="submit" value="Generate" class="adminbttn" name="Generate" id="Generate">
  </div>
  <?php 
    if(!isset($_POST['Generate']))
    {   
      echo '<div id="container" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
      echo '<hr/>';
      echo '<div id="container1" style="width: 100%; min-height: 650px; max-height: auto;margin-top:30px;%"></div>';
      echo '<br/>';
    } 
    else
    {
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
          text: 'Daily Market Sell Analysis'
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
                showInNavigator: true
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
  Highcharts.getJSON('data-json/eth-btc-sell.json', success);
  Highcharts.getJSON('data-json/ltc-btc-sell.json', success);
  Highcharts.getJSON('data-json/bch-btc-sell.json', success);
  Highcharts.getJSON('data-json/btg-btc-sell.json', success);
  Highcharts.getJSON('data-json/xrp-btc-sell.json', success);
  Highcharts.getJSON('data-json/rvn-btc-sell.json', success);
  Highcharts.getJSON('data-json/enj-btc-sell.json', success);
<?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/btc-php-sell.json', success);
  Highcharts.getJSON('data-json/eth-php-sell.json', success);
  Highcharts.getJSON('data-json/ltc-php-sell.json', success);
  Highcharts.getJSON('data-json/bch-php-sell.json', success);
  Highcharts.getJSON('data-json/btg-php-sell.json', success);
  Highcharts.getJSON('data-json/xrp-php-sell.json', success);
  Highcharts.getJSON('data-json/qtum-php-sell.json', success);
  Highcharts.getJSON('data-json/rvn-php-sell.json', success);
  Highcharts.getJSON('data-json/eos-php-sell.json', success);
  Highcharts.getJSON('data-json/enj-php-sell.json', success);
  Highcharts.getJSON('data-json/trx-php-sell.json', success);
<?php } elseif($_SESSION['m_pay'] == 'KRWC' && !isset($_POST['Generate'])) { ?> 
  Highcharts.getJSON('data-json/btc-krwc-sell.json', success);
  Highcharts.getJSON('data-json/eth-krwc-sell.json', success);
  Highcharts.getJSON('data-json/ltc-krwc-sell.json', success);
  Highcharts.getJSON('data-json/bch-krwc-sell.json', success);
  Highcharts.getJSON('data-json/btg-krwc-sell.json', success);
  Highcharts.getJSON('data-json/xrp-krwc-sell.json', success);
  Highcharts.getJSON('data-json/rvn-krwc-sell.json', success);
  Highcharts.getJSON('data-json/eos-krwc-sell.json', success);
  Highcharts.getJSON('data-json/enj-krwc-sell.json', success);
  Highcharts.getJSON('data-json/trx-krwc-sell.json', success);
<?php } else { ?>
  Highcharts.getJSON('data-json/btc-usdt-sell.json', success);
  Highcharts.getJSON('data-json/eth-usdt-sell.json', success);
  Highcharts.getJSON('data-json/ltc-usdt-sell.json', success);
  Highcharts.getJSON('data-json/bch-usdt-sell.json', success);
  Highcharts.getJSON('data-json/btg-usdt-sell.json', success);
  Highcharts.getJSON('data-json/xrp-usdt-sell.json', success);
  Highcharts.getJSON('data-json/rvn-usdt-sell.json', success);
  Highcharts.getJSON('data-json/eos-usdt-sell.json', success);
  Highcharts.getJSON('data-json/enj-usdt-sell.json', success);
  Highcharts.getJSON('data-json/trx-usdt-sell.json', success);
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
    totalText_likes1 = 0;

  function createChart() {

    Highcharts.stockChart('container1', {
        rangeSelector: {
            selected: 4
        },

        legend: {
          enabled: true,
        },

        title: {
          text: 'Monthly Market Sell Analysis'
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
  Highcharts.getJSON('data-json/eth-btc-sell.json', success);
  Highcharts.getJSON('data-json/ltc-btc-sell.json', success);
  Highcharts.getJSON('data-json/bch-btc-sell.json', success);
  Highcharts.getJSON('data-json/btg-btc-sell.json', success);
  Highcharts.getJSON('data-json/xrp-btc-sell.json', success);
  Highcharts.getJSON('data-json/rvn-btc-sell.json', success);
  Highcharts.getJSON('data-json/enj-btc-sell.json', success);
<?php } elseif($_SESSION['m_pay'] == 'PHP' && !isset($_POST['Generate'])) { ?>
  Highcharts.getJSON('data-json/btc-php-sell.json', success);
  Highcharts.getJSON('data-json/eth-php-sell.json', success);
  Highcharts.getJSON('data-json/ltc-php-sell.json', success);
  Highcharts.getJSON('data-json/bch-php-sell.json', success);
  Highcharts.getJSON('data-json/btg-php-sell.json', success);
  Highcharts.getJSON('data-json/xrp-php-sell.json', success);
  Highcharts.getJSON('data-json/qtum-php-sell.json', success);
  Highcharts.getJSON('data-json/rvn-php-sell.json', success);
  Highcharts.getJSON('data-json/eos-php-sell.json', success);
  Highcharts.getJSON('data-json/enj-php-sell.json', success);
  Highcharts.getJSON('data-json/trx-php-sell.json', success);
<?php } elseif($_SESSION['m_pay'] == 'KRWC' && !isset($_POST['Generate'])) { ?> 
  Highcharts.getJSON('data-json/btc-krwc-sell.json', success);
  Highcharts.getJSON('data-json/eth-krwc-sell.json', success);
  Highcharts.getJSON('data-json/ltc-krwc-sell.json', success);
  Highcharts.getJSON('data-json/bch-krwc-sell.json', success);
  Highcharts.getJSON('data-json/btg-krwc-sell.json', success);
  Highcharts.getJSON('data-json/xrp-krwc-sell.json', success);
  Highcharts.getJSON('data-json/rvn-krwc-sell.json', success);
  Highcharts.getJSON('data-json/eos-krwc-sell.json', success);
  Highcharts.getJSON('data-json/enj-krwc-sell.json', success);
  Highcharts.getJSON('data-json/trx-krwc-sell.json', success);
<?php } else { ?>
  Highcharts.getJSON('data-json/btc-usdt-sell.json', success);
  Highcharts.getJSON('data-json/eth-usdt-sell.json', success);
  Highcharts.getJSON('data-json/ltc-usdt-sell.json', success);
  Highcharts.getJSON('data-json/bch-usdt-sell.json', success);
  Highcharts.getJSON('data-json/btg-usdt-sell.json', success);
  Highcharts.getJSON('data-json/xrp-usdt-sell.json', success);
  Highcharts.getJSON('data-json/rvn-usdt-sell.json', success);
  Highcharts.getJSON('data-json/eos-usdt-sell.json', success);
  Highcharts.getJSON('data-json/enj-usdt-sell.json', success);
  Highcharts.getJSON('data-json/trx-usdt-sell.json', success);
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
    }(Highcharts));
</script>

<script type="text/javascript">
  $(document).ready(function() { // onload

  var coinname = document.getElementById("coinselect").value;

  var coinname = "<?php  
    if(empty($coinname)) {
      if($_GET['m_pay'] == 'BTC'){
        echo 'ETH';} 
      else {
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
    $.getJSON('data_sell.php', function(data) {
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