<?php


class users
{
    //FIO and birthday
    private $firstName;
    private $secondName;
    private $lastName;
    private $birthday;
    //auth data
    private $login;
    private $passwd;
    private $email;


    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    public function setSecondName($secondName){
        $this->secondName = $secondName;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    public function setBirthday($birthday){
        if($birthday == ''){$birthday = NULL;}
        $this->birthday = $birthday;
    }

    public function setLogin($login){
        $this->login = $login;
    }

    protected function setPassword($passwd){
        $this->passwd = $passwd;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getSecondName(){
        return $this->secondName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getLogin(){
        return $this->login;
    }

    public function getPassword(){
        return $this->passwd;
    }

    public function getEmail(){
        return $this->email;
    }

    public function checkPassword($passwd, $retypepasswd, $salt){
        //проверка длины

        $len = strlen($passwd);
        if($len < 6){
           $errorMessage = " Пароль слишком короткий";
        }

        if($passwd != $retypepasswd){
            $errorMessage .= " Пароли не совпадают";
        }

        if(!empty($errorMessage)){
            return $errorMessage;
        }
        else{
            $this->setPassword(password_hash($passwd.$salt, PASSWORD_DEFAULT ));
            return true;
        }

    }

    public function checkLogin($db){
        $result = $db->query("SELECT login FROM users");
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            if($this->login == $row['login']){
                return "this login already exists";
            }
            else{
                return true;
            }
        }
    }

    public function putRegistration($salt, $db){
        $data = array('id'=>0, 'name'=>$this->firstName, 'name2'=>$this->secondName,
                      'name3'=>$this->lastName, 'birthday'=>$this->birthday,
                      'login'=>$this->login, 'passwd'=>$this->passwd, 'email'=>$this->email, 'salt'=>$salt);

        $queryRegistration = "INSERT INTO users (idUser, firstName, secondName, lastName, birthday, login, passwd, email, salt) 
                              VALUES(:id, :name, :name2, :name3, :birthday, :login, :passwd, :email, :salt)";

        $result = $db->prepare($queryRegistration);
        $result->execute($data);
return $data;
    }

    public function getEnter($db, $login, $password){
        $querySelectUser = "SELECT * FROM users WHERE login='$login'";
        $result = $db->query($querySelectUser)->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password.$result['salt'], $result['passwd'])){
            $_SESSION['id'] = $result['idUser'];
            $_SESSION['firstName'] = $result['firstName'];
            $_SESSION['secondName'] = $result['secondName'];
            $_SESSION['lastName'] = $result['lastName'];
            $_SESSION['birthday'] = $result['birthday'];
            $_SESSION['login'] = $result['login'];
            $_SESSION['email'] = $result['email'];
            header('location:'.HOST);
            return true;
        }
        else{
            return false;
        }
    }

    public function getExit(){
        session_destroy();
        header('location:'.HOST);
    }
}