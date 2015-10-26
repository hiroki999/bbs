<?php 
  require_once("dbfunc.php");
  $dbc = new DBConet();
  $threads = $dbc->get_data();
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>掲示板</title>
    <link rel="stylesheet" href="css/base.css">
  </head>
  <body>
    <div id="container">
      <header>
        <h1>掲示板</h1>
      </header>
      <main role="main">
        <div id="thread-container">
          <dl>
            <?php foreach($threads as $thread) :
              extract($thread);
              $message = nl2br($message);
            ?>
              <dt><?php echo $id . ':' . $user_name . ':'  . $timestamp; ?></dt>

              <dd>
                <?php 
                  if ($image_name != '') {
                    $image_src = IMGDIR . $image_name;
                    echo '<img src="' . $image_src  
                       . '" alt="' . $image_name  . '"><br>';
                  }
                  echo $message; 
                ?>
              </dd>
            <?php endforeach; ?>
          </dl>
        </div><!-- thread-container -->
        <div id="form-container">
          <form name="input_form" action="thanks.php" method="post" 
          enctype="multipart/form-data">
            名前:<input type="text" name="user_name"><br>
            <textarea name="message" rows="10" cols="40"></textarea><br>
            <input type="file" name="uploaded_file"><br>
            <input type="submit" value="送信" class="button">
          </form>
        </div><!-- form-container -->
      </main>
    </div><!-- container -->
  </body>
</html>
