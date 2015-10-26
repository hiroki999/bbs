<?php 
  // データベース接続情報
  const HOSTNAME = '';
  const DBNAME = '';
  const TABLENAME = '';
  const USERNAME = '';
  const PASSWORD = '';
  const IMGDIR = './images/'; // 画像を保存するディレクトリ名

// データベースに接続し、投稿者の名前、時刻、メッセージ、画像をデータベースに
// 挿入するオブジェクト
  class DBConet
  {
    private $pdo;
    private $data;
    private $title;
    private $now; //投稿が行われた時間
    function __construct()
    {
      $dbn = 'mysql:host=' . HOSTNAME . ';dbname=' . DBNAME 
           . ';charset=utf8';
      $this->pdo = new PDO($dbn, USERNAME, PASSWORD);
      $this->title = '';

    }
    
    function __destruct()
    {
      $this->pdo = null;
    }
    // 受け取ったデータをチェックして$dataにセットする
    public function set_data($inputed_data)
    {
    
      $this->now = time(); // 時刻を取得
      $success = false; // 画像かメッセージのどちらかが投稿されているか
      //extract($inputed_data);
      $user_name = $inputed_data['user_name'];
      $message = $inputed_data['message'];
    
      if ($user_name == '') {
        $user_name = 'Anonymous';
      } 
      // メッセージと画像が両方共投稿されていない場合、
      if ($message == ''
        && !is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) {
        $this->title = '何も投稿されていません！';
        $image_name = '';
      
      } elseif (is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) {
        // 画像が投稿されている場合
        $image_name = $this->copy_img();
        if ($image_name != '') {
          $success = true;
        }
      } else {
        // メッセージのみの投稿の場合
        $this->title = '投稿が正常に完了しました';
        $image_name = '';
        $success = true;
      }
      $timestamp = date('Y/m/d H:i:s', $this->now);
      $this->data = array(
          ':user_name' => $user_name, 
          ':timestamp' => $timestamp,
          ':message' => $message,
          ':image_name' => $image_name,
      );

      return $success;
    }

    // セットされたデータをデータベースに挿入
    public function insert_data()
    {
      $sql = "INSERT INTO bbs_data (id, user_name, timestamp, message, 
            image_name) VALUES (0, :user_name, :timestamp, :message, 
            :image_name)";
     // print_r($sql);
      echo '<br>';
      $statement = $this->pdo->prepare($sql);
      $res = $statement->execute($this->data);
      return $res;
    }
    // thanks.phpのタイトルを得る
    public function get_title()
    {
      return $this->title;
    }
    
    // ファイルの拡張子を調査し、画像ならIMGDIRにコピーする
    private function copy_img()
    {
      $extension = pathinfo($_FILES['uploaded_file']['name'], 
                   PATHINFO_EXTENSION);
      // 拡張子を調査
      if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' 
          || $extension == 'png') {
        
        $image_name = 'img_' . date('YmdHis', $this->now) . '_' 
                    . rand(1000, 9999) . '.' . $extension;
        $file_path = IMGDIR . $image_name;
        move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path);
        $this->title = '投稿が正常に完了しました';
      } else {
        $this->title = '投稿可能な画像ではありません!';
        $image_name = '';
      }
      return $image_name;
    }
    public function get_data()
    {
      $statement = $this->pdo->prepare('SELECT * FROM ' . TABLENAME);
      $statement->execute();
      $threads = $statement->fetchAll();
      return $threads;
    }
  }
?>
