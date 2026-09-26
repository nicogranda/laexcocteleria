<?php
// app/src/Domains/Admin/AdminUser.php
namespace App\Domains\Admin;

class AdminUser
{
    private $mysqli;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function findAdminByUsernameOrEmail($usernameOrEmail)
    {
        $stmt = $this->mysqli->prepare(
            "SELECT * FROM users 
             WHERE (username = ? OR email = ?) 
             AND role = 'admin' 
             LIMIT 1"
        );
        
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user;
    }
    
    public function findAdminByEmail($email)
    {
        $stmt = $this->mysqli->prepare(
            "SELECT * FROM users 
             WHERE email = ? AND role = 'admin' 
             LIMIT 1"
        );
        
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user;
    }
    
    public function findOrCreateFromGoogle($googleUser)
    {
        // Buscar existente
        $existing = $this->findAdminByEmail($googleUser['email']);
        
        if ($existing) {
            return $existing;
        }
        
        // Crear nuevo
        $stmt = $this->mysqli->prepare(
            "INSERT INTO users 
            (username, name, lastname, email, password, role, provider, provider_id, created_at, update_at) 
            VALUES (?, ?, ?, ?, ?, 'admin', 'google', ?, NOW(), NOW())"
        );
        
        $username = explode('@', $googleUser['email'])[0];
        $name = $googleUser['given_name'] ?? $googleUser['name'] ?? '';
        $lastname = $googleUser['family_name'] ?? '';
        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $providerId = $googleUser['id'] ?? 0;
        
        $stmt->bind_param('sssssi', $username, $name, $lastname, $googleUser['email'], $password, $providerId);
        $stmt->execute();
        
        $userId = $this->mysqli->insert_id;
        $stmt->close();
        
        return $this->findAdminById($userId);
    }
    
    public function findAdminById($id)
    {
        $stmt = $this->mysqli->prepare(
            "SELECT * FROM users WHERE id = ? AND role = 'admin' LIMIT 1"
        );
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user;
    }
}