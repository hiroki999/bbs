<?php 
  require_once('dbfunc.php');
  // index.phpから送られたデータをサニタイジングしてデータベースに挿入
  $inputed_data = $_POST;
  foreach($inputed_data as $key => $val) {
    $inputed_data[$key] = htmlspecialchars($val);
  }

  $dbc = new DBConet();
  $is_set = $dbc->set_data($inputed_data);
  if ($is_set) {
    $success = $dbc->insert_data();
    if (!$success) echo "データベース接続失敗<br>";
  }
  $title = $dbc->get_title();

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="3; ./index.php"> 
    <title>掲示板</title>
    <link rel="stylesheet" href="css/base.css">
  </head>
  <body>
    <div id="container">
      <header>
        <h1><?php echo $title; ?></h1>
      </header>
      <main role="main">
        <p>3秒後に前の画面に戻ります。</p>
      </main>
    </div><!-- container -->
  </body>
</html>
