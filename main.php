<?php
if ($_COOKIE ['login'] == '') {header ('Location: /index.php');
   exit();}
require 'connectdb.php';
$login =$_COOKIE ['login'];
$sql='SELECT `calcul` FROM `users` WHERE `login`= :login ';
$query = $pdo->prepare($sql);
$query -> execute([':login'=>$login]);
$row=$query->fetch(PDO::FETCH_OBJ);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Главная</title>
  </head>
  <body>
    <div class="header"></div>
    <div class="polzunok-label">
      <span class="polz_sum_text">Домен</span>
      <div class="polz_sum_val">
        <input type="text" id="amount2" data-calc="domen" value="" class="cal_val">

      </div>
    </div>
    <div class="polzunok-label">
      <span class="polz_sum_text">Дата начало</span>
      <div class="polz_sum_val">
        <input type="text" id="amount2" data-calc="start_date" value="2021-01" class="cal_val">
        <div class="polz_sum_value_suffix">дней</div>
      </div>
    </div>
    <div class="polzunok-label">
      <span class="polz_sum_text">Дата конец</span>
      <div class="polz_sum_val">
        <input type="text" id="amount2" data-calc="end_date" value="2021-03" class="cal_val">
        <div class="polz_sum_value_suffix">дней</div>
      </div>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_per_vis" id="save-info">
      <label class="custom-control-label" for="save-info">Cр-колво визитов</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_vis" id="save-info">
      <label class="custom-control-label" for="save-info">Кол-во визитов</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_traf" id="save-info">
      <label class="custom-control-label" for="save-info">Трафик по каналам</label>
      <select class="select_traf">
        <option  value="all" selected="selected">Все</option>
        <option  value="Direct">Прямой зайход</option>
        <option  value="Email">Электронная почта</option>
        <option  value="Social">Социальные сети</option>
        <option  value="Search / Organic">Поиск (органика)</option>
        <option  value="Search / Paid">Поиск (платный)</option>
        <option  value="Display Ad">Баннеры</option>
        <option  value="Referral">Рефералы</option>
        <option  value="Other">Другое</option>
      </select>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_vs" id="save-info">
      <label class="custom-control-label" for="save-info">Трафик ПК VS Мобильный</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_sm" id="save-info">
      <label class="custom-control-label" for="save-info">Похожие сайты</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_keysorg" id="save-info">
      <label class="custom-control-label" for="save-info">Ключевые запросы (Органика)</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_keyspay" id="save-info">
      <label class="custom-control-label" for="save-info">Ключевые запросы (Платный)</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_comorg" id="save-info">
      <label class="custom-control-label" for="save-info">Конкуренты (Органика)</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_compay" id="save-info">
      <label class="custom-control-label" for="save-info">Конкуренты (Платный)</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_refin" id="save-info">
      <label class="custom-control-label" for="save-info">Входящий реферальный трафик</label>
    </div>
    <div class="custom-control custom-checkbox col-md-12">
      <input type="checkbox" class="checkbox_refout" id="save-info">
      <label class="custom-control-label" for="save-info">Исходящий реферальный трафик</label>
    </div>
    <button id="exit" name="button">Узнать </button>
    <div class="result">
      <?php echo $row->calcul; ?>
    </div>

  </body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
  var load_ajax;
  var load = $('#exit').click(function() {
      if(load_ajax) load_ajax.abort();
      var data = {
          domen: $('[data-calc="domen"]').val(),
          start_date: $('[data-calc="start_date"]').val(),
          end_date: $('[data-calc="end_date"]').val(),
          count: $('[count-name="count"]').val(),
          chek_sr_v: $('[class="checkbox_per_vis"]:checked').val(),
          chek_visit: $('[class="checkbox_vis"]:checked').val(),
          chek_traf: $('[class="checkbox_traf"]:checked').val(),
          chek_sel_traf: $('[class="select_traf"]').val(),
          chek_descvsmob: $('[class="checkbox_vs"]:checked').val(),
          chek_simmilar: $('[class="checkbox_sm"]:checked').val(),
          chek_keyogr: $('[class="checkbox_keysorg"]:checked').val(),
          chek_keypay: $('[class="checkbox_keyspay"]:checked').val(),
          chek_comorg: $('[class="checkbox_comorg"]:checked').val(),
          chek_compay: $('[class="checkbox_compay"]:checked').val(),
          chek_refin: $('[class="checkbox_refin"]:checked').val(),
          chek_refout: $('[class="checkbox_refout"]:checked').val()
      };
      load_ajax = $.post('/functions.php', data, function (resp) {
          $('.result').html(resp);
          $('body,html').animate({
              scrollTop: 0
          }, 400);

      });
  });


  </script>
</html>
