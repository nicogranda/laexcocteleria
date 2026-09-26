<?php
// app/src/Domains/Admin/AdminAuthController.php
namespace App\Domains\Admin;

require_once __DIR__ . '/AdminUser.php';

class AdminAuthController
{
    private $baseUrl;
    private $mysqli;
    
    public function __construct($baseUrl, $mysqli = null)
    {
        $this->baseUrl = $baseUrl;
        $this->mysqli = $mysqli;
    }
    
    /**
     * Mostrar formulario de login
     * URL: index.php?page=admin&action=login
     */
    public function showLoginForm()
    {
        // Si ya est�� logueado, redirigir
        if ($this->isLoggedIn()) {
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }
        
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../../../views/pages/admin/login.php';
    }
    
    /**
     * Procesar login POST
     * URL: index.php?page=admin&action=auth
     */
    public function handleAuth()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Usuario y contrase�0�9a son requeridos';
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        // Buscar usuario admin
        $adminUser = new AdminUser($this->mysqli);
        $user = $adminUser->findAdminByUsernameOrEmail($username);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuario no encontrado o no tiene permisos de administrador';
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        // Verificar contrase�0�9a
        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Contrase�0�9a incorrecta';
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        // Crear sesi��n
        $this->createAdminSession($user);
        
        // Log
        $this->logActivity($user['id'], 'login');
        
        // Redirigir al dashboard
        header('Location: index.php?page=admin&action=dashboard');
        exit;
    }
    
    /**
     * Google OAuth callback
     * URL: index.php?page=admin&action=google-callback
     */
    public function handleGoogleCallback()
    {
        if (!isset($_SESSION['google_user'])) {
            $_SESSION['error'] = 'Error en autenticaci��n con Google';
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        $googleUser = $_SESSION['google_user'];
        
        $adminUser = new AdminUser($this->mysqli);
        $user = $adminUser->findOrCreateFromGoogle($googleUser);
        
        if (!$user) {
            $_SESSION['error'] = 'No se pudo crear usuario administrador';
            header('Location: index.php?page=admin&action=login');
            exit;
        }
        
        $this->createAdminSession($user);
        $this->logActivity($user['id'], 'login_google');
        
        unset($_SESSION['google_user']);
        
        header('Location: index.php?page=admin&action=dashboard');
        exit;
    }
    
    /**
     * Logout
     * URL: index.php?page=admin&action=logout
     */
    public function handleLogout()
    {
        if (isset($_SESSION['admin_user_id'])) {
            $this->logActivity($_SESSION['admin_user_id'], 'logout');
        }
        
        // Limpiar sesi��n
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        
        header('Location: index.php?page=admin&action=login');
        exit;
    }
    
    /**
     * Dashboard
     * URL: index.php?page=admin&action=dashboard
     */
    public function showDashboard()
    {
        $baseUrl = $this->baseUrl;
        $adminName = $_SESSION['admin_name'] ?? 'Admin';
        
        require __DIR__ . '/../../../views/pages/admin/dashboard.php';
    }
    
    // ========== M�0�7TODOS PRIVADOS ==========
    
    private function isLoggedIn()
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    private function createAdminSession($user)
    {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_lastname'] = $user['lastname'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_login_time'] = time();
    }
    
    private function logActivity($userId, $action)
    {
        try {
            $stmt = $this->mysqli->prepare(
                "INSERT INTO admin_activity_logs 
                (user_id, action, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, NOW())"
            );
            
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $stmt->bind_param('isss', $userId, $action, $ip, $userAgent);
            $stmt->execute();
            $stmt->close();
        } catch (\Exception $e) {
            error_log("Admin log error: " . $e->getMessage());
        }
    }
}