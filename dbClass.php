<?php 

//Main Class
class info {
    public $host = 'localhost';
    public $user = 'root';
    public $pass = '';

    //Fetch everything with possibility of sorting
    function fetch_all($sort = 'id') {   
        $stmt = $this->db_conn -> query("SELECT * FROM $this->tb_name ORDER BY " . $sort);
        return $stmt;   
    }

    //Find rows with possibility of sorting
    //Exmaple: find('id > ?', [$id])
    function find($query, $arr, $sort = 'id') {
        $stmt = $this->db_conn -> prepare("SELECT * FROM $this->tb_name WHERE $query ORDER BY $sort");
        $stmt -> execute($arr);
        return $stmt;
    }

    //Find only one row
    //Example: findOne('id = ?', [$id])
    function findOne($query, $arr) {
        $stmt = $this->db_conn -> prepare("SELECT * FROM $this->tb_name WHERE $query LIMIT 1");
        $stmt -> execute($arr);
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    //delete row
    function delete($id) {
        $this->db_conn -> prepare("DELETE FROM $this->tb_name WHERE id=? LIMIT 1") -> execute([$id]);
    }

    //Update row
    function update($id) {
        $this->db_conn -> prepare("UPDATE list SET completed = completed*(-1) WHERE id=? LIMIT 1") -> execute([$id]);
    }
}




//CLass foor accounts table
Class accounts_db extends info {
    public $db_conn;
    public $tb_name = 'accounts';
    public $db_name = 'social';

    //Establishing connection 
    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->pass);
    }

    //Get current users id
    function getCurrentUser() {
        $id = $_SESSION['id'];

        return $this->findOne('id = ?', [$id]);
    }

    //Function for Registering user
    function register($username, $email, $password, $password2) {
        //Storing errors
        $errors = '';

        
        //Check if password is at least 6 characters
        if(strlen($password) < 5) $errors .= 'Password Too Short <br>';
        //Check if password was repeated correctly         
        if($password != $password2) $errors .= 'Passwords Doesnt Match <br>';
        
        //Check if username is at least 6 characters
        if(strlen($username) < 5) $errors .= 'Username Too Short';
         
        //Check if email is valid
        if(strlen($email) < 6 || !strpos($email, '@')) $errors .= 'Enter Valid Email';
        
        //Stop process if error is found
        if(strlen($errors) > 0) throw new Exception($errors);


        //Check if email already exists
        $stmt = $this->find("email = ?", [$email]);

        if($stmt->rowCount() > 0) $errors .= 'Email Exists <br>';

        //Stop process if error is found
        if(strlen($errors) > 0) throw new Exception($errors);

        //Hashing password
        $password_hashed = password_hash(trim($password), PASSWORD_DEFAULT);
        //Checking if hashing was done correctly
        if(!password_verify($password, $password_hashed)) {
            throw new Exception('Failed');
        }

        //Insert user into database
        $this->db_conn -> prepare("INSERT INTO accounts(username,email,password) VALUES(?,?,?)") -> execute([$username, $email, $password_hashed]);
    

        $stmt = $this->find("email = ?", [$email]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        $id = $stmt['id'];

        $this->db_conn -> query("CREATE TABLE `messages`.`$id` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `message` VARCHAR(300) NOT NULL , `reciever` INT(11) NOT NULL , `time` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        
        session_start();
        $_SESSION['logged'] = true;
        $_SESSION['id'] = $id;
    }
    


    //Function for logging user in
    function login($email , $password) {
        //Storing errors
        $errors = '';

        //Checks if password is at least 6 characters
        if(strlen($password) < 5) $errors .= 'Password Too Short <br>';
    
        //Checks if email is valid
        if(strlen($email) < 6 || !strpos($email, '@')) $errors .= 'Enter Valid Email <br>';

        //Stop process if error
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



//Class for posts table
Class posts_db extends info {
    public $db_conn;
    public $db_name = 'social';
    public $tb_name = 'posts';

    //Establishing connection
    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->user);
    }

    //Upload new post to database
    function upload($post, $posters_id) {
        //Storing errors
        $errors = '';

        //trimming extra whitespaces
        $post = trim($post);
        //Post must be at least 1 character
        if(strlen($post) == 0) $errors .= 'Post must be at least 1 charachter';

        //Stop process if error
        if(strlen($errors) > 0) throw new Exception($errors);

        //Get current time
        $time = date("Y-m-d H:i:s");
        //Upload
        $this->db_conn -> prepare("INSERT INTO posts(post, time, posters_id) VALUES(?, ?, ?)") -> execute([$post, $time, $posters_id]);
    }
}

$postsDB = new posts_db();

//Class for messages table
Class messages_db extends info {
    public $db_conn;
    public $tb_name;
    public $db_name = 'messages';

    //Establishing connection
    function __construct() {
        $this->db_conn = new PDO("mysql: host=$this->host; dbname=$this->db_name;", $this->user, $this->user);
    }

    //Fetch messages
    function fetch_messages($local_id, $guest_id) {

        $local_id = str_replace('`', '', $local_id);
        $guest_id = str_replace('`', '', $guest_id);
        $stmt = $this->db_conn -> prepare("SELECT * FROM `$local_id` WHERE reciever = ? UNION SELECT * FROM `$guest_id` WHERE reciever = ? ORDER BY time ASC");

        $stmt -> execute([$guest_id, $local_id]);
        return $stmt;
    }
    

    //Upload new message
    function upload($msg, $local, $adress) {
        //Stroing erors
        $errors = '';
        
        //trim extra whitespaces
        $msg = trim($msg);

        //Messages must container at least one character
        if(strlen($msg) == 0) return false;
        
        //Get current user
        $time = date("Y-m-d H:i:s");

        //Replacing dangerous character to avoid sql injection
        $local = str_replace('`', '', $local);

        //upload
        $this->db_conn -> prepare("INSERT INTO `$local`(message,reciever,time) VALUES(?,?,?)") -> execute([$msg, $adress, $time]);
        
    }
}

$messagesDB = new messages_db();