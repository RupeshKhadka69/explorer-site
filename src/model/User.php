<?php

class User
{
    private $db;
    private $id;
    private $username;
    private $email;
    private $password;


    public function __construct(ConnectDb $db)
    {
        $this->db = $db->getConnection();
    }

    public function register($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username,email,password) values(?,?,?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        return $stmt->execute();
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            return password_verify($password, $user['password']) ? $user['id'] : false;
        }

        return false;
    }
   
    public function update($username,$email,$password,$id){
       
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users set username = ? , email = ?, password = ? , where id = ?');
        $stmt->bind_param("sssi",$username,$email,$hashed_password,$id);
        return $stmt->execute();
    }
    public function getUserById($id){
    $stmt = $this->db->prepare("select id,username,email from users where id= ?")  ;
    $stmt->bind_param("i",$id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }
}