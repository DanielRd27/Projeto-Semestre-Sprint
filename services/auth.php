<?php

require_once "../config/connectDB.php";

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = getConnection();
    }
    
    public function login(string $username, string $password): bool {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE username = ?");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch();

        $id_user = $usuario['id'];
        
        if ($usuario['password'] == $password) {
            $_SESSION['auth'] = [
                'logado' => true,
                'username' => $username,
                'perfil' => $usuario['perfil'],
                'user_id' => $id_user
            ];
            return true;
        }
        return false;
    }

    public function cadastrar(string $username, string $email,string $password): bool {
        $stmt = $this->db->prepare("INSERT INTO usuario  (username, email, password, perfil) 
                VALUES (?, ?, ?, ?)");
                
        $result = $stmt->execute([
                    $username,
                    $email,
                    $password,
                    'User'
                ]);

        return $result;
    }

    public function logout(): void {
        session_destroy();
    }

    public static function verificarLogin(): bool {
        return isset($_SESSION['auth']) && $_SESSION['auth']['logado'] === true;
    }
    
    public static function isPerfil(string $perfil): bool {
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
    }

    public static function isAdmin(): bool {
        return self::isPerfil('admin');
    }

    public static function getUsuario(): ?array {
        return $_SESSION['auth'] ?? null;
    }

    public static function temPermissao(string $acao): bool {
        $usuario = self::getUsuario();
        if (!$usuario) {
            return false;
        }
        
        // Matriz de permissões por perfil
        $permissoes = [
            'admin' => [
                'visualizar' => true,
                'adicionar' => true,
                'alugar' => true,
                'devolver' => true,
                'deletar' => true,
                'calcular' => true
            ],
            'usuario' => [
                'visualizar' => true,
                'adicionar' => false,
                'alugar' => true,
                'devolver' => true,
                'deletar' => false,
                'calcular' => true
            ]
            // Pode-se adicionar facilmente novos perfis, como:
            // 'gerente' => [
            //     'visualizar' => true,
            //     'adicionar' => true,
            //     'alugar' => true,
            //     'devolver' => true,
            //     'deletar' => false,
            //     'calcular' => true
            // ]
        ];
        
        // Verifica se o perfil e a ação existem na matriz
        if (!isset($permissoes[$usuario['perfil']]) || !isset($permissoes[$usuario['perfil']][$acao])) {
            return false;
        }
        
        return $permissoes[$usuario['perfil']][$acao];
    }
}