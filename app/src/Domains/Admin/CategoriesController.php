<?php
require 'auth.php';
require_once '../app/models/Model.php';
require_once '../app/models/admin/Category.php';
require_once '../app/models/admin/CategoryMedia.php'; // ✅ nuevo modelo para la media

use App\Models\Admin\Category;
use App\Models\Admin\CategoryMedia;

class CategoriesController
{
    private $categoryModel;
    private $categoryMediaModel;
    private $mysqli;

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
        $this->categoryModel = new Category();
        $this->categoryMediaModel = new CategoryMedia();
    }

    // ✅ CREATE (guarda categoría y opcionalmente imagen en category_media)
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $language    = "EN";

            if (empty($name)) {
                echo "The category name is required.";
                return;
            }

            // --- Guardar categoría principal ---
            $categoryData = [
                'name'        => $name,
                'description' => $description,
                'language'    => $language,
                'user_id'     => $user_id
            ];

        // var_dump($categoryData);
        // exit;
            $categoryId = $this->categoryModel->create($categoryData);
        
            if (!$categoryId) {
                echo "Error while saving the category.";
                return;
            }

            // --- Procesar imagen si existe ---
            if (!empty($_FILES['image_file']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileTmp  = $_FILES['image_file']['tmp_name'];
                $fileName = time() . '_' . basename($_FILES['image_file']['name']);
                $target   = $uploadDir . $fileName;

                if (move_uploaded_file($fileTmp, $target)) {
                    $filePath = 'uploads/categories/' . $fileName; // ruta relativa

                    // --- Guardar registro en category_media ---
                    $mediaData = [
                        'category_id' => $categoryId,
                        'file_path'   => $filePath,
                        'file_type'   => 'image',
                        'language'    => $language,
                        'is_primary'  => 1,
                        'user_id'     => $user_id
                    ];
                    $this->categoryMediaModel->create($mediaData);
                } else {
                    echo "Error uploading image.";
                }
            }

            header('Location: ./index.php?page=categories&action=index');
            exit;
        } else {
            include '../app/views/admin/categories/create.php';
        }
    }

    // ✅ INDEX (con búsqueda y paginación)
    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoriesPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $categoriesPerPage;

        if (!empty($search)) {
            $categories = $this->categoryModel->searchByName($search);
            $totalCategories = count($categories);
        } else {
            $categories = $this->categoryModel->getAllPaginated($categoriesPerPage, $offset);
            $totalCategories = $this->categoryModel->getTotal();
        }

        $totalPages = ceil($totalCategories / $categoriesPerPage);

        include '../app/views/admin/categories/index.php';
    }

    // ✅ SEARCH (idéntico comportamiento que index, pero con POST)
    public function search()
    {
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        $categoriesPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $categoriesPerPage;

        if (!empty($search)) {
            $categories = $this->categoryModel->searchByName($search);
            $totalCategories = count($categories);
        } else {
            $categories = $this->categoryModel->getAllPaginated($categoriesPerPage, $offset);
            $totalCategories = $this->categoryModel->getTotal();
        }

        $totalPages = ceil($totalCategories / $categoriesPerPage);

        include '../../app/views/admin/categories/index.php';
    }

    // ✅ SHOW
    public function show($id)
    {
        $category = $this->categoryModel->find($id);
        include '../app/views/admin/categories/show.php';
    }

    // ✅ EDIT
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $language    = "EN";

            if (empty($name)) {
                echo "The category name is required.";
                return;
            }

            $this->categoryModel->update($id, [
                'name'        => $name,
                'description' => $description,
                'language'    => $language
            ]);

            header('Location: ./index.php?page=categories&action=index');
            exit;
        }

        include '../app/views/admin/categories/edit.php';
    }

    // ✅ DELETE
    public function delete($id)
    {
        $id = intval($id);
        $this->categoryModel->delete($id);
        header('Location: ./index.php?page=categories&action=index');
        exit;
    }
}
?>
