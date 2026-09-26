<?php
namespace App\Domains\Categories;

// Mostrar errores
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/php_errors.log');

// Incluir la clase Categories relativa a este archivo
require_once __DIR__ . '/Categories.php';

use App\Domains\Categories\Categories; 

class CategoriesController
{
    private Categories $categoriesModel;
    private string $lang;

    public function __construct(\mysqli $mysqli, string $lang = 'EN')
    {
        $this->categoriesModel = new Categories($mysqli);
        $this->lang = strtoupper($lang);
        if (!in_array($this->lang, ['EN','ES'])) $this->lang = 'EN';
    }

    /**
     * Retorna categorías activas según idioma
     */
    public function getCategories(): array
    {
        return $this->categoriesModel->getByLanguage($this->lang);
    }
}