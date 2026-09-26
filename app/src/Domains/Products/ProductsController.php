<?php
require 'auth.php';

require_once __DIR__ . '/../../models/Model.php';
require_once __DIR__ . '/../../models/admin/Product.php';
require_once __DIR__ . '/../../models/admin/Category.php';
require_once __DIR__ . '/../../models/admin/ProductVariant.php';
require_once __DIR__ . '/../../models/admin/VariantAttributes.php';
require_once __DIR__ . '/../../models/admin/ProductMedia.php';

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\VariantAttributes;
use App\Models\Admin\ProductMedia;

class ProductsController
{
    private $product;
    private $category;
    private $mysqli;
    private $variantModel;
    private $attributeModel;
    private $mediaModel;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->category = new Category();
        $this->product = new Product();
        $this->variantModel = new ProductVariant();
        $this->attributeModel = new VariantAttributes();
        $this->mediaModel = new ProductMedia();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
    
            // --- Validar y limpiar datos base ---
            $name = trim($_POST['name'] ?? '');
            $slug = strtolower(trim($name));
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
    
            $description = trim($_POST['description'] ?? '');
            $unit        = trim($_POST['unit'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            $vat_rate    = trim($_POST['vat_rate'] ?? '0');
            $language    = "EN";
            $is_active   = 1;
    
            if (empty($name) || empty($unit) || empty($category_id)) {
                echo "El nombre, unidad y categoría son obligatorios.";
                return;
            }
    
            $category = $this->category->getById($category_id);
            $categoryName = $category['name'] ?? '';
    
            // --- Guardar producto base ---
            $productData = [
                'name'        => $name,
                'slug'        => $slug,
                'description' => $description,
                'unit'        => $unit,
                'category_id' => $category_id,
                'vat_rate'    => $vat_rate,
                'language'    => $language,
                'is_active'   => $is_active,
                'user_id'     => $user_id
            ];
    
            $productId = $this->product->create($productData);
    
            // --- Procesar variantes usando createVariant() ---
            $variants      = $_POST['variants'] ?? [];
            $variantImages = $_FILES['variant_images'] ?? null;
    
            foreach ($variants as $vIndex => $variant) {
                $this->createVariant(
                    $productId,
                    $variant,
                    $variantImages,
                    $vIndex,
                    $categoryName,
                    $name,
                    $user_id
                );
            }
    
            // --- Redirigir al listado de productos ---
            header('Location: ./index.php?page=products&action=index');
            exit;
    
        } else {
            // Mostrar formulario de creación
            $categories = $this->category->getAll();
            include '../app/views/admin/products/create.php';
        }
    }

    private function createVariant(
        int $productId,
        array $variant,
        ?array $variantImages,
        int $vIndex,
        string $categoryName,
        string $name,
        int $user_id,
        ?array $imageData = null  // ← AGREGAR ESTE PARÁMETRO
    ) {
        $imageUrl = '';
        $variantSKU = trim($variant['sku'] ?? '');
        
        // Si viene imageData (desde update), usarlo
        if ($imageData) {
            $imageUrl = $imageData['url'];
            $variantSKU = $imageData['sku'];
        } 
        // Si no, procesar imagen normal (desde create)
        else {
            $firstAttributeValue = $variant['attributes'][0]['atributo_valor'] ?? $name;
            $variantImageTmp  = $variantImages['tmp_name'][$vIndex] ?? null;
            $variantImageName = $variantImages['name'][$vIndex] ?? null;
    
            if ($variantImageTmp && $variantImageName) {
                $uploadResult = $this->upload(
                    $productId,
                    $categoryName,
                    $name,
                    $firstAttributeValue,
                    $variantImageTmp,
                    $variantImageName
                );
    
                if (!empty($uploadResult['success'])) {
                    $imageUrl = $uploadResult['url'];
                    $variantSKU = $uploadResult['sku'] ?? $variantSKU;
                }
            }
        }
    
        // Preparar datos de la variante
        $variantData = [
            'product_id' => $productId,
            'sku'        => $variantSKU ?: $categoryName . '-' . $name . '-' . $vIndex,
            'price'      => floatval($variant['price'] ?? 0),
            'stock'      => intval($variant['stock'] ?? 0),
            'weight'     => floatval($variant['weight'] ?? 0),
            'width'      => isset($variant['width']) ? floatval($variant['width']) : 0,
            'height'     => isset($variant['height']) ? floatval($variant['height']) : 0,
            'length'     => isset($variant['length']) ? floatval($variant['length']) : 0,
            'image_url'  => $imageUrl,
            'is_active'  => isset($variant['is_active']) ? intval($variant['is_active']) : 1,
            'user_id'    => $user_id
        ];
    
        // Guardar variante
        $variantId = $this->variantModel->create($variantData);
    
        // Guardar atributos
        if (!empty($variant['attributes'])) {
            foreach ($variant['attributes'] as $attr) {
                $attribute = trim($attr['atributo'] ?? '');
                $attribute_value = trim($attr['atributo_valor'] ?? '');
                if ($attribute && $attribute_value) {
                    $this->attributeModel->create([
                        'variant_id'     => $variantId,
                        'attribute'      => $attribute,
                        'attribute_value'=> $attribute_value
                    ]);
                }
            }
        }
    }

    // --- Método interno para subir imágenes ---
    private function upload($productId, $categoryName, $productName, $firstAttributeValue, $tmpFile, $originalName)
    {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $logFile = $logDir . '/upload.log';
        file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Iniciando uploadInternal...\n", FILE_APPEND);
    
        $baseDir = __DIR__ . '/../../../products'; 
        $productDir = $baseDir . "/" . $productId . "/" . 'images/';
        if (!is_dir($productDir)) mkdir($productDir, 0777, true);
    
        // Generar base legible
        $nameBase = strtolower($categoryName . '-' . $productName);
        $nameBase = preg_replace('/[^a-zA-Z0-9_-]/', '-', $nameBase);
    

        // Generar 6 caracteres únicos
        $unique = substr(bin2hex(random_bytes(3)), 0, 6); // 3 bytes → 6 chars hex

        // Combinar todo y mantener extensión .png
        $newName = $nameBase . '-' . $unique . '.png';

        // Ruta final
        $destination = $productDir . DIRECTORY_SEPARATOR . $newName;


        // --- Cargar la imagen original ---
        $info = getimagesize($tmpFile);
        $imgType = $info[2];
    
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($tmpFile);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($tmpFile);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($tmpFile);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($tmpFile);
                break;
            default:
                file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Formato no soportado: {$originalName}\n", FILE_APPEND);
                return ['success' => false, 'error' => 'Formato no soportado'];
        }
    
        // --- Redimensionar a 16:9 ---
        // $maxWidth = 1920;
        // $maxHeight = 1080;
        $maxWidth = 500;
        $maxHeight = (int)($maxWidth * 16 / 9); // mantiene 9:16
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
    
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);
    
        $resized = imagecreatetruecolor($newWidth, $newHeight);
    
        // Transparencia PNG
        imagesavealpha($resized, true);
        $trans_color = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $trans_color);
    
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    
        // --- Guardar como PNG ---
        if (imagepng($resized, $destination)) {
            file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Archivo movido correctamente: {$destination}\n", FILE_APPEND);
            imagedestroy($image);
            imagedestroy($resized);
            return [
                'success' => true,
                'url'     => 'products/' . $productId . '/images/' . $newName,
                'sku'     => $nameBase
            ];
        } else {
            file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] ERROR al mover archivo: {$destination}\n", FILE_APPEND);
            imagedestroy($image);
            imagedestroy($resized);
            return ['success' => false];
        }
    }
    
   public function index()
    {
         $search = isset($_GET['search']) ? $_GET['search'] : '';
    
        // Configuración de paginación
        $productsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;  // Usamos 'currentPage' en lugar de 'page'
        $offset = ($currentPage - 1) * $productsPerPage;
        
        
        $lang = $_GET['lang'] ?? 'es'; 
        
        
        // Obtener el valor de búsqueda (si existe)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Configuración de paginación
        $productsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $productsPerPage;

        // Si hay búsqueda, usamos searchByName(); sino, getAllPaginated()
        if (!empty($search)) {
            $products = $this->product->searchByName($search);
        } else {
            $products = $this->product->getAllPaginated($productsPerPage, $offset);
        }

        // Obtener total de registros (filtrados si hay búsqueda)
        $totalProducts = $this->product->getTotal($search);
        $totalPages = ceil($totalProducts / $productsPerPage);
    
        $categories = $this->category->getAll();
        
        // Pasar datos a la vista
        include '../app/views/admin/products/index.php';
    }

    // 🔍 SEARCH: para manejar búsquedas por POST (formulario o AJAX)
public function search()
{
    $search   = $_POST['search'] ?? '';
    $category = $_POST['category'] ?? '';
    $sort     = $_POST['sort'] ?? '';

    $search = trim($search);

    // paginación
    $productsPerPage = 10;
    $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
    $offset = ($currentPage - 1) * $productsPerPage;

    $products = $this->product->searchProducts($search, $category, $sort, $productsPerPage, $offset);

    $totalProducts = $this->product->getTotalFiltered($search, $category);
    $totalPages = ceil($totalProducts / $productsPerPage);

    $categories = $this->category->getAll();

    include '../app/views/admin/products/index.php';
}
    
    public function edit(int $id)
    {
        $id = intval($id);
    
    
        $product = $this->product->getById($id);
        if (!$product) {
            echo "Producto no encontrado";
            return;
        }
    
        $categories = $this->category->getAll();
        
        $productVariants = $this->variantModel->getByColumn('product_id', $id);
    
        // Obtener atributos de cada variante
        foreach ($productVariants as &$variant) {
            $variant['attributes'] = $this->attributeModel->getByColumn('variant_id', $variant['id']);
        }
        unset($variant);
    
        // --- GALERÍA: cargar imágenes existentes ---
        $productGallery = [];
        $galleryDir = __DIR__ . '/../../../products/' . $id . '/images/';
        if (is_dir($galleryDir)) {
            foreach (scandir($galleryDir) as $file) {
                if ($file !== '.' && $file !== '..') {
                    $productGallery[] = 'products/' . $id . '/images/' . $file;
                }
            }
        }
    
        // Incluir vista
        include '../app/views/admin/products/edit.php';
    }

    
    public function update($id)
    {
        $id = (int) $id;
        
        $logFile = __DIR__ . '/../../logs/update.log';
        if (!is_dir(__DIR__ . '/../../logs')) mkdir(__DIR__ . '/../../logs', 0777, true);
        file_put_contents($logFile, "\n========== UPDATE INICIO ==========\n", FILE_APPEND);
        file_put_contents($logFile, "Product ID: {$id}\n", FILE_APPEND);
        
        $product = $this->product->getById($id);
        
        if (!$product) {
            echo "Producto no encontrado";
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->edit($id);
            return;
        }
        
        $user_id = $_SESSION['user_id'];
        
        // ===== PASO 1: ACTUALIZAR PRODUCTO =====
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $unit        = trim($_POST['unit'] ?? '');
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $vat_rate    = trim($_POST['vat_rate'] ?? '0');
        
        if ($name === '' || $unit === '' || $category_id === 0) {
            echo "El nombre, unidad y categoría son obligatorios.";
            return;
        }
        
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $category = $this->category->getById($category_id);
        if (!$category) {
            echo "Categoría inválida";
            return;
        }
        
        $categoryName = $category['name'];
        
        $productData = [
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'unit'        => $unit,
            'category_id' => $category_id,
            'vat_rate'    => $vat_rate,
            'language'    => 'EN',
            'is_active'   => 1,
            'user_id'     => $user_id
        ];
        
        $this->product->updateById($id, $productData);
        file_put_contents($logFile, "✓ Producto actualizado\n", FILE_APPEND);
        
        // ===== PASO 2: PROCESAR IMÁGENES DEL TAB MEDIA =====
        if (isset($_FILES['images']) && !empty($_FILES['images']['tmp_name'][0])) {
            file_put_contents($logFile, "Procesando imágenes del tab Media...\n", FILE_APPEND);
            $this->uploadMediaImages($id, $_FILES['images'], $categoryName, $name);
        }

        // ===== PASO 3: ACTUALIZAR/CREAR VARIANTES =====
        $variants = $_POST['variants'] ?? [];
        
        file_put_contents($logFile, "Total variantes recibidas: " . count($variants) . "\n", FILE_APPEND);
        
        foreach ($variants as $vIndex => $variant) {
            $variantId = (int) ($variant['id'] ?? 0);
            
            file_put_contents($logFile, "Variante {$vIndex}: ID={$variantId}\n", FILE_APPEND);
            
            if ($variantId > 0) {
                file_put_contents($logFile, "  → Actualizando variante {$variantId}\n", FILE_APPEND);
                $this->updateVariant($variantId, $variant, null, $categoryName, $name, $vIndex, $user_id);
                
            } else {
                file_put_contents($logFile, "  → Creando nueva variante\n", FILE_APPEND);
                $this->createVariant($id, $variant, null, $vIndex, $categoryName, $name, $user_id, null);
            }
        }
        
        file_put_contents($logFile, "========== UPDATE FIN ==========\n\n", FILE_APPEND);
        
        header('Location: ./index.php?page=products&action=index');
        exit;
    }
    

    /**
     * Método para subir imágenes y registrarlas en product_media
     */
private function uploadMediaImages(int $productId, array $images, string $categoryName, string $productName)
{
    foreach ($images['tmp_name'] as $index => $tmpFile) {
        $originalName = $images['name'][$index];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Determinar tipo
        $type = in_array($ext, ['mp4', 'webm', 'mov']) ? 'video' : 'image';

        // Directorio de almacenamiento
        $mediaDir = __DIR__ . '/../../../products/' . $productId . '/images/';
        if (!is_dir($mediaDir)) mkdir($mediaDir, 0777, true);

        // Generar nombre seguro y único
        $categorySafe = preg_replace('/[^a-z0-9]+/i', '-', strtolower($categoryName));
        $productSafe  = preg_replace('/[^a-z0-9]+/i', '-', strtolower($productName));
        $unique       = substr(bin2hex(random_bytes(3)), 0, 6);

        $newName = "{$categorySafe}-{$productSafe}-{$unique}.{$ext}";
        $destination = $mediaDir . $newName;

        if (move_uploaded_file($tmpFile, $destination)) {
            // Insertar registro en product_media
            $this->mediaModel->create([
                'type'       => $type,
                'product_id' => $productId,
                'position'   => $index + 1,
                'image_path' => "{$productId}/images/{$newName}"
            ]);
        }
    }
}

    /**
     * Sube todas las imágenes de las variantes
     */
    private function uploadImages($productId, $variants, $variantImages, $categoryName, $productName)
    {
        $results = [];
        
        // LOG
        $logFile = __DIR__ . '/../../logs/upload.log';
        if (!is_dir(__DIR__ . '/../../logs')) mkdir(__DIR__ . '/../../logs', 0777, true);
        
        file_put_contents($logFile, "\n===== UPLOAD INICIO =====\n", FILE_APPEND);
        file_put_contents($logFile, "Product ID: {$productId}\n", FILE_APPEND);
        file_put_contents($logFile, "Category: {$categoryName}\n", FILE_APPEND);
        file_put_contents($logFile, "Product: {$productName}\n", FILE_APPEND);
        
        if (!$variantImages || empty($variantImages['tmp_name'])) {
            file_put_contents($logFile, "No hay imágenes para procesar\n", FILE_APPEND);
            return $results;
        }
        
        // Crear directorios
        $baseDir = __DIR__ . '/../../../products/' . $productId;
        $imagesDir = $baseDir . '/images/';
        
        file_put_contents($logFile, "Base Dir: {$baseDir}\n", FILE_APPEND);
        file_put_contents($logFile, "Images Dir: {$imagesDir}\n", FILE_APPEND);
        
        if (!is_dir($baseDir)) {
            file_put_contents($logFile, "Creando base dir...\n", FILE_APPEND);
            if (mkdir($baseDir, 0777, true)) {
                file_put_contents($logFile, "✓ Base dir creado\n", FILE_APPEND);
            } else {
                file_put_contents($logFile, "✗ ERROR creando base dir\n", FILE_APPEND);
                return $results;
            }
        } else {
            file_put_contents($logFile, "Base dir ya existe\n", FILE_APPEND);
        }
        
        if (!is_dir($imagesDir)) {
            file_put_contents($logFile, "Creando images dir...\n", FILE_APPEND);
            if (mkdir($imagesDir, 0777, true)) {
                file_put_contents($logFile, "✓ Images dir creado\n", FILE_APPEND);
            } else {
                file_put_contents($logFile, "✗ ERROR creando images dir\n", FILE_APPEND);
                return $results;
            }
        } else {
            file_put_contents($logFile, "Images dir ya existe\n", FILE_APPEND);
        }
        
        // Verificar permisos
        file_put_contents($logFile, "Permisos imagesDir: " . decoct(fileperms($imagesDir) & 0777) . "\n", FILE_APPEND);
        file_put_contents($logFile, "Es escribible: " . (is_writable($imagesDir) ? 'SÍ' : 'NO') . "\n", FILE_APPEND);
        
        // Procesar cada imagen
        foreach ($variants as $vIndex => $variant) {
            file_put_contents($logFile, "\n--- Variante {$vIndex} ---\n", FILE_APPEND);
            
            $tmpFile = $variantImages['tmp_name'][$vIndex] ?? null;
            $fileName = $variantImages['name'][$vIndex] ?? null;
            
            file_put_contents($logFile, "tmpFile: {$tmpFile}\n", FILE_APPEND);
            file_put_contents($logFile, "fileName: {$fileName}\n", FILE_APPEND);
            
            if (!$tmpFile || !$fileName) {
                file_put_contents($logFile, "Sin archivo\n", FILE_APPEND);
                continue;
            }
            
            if (!is_uploaded_file($tmpFile)) {
                file_put_contents($logFile, "No es archivo subido válido\n", FILE_APPEND);
                continue;
            }
            
            file_put_contents($logFile, "✓ Archivo válido\n", FILE_APPEND);
            
            // Obtener atributo para nombre
            $attrValue = $variant['attributes'][0]['atributo_valor'] ?? $productName;
            
            // Generar nombre
            $nameBase = strtolower($categoryName . '-' . $productName . '-' . $attrValue);
            $nameBase = preg_replace('/[^a-zA-Z0-9_-]/', '-', $nameBase);
            $nameBase = trim($nameBase, '-');
            $unique = substr(bin2hex(random_bytes(3)), 0, 6);
            $newName = $nameBase . '-' . $unique . '.png';
            $destination = $imagesDir . $newName;
            
            file_put_contents($logFile, "Destino: {$destination}\n", FILE_APPEND);
            
            // Cargar imagen
            $info = @getimagesize($tmpFile);
            if (!$info) {
                file_put_contents($logFile, "✗ No se pudo leer imagen\n", FILE_APPEND);
                continue;
            }
            
            file_put_contents($logFile, "Tipo imagen: {$info[2]}\n", FILE_APPEND);
            
            $imgType = $info[2];
            $image = null;
            
            switch ($imgType) {
                case IMAGETYPE_JPEG: 
                    $image = @imagecreatefromjpeg($tmpFile); 
                    file_put_contents($logFile, "Cargando como JPEG\n", FILE_APPEND);
                    break;
                case IMAGETYPE_PNG:  
                    $image = @imagecreatefrompng($tmpFile); 
                    file_put_contents($logFile, "Cargando como PNG\n", FILE_APPEND);
                    break;
                case IMAGETYPE_GIF:  
                    $image = @imagecreatefromgif($tmpFile); 
                    file_put_contents($logFile, "Cargando como GIF\n", FILE_APPEND);
                    break;
                case IMAGETYPE_WEBP: 
                    $image = @imagecreatefromwebp($tmpFile); 
                    file_put_contents($logFile, "Cargando como WEBP\n", FILE_APPEND);
                    break;
                default:
                    file_put_contents($logFile, "✗ Formato no soportado\n", FILE_APPEND);
                    continue 2;
            }
            
            if (!$image) {
                file_put_contents($logFile, "✗ No se pudo crear recurso de imagen\n", FILE_APPEND);
                continue;
            }
            
            file_put_contents($logFile, "✓ Imagen cargada\n", FILE_APPEND);
            
            // Redimensionar
            $maxWidth = 500;
            $maxHeight = (int)($maxWidth * 9 / 16);
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth = (int)($origWidth * $ratio);
            $newHeight = (int)($origHeight * $ratio);
            
            file_put_contents($logFile, "Redimensionando {$origWidth}x{$origHeight} → {$newWidth}x{$newHeight}\n", FILE_APPEND);
            
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagesavealpha($resized, true);
            $trans = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $trans);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            
            // Guardar
            file_put_contents($logFile, "Guardando...\n", FILE_APPEND);
            
            if (@imagepng($resized, $destination)) {
                $results[$vIndex] = [
                    'url' => 'products/' . $productId . '/images/' . $newName,
                    'sku' => strtoupper($categoryName) . '-' . strtoupper($productName) . '-' . $unique
                ];
                file_put_contents($logFile, "✓✓✓ GUARDADA: {$newName}\n", FILE_APPEND);
                file_put_contents($logFile, "URL: {$results[$vIndex]['url']}\n", FILE_APPEND);
                file_put_contents($logFile, "SKU: {$results[$vIndex]['sku']}\n", FILE_APPEND);
            } else {
                file_put_contents($logFile, "✗✗✗ ERROR al guardar\n", FILE_APPEND);
                file_put_contents($logFile, "Error: " . error_get_last()['message'] . "\n", FILE_APPEND);
            }
            
            imagedestroy($image);
            imagedestroy($resized);
        }
        
        file_put_contents($logFile, "\nTotal procesadas: " . count($results) . "\n", FILE_APPEND);
        file_put_contents($logFile, "===== UPLOAD FIN =====\n\n", FILE_APPEND);
        
        return $results;
    }


    /**
     * Actualiza UNA variante existente
     */
    private function updateVariant($variantId, $variant, $imageData, $categoryName, $productName, $vIndex, $userId)
    {
        $logFile = __DIR__ . '/../../logs/update.log';
        
        file_put_contents($logFile, "\n  updateVariant() para ID={$variantId}\n", FILE_APPEND);
        
        $imageUrl = $variant['image_url'] ?? 'products/default.png';
        $variantSKU = trim($variant['sku'] ?? '');
        
        file_put_contents($logFile, "  Imagen actual: {$imageUrl}\n", FILE_APPEND);
        
        // Si hay nueva imagen, usarla
        if ($imageData && !empty($imageData['url'])) {
            file_put_contents($logFile, "  Nueva imagen: {$imageData['url']}\n", FILE_APPEND);
            
            // Borrar imagen anterior
            if ($imageUrl !== 'products/default.png') {
                $oldPath = __DIR__ . '/../../../' . $imageUrl;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                    file_put_contents($logFile, "  ✓ Imagen anterior borrada\n", FILE_APPEND);
                }
            }
            $imageUrl = $imageData['url'];
            $variantSKU = $imageData['sku'];
        } else {
            file_put_contents($logFile, "  Sin nueva imagen\n", FILE_APPEND);
        }
        
        // Actualizar variante
        $variantData = [
            'sku'        => $variantSKU ?: $categoryName . '-' . $productName . '-' . $vIndex,
            'price'      => (float) ($variant['price'] ?? 0),
            'stock'      => (int) ($variant['stock'] ?? 0),
            'weight'     => $variant['weight'] !== '' ? (float) $variant['weight'] : null,
            'width'      => $variant['width'] !== '' ? (float) $variant['width'] : null,
            'height'     => $variant['height'] !== '' ? (float) $variant['height'] : null,
            'length'     => $variant['length'] !== '' ? (float) $variant['length'] : null,
            'image_url'  => $imageUrl,
            'is_active'  => isset($variant['is_active']) ? (int) $variant['is_active'] : 1,
            'user_id'    => $userId
        ];
        
        file_put_contents($logFile, "  Datos a actualizar: " . json_encode($variantData) . "\n", FILE_APPEND);
        
        // $this->variantModel->update($variantId, $variantData);
        $this->variantModel->updateById($variantId, $variantData);
        
        file_put_contents($logFile, "  ✓ Variante actualizada\n", FILE_APPEND);
        
        // Actualizar atributos
        // $this->attributeModel->deleteByVariantId($variantId);
        $this->attributeModel->deleteByColumn('variant_id', $variantId);
        
        file_put_contents($logFile, "  ✓ Atributos anteriores borrados\n", FILE_APPEND);
        
        if (!empty($variant['attributes']) && is_array($variant['attributes'])) {
            foreach ($variant['attributes'] as $attr) {
                $name = trim($attr['atributo'] ?? '');
                $value = trim($attr['atributo_valor'] ?? '');
                
                if ($name !== '' && $value !== '') {
                    $this->attributeModel->create([
                        'variant_id'      => $variantId,
                        'attribute'       => $name,
                        'attribute_value' => $value
                    ]);
                    file_put_contents($logFile, "  ✓ Atributo creado: {$name} = {$value}\n", FILE_APPEND);
                }
            }
        }
    }

    public function delete($id)
    {
        $id = (int) $id;
    
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID de producto inválido');
        }
    
        // --- Borrar producto y sus relaciones ---
        $this->product->deleteItem($id);
    
        // --- Borrar carpeta de uploads ---
        $productDir = __DIR__ . '/../../../products/' . $id;
        if (is_dir($productDir)) {
            $this->deleteDir($productDir); // Debe borrar todo recursivamente
        }
    
        // --- Redirigir ---
        header('Location: ./index.php?page=products&action=index');
        exit;
    }

    // --- Método recursivo para borrar carpetas y archivos ---
    private function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) return;
    
        $items = scandir($dirPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dirPath . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->deleteDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dirPath);
    }



}


