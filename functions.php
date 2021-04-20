
<?php
require 'connectdb.php';
$login = $_COOKIE ['login'];
$sql='SELECT `calcul`, `api_key`FROM `users` WHERE `login`= :login ';
$query = $pdo->prepare($sql);
$query -> execute([':login'=>$login]);
$row=$query->fetch(PDO::FETCH_OBJ);
$id=$row->id;

$api_key=$row->api_key;//ключ к акку
$global_count =$row->calcul;

$site=filter_var($_POST ['domen']);
$start_date= filter_var($_POST ['start_date']);
$end_date= filter_var($_POST ['end_date']);
$granuarity= "monthly";
$main_domain_only ="false"; //true - если значение только для домена . false - для домена и субдоменов
$format = "json";

if ($global_count!=0){
// среднее кол-во страниц
  if (filter_var($_POST['chek_sr_v'])=='on'){
    $page_resours ="total-traffic-and-engagement/pages-per-visit?";
    $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
    $result = json_decode(file_get_contents($url),true);
    $pages_per_vis = $result['pages_per_visit'];
    $sum_visit=0;
    $count;
    foreach ($pages_per_vis as $key) {
      $sum_visit+=$key['pages_per_visit'];
      $count++;

    }
    $global_count--;
    echo '
      <div class="result">
        <p> Среднее кол-во просмотров сраниц за даты: <strong>'.intval($sum_visit/$count).'</strong> шт</p>
      ';

  }
// Визиты
  if (filter_var($_POST['chek_visit'])=='on'){
    $page_resours ="total-traffic-and-engagement/visits?";
    $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
    $result = json_decode(file_get_contents($url),true);
    $pages_per_vis = $result['visits'];
    $sum_visit=0;
    $count;
    foreach ($pages_per_vis as $key) {
      $sum_visit+=$key['visits'];
      $count++;

    }
    $global_count--;
    echo '
      <div class="result">
        <p> Сумма визитов: <strong>'.intval($sum_visit).'</strong> шт</p>
      ';

  }
//трафик по каналам
if (filter_var($_POST['chek_traf'])=='on' &&  !empty($_POST['chek_sel_traf'])){
  $page_resours ="traffic-sources/overview?";
  $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
  $result = json_decode(file_get_contents($url),true);
  $select = $_POST['chek_sel_traf'];
  $sum_search=0;
  $sum_email=0;
  $sum_direct=0;
  $sum_other=0;
  $sum_directad=0;
  $sum_Social=0;
  $sum_Referal=0;
  $sum_searchpaid;
  $sum_xz;
  foreach ($result['overview'] as $key => $value) {
    if ($select == 'all'){
      switch ($value['source_type']) {
        case 'Direct':
          $sum_direct+=$value['share'];
          break;
        case 'Email':
          $sum_email+=$value['share'];
          break;
        case 'Social':
          $sum_Social+=$value['share'];
          break;
        case 'Search / Organic':
          $sum_search+=$value['share'];
          break;
        case 'Display Ad':
          $sum_directad+=$value['share'];
          break;
        case 'Referral':
          $sum_Referal+=$value['share'];
          break;
        case 'Other':
          $sum_other+=$value['share'];
          break;
        case 'Search / Paid':
          $sum_searchpaid+=$value['share'];
          break;
        default:
          $sum_xz+=$value['share'];
          break;
      }
    }
    else {
      if ($value['source_type']==$select){
          $sum_xz+=$value['share'];
      }
    }
  }
  if ($select == 'all'){
    echo '<div>
      <p> Прямой заход -  <strong>'.(round($sum_direct,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Email -  <strong>'.(round($sum_email,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Social -  <strong>'.(round($sum_Social,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Search / Organic -  <strong>'.(round($sum_search,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Display Ad -  <strong>'.(round($sum_directad,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Referral -  <strong>'.(round($sum_Referal,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Другое -  <strong>'.(round($sum_other,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
      <p> Search / Paid -  <strong>'.(round($sum_searchpaid,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>
    </div>';
  }
  else{
    echo '<div>
      <p> '.$select.' -  <strong>'.(round($sum_xz,4,PHP_ROUND_HALF_UP)*100).' %</strong> </p>
      <br>';
  }
  $global_count--;
}

//трафик десктоп против мобайла
  if (filter_var($_POST['chek_descvsmob'])=='on'){
    $page_resours ="total-traffic-and-engagement/visits-split?";
    $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
    $result = json_decode(file_get_contents($url),true);
    $summ= $result['desktop_visit_share']+$result['mobile_web_visit_share'];
    echo
    '<div>
      <p> ПК <strong>'.round((($result['desktop_visit_share']/$summ)*100),2) .'%</strong> </p>
      <p> Мобильный <strong>'.round((($result['mobile_web_visit_share']/$summ)*100),2) .' %</strong> </p>
    </div>';
    $global_count--;
  }
  //похожие сайты
  if (filter_var($_POST['chek_simmilar'])=='on'){
    $page_resours ="similar-sites/similarsites?";
    $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
    $result = json_decode(file_get_contents($url),true);
    foreach ($result['similar_sites'] as $key => $value) {
      echo
      '<div>
        <p> Домен <strong>'.$value['url'].'</strong> </p>
        <p> Похожесть <strong>'.(round($value['score'],3)*100).' %</strong> </p>
      </div>';
    }
    $global_count--;

  }
 // ключи органики
 if (filter_var($_POST['chek_keyogr'])=='on'){
   $page_resours ="traffic-sources/organic-search?";
   $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
   $result = json_decode(file_get_contents($url),true);
   foreach ($result['search'] as $key => $value) {
     echo
     '<div>
       <p> Ключ -  <strong>'.$value['search_term'].'</strong> </p>
       <p> Доля трафика <strong>'.(round($value['share'],3)*100).' %</strong> </p>
       <p> Объем трафика -  <strong>'.$value['volume'].'</strong> </p>
       <br>
     </div>';
   }
   $global_count--;

 }
// ключи платный поиск
 if (filter_var($_POST['chek_keypay'])=='on'){
   $page_resours ="traffic-sources/paid-search?";
   $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
   $result = json_decode(file_get_contents($url),true);
   foreach ($result['search'] as $key => $value) {
     echo
     '<div>
       <p> Ключ -  <strong>'.$value['search_term'].'</strong> </p>
       <p> Доля трафика <strong>'.(round($value['share'],3)*100).' %</strong> </p>
       <br>
     </div>';
   }
   $global_count--;

 }
// конкуренты в органике
if (filter_var($_POST['chek_comorg'])=='on'){
  $page_resours ="search-competitors/organicsearchcompetitors?";
  $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
  $result = json_decode(file_get_contents($url),true);
  foreach ($result['data'] as $key => $value) {
    echo
    '<div>
      <p> Домен -  <strong>'.$value['url'].'</strong> </p>
      <p> Соотстветствие <strong>'.(round($value['score'],3)*100).' %</strong> </p>
      <br>
    </div>';
  }
  $global_count--;

}
// конкуренты в платном
if (filter_var($_POST['chek_compay'])=='on'){
  $page_resours ="search-competitors/paidsearchcompetitors?";
  $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
  $result = json_decode(file_get_contents($url),true);
  foreach ($result['data'] as $key => $value) {
    echo
    '<div>
      <p> Домен -  <strong>'.$value['url'].'</strong> </p>
      <p> Соотстветствие <strong>'.(round($value['score'],3)*100).' %</strong> </p>
      <br>
    </div>';
  }
  $global_count--;

}
// Реферальный трафик - входящий
if (filter_var($_POST['chek_refin'])=='on'){
  $page_resours ="traffic-sources/referrals?";
  $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
  $result = json_decode(file_get_contents($url),true);
  foreach ($result['referrals'] as $key => $value) {
    echo
    '<div>
      <p> Домен -  <strong>'.$value['domain'].'</strong> </p>
      <p> Доля трафика <strong>'.(round($value['share'],4)*100).' %</strong> </p>
      <br>
    </div>';
  }
  $global_count--;

}
// Реферальный трафик - исходящий
if (filter_var($_POST['chek_refout'])=='on'){
  $page_resours ="traffic-sources/outgoing-referrals?";
  $url = "https://api.spymetrics.ru/v1/website/".$site."/".$page_resours."api_key=".$api_key."&start_date=".$start_date."&end_date=".$end_date."&granuarity=".$granuarity."&main_domain_only=".$main_domain_only."&fomat=".$format;
  $result = json_decode(file_get_contents($url),true);
  foreach ($result['referrals'] as $key => $value) {
    echo
    '<div>
      <p> Домен -  <strong>'.$value['domain'].'</strong> </p>
      <p> Доля трафика <strong>'.(round($value['share'],4)*100).' %</strong> </p>
      <br>
    </div>';
  }
  $global_count--;

}
$sql = "UPDATE `users` SET  `calcul`=:count WHERE `login`= :login";
$query = $pdo->prepare($sql);
$res=$query -> execute([':count'=>$global_count, ':login'=>$login]);

echo $global_count;

}
else{
  echo '
  <div class="result">
    <p> Денег нет, но вы держитесь</p>
  </div>

  ';
};

 ?>
