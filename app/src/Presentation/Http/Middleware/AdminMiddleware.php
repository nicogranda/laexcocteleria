<?php
namespace App\Presentation\Http\Middleware;

class AdminMiddleware
{
    public function handle()
    {
        session_status() === PHP_SESSION_NONE && session_start();

        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: index.php?page=admin&action=login');
            exit;
        }
    }
}