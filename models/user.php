<?php



class User {
    private string $username;
    private string $password;
    private string $perfil;

    public function __contruct (string $username, string $password, string $perfil){
        $this->username = $username;
        $this->password = $password;
        $this->perfil = $perfil;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPerfil() {
        return $this->perfil;
    }

    public function getHashPassword() {
        return $this->password;
    }
}