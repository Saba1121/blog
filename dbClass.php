<?php 


class info {
    public $host = 'localhost';
    public $user = 'root';
    public $pass = '';


    function fetch_all($sort = 'id') {   
        $stmt = $this->db_conn -> query("SELECT * FROM $this->tb_name ORDER BY " . $sort);
        return $stmt;   
    }

    function find($query, $arr, $sort = 'id') {
        $stmt = $this->db_conn -> prepare("SELECT * FROM $this->tb_name WHERE $query ORDER BY $sort");
        $stmt -> execute($arr);
        return $stmt;
    }

    function findOne($query, $arr) {
        $stmt = $this->db_conn -> prepare("SELECT * FROM $this->tb_name WHERE $query LIMIT 1");
        $stmt -> execute($arr);
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    function delete($id) {
        $this->db_conn -> prepare("DELETE FROM $this->tb_name WHERE id=?") -> execute([$id]);
    }

    function update($id) {
        $this->db_conn -> prepare("UPDATE list SET completed = completed*(-1) WHERE id=?") -> execute([$id]);
    }
}





Class accounts_db extends info {
    public $db_conn;
    public $tb_name = 'accounts';
    public $db_name = 'social';

    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->pass);
    }

    function getCurrentUser() {
        $id = $_SESSION['id'];

        // $stmt = $this->findOne('id = ?', [$id]);
        return $this->findOne('id = ?', [$id]);
    }

    function register($username, $email, $password, $password2) {
        $errors = '';

        $stmt = $this->find("email = ?", [$email]);

        if($stmt->rowCount() > 0) $errors .= 'Email Exists <br>';

        if(strlen($password) < 5) $errors .= 'Password Too Short <br>';         
        if($password != $password2) $errors .= 'Passwords Doesnt Match <br>';
 
        if(strlen($username) < 5) $errors .= 'Username Too Short';
         
        if(strlen($email) < 6 || !strpos($email, '@')) $errors .= 'Enter Valid Email';

        if(strlen($errors) > 0) throw new Exception($errors);

        $password_hashed = password_hash(trim($password), PASSWORD_DEFAULT);

        if(!password_verify($password, $password_hashed)) {
            throw new Exception('Failed');
        }

        $this->db_conn -> prepare("INSERT INTO accounts(username,email,password) VALUES(?,?,?)") -> execute([$username, $email, $password_hashed]);
    

        $stmt = $this->find("email = ?", [$email]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        $id = $stmt['id'];

        $this->db_conn -> query("CREATE TABLE `messages`.`$id` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `message` VARCHAR(300) NOT NULL , `reciever` INT(11) NOT NULL , `time` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        
        session_start();
        $_SESSION['logged'] = true;
        $_SESSION['id'] = $id;
    }
    
    function login($email , $password) {
        $errors = '';

        if(strlen($password) < 5) $errors .= 'Password Too Short <br>';
    
        if(strlen($email) < 6 || !strpos($email, '@')) $errors .= 'Enter Valid Email <br>';

        if(strlen($errors) > 0) throw new Exception($errors);


        $stmt = $this->find("email = ?", [$email]);
        $stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

        if(password_verify($password, $stmt['password'])) {
            session_start();
            $_SESSION['logged'] = true;
            $_SESSION['id'] = $stmt['id'];
        } else {
            throw new Exception('Incorrect Email or Password');
        }
    }
};


$accountsDB = new accounts_db();


Class posts_db extends info {
    public $db_conn;
    public $db_name = 'social';
    public $tb_name = 'posts';

    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->user);
    }

    function upload($post, $posters_id) {
        $errors = '';

        $post = trim($post);
        if(strlen($post) == 0) $errors .= 'Post must be at least 1 charachter';

        if(strlen($errors) > 0) throw new Exception($errors);

        $time = date("Y-m-d H:i:s");
        $this->db_conn -> prepare("INSERT INTO posts(post, time, posters_id) VALUES(?, ?, ?)") -> execute([$post, $time, $posters_id]);
    }
}

$postsDB = new posts_db();


Class messages_db extends info {
    public $db_conn;
    public $tb_name;
    public $db_name = 'messages';

    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->user);
    }

    function fetch_messages($local_id, $guest_id) {

        $local_id = str_replace('`', '', $local_id);
        $guest_id = str_replace('`', '', $guest_id);
        $stmt = $this->db_conn -> prepare("SELECT * FROM `$local_id` WHERE reciever = ? UNION SELECT * FROM `$guest_id` WHERE reciever = ? ORDER BY time ASC");

        $stmt -> execute([$guest_id, $local_id]);
        return $stmt;
    }
    
    function upload($msg, $local, $adress) {
        $errors = '';
        
        $msg = trim($msg);

        if(strlen($msg) == 0) return 0;
        
        $time = date("Y-m-d H:i:s");
        $local = str_replace('`', '', $local);
        $this->db_conn -> prepare("INSERT INTO `$local`(message,reciever,time) VALUES(?,?,?)") -> execute([$msg, $adress, $time]);
        
    }
}

$messagesDB = new messages_db();