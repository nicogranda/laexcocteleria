<?php $page = "products"; ?>
<main>
<div class="search-bar">

    <?php if ($totalPages > 1): ?>
        <?php if ($currentPage > 1): ?>
            <a href="index.php?page=<?= $page ?>&action=index&currentPage=<?= $currentPage - 1 ?>">&lt;&lt;</a>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="index.php?page=<?= $page ?>&action=index&currentPage=<?= $currentPage + 1 ?>">&gt;&gt;</a>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="index.php?page=products&action=search">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="action" value="search">

        <!-- Búsqueda -->
        <input 
            type="text" 
            name="search" 
            placeholder="Buscar producto" 
            value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8') : '' ?>">

        <!-- Filtro por categoría -->
        <select name="category">
            <option value="">Todas las categorías</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category']) && $_POST['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= ucfirst($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Orden alfabético -->
        <select name="sort">
            <option value="">Ordenar por</option>
            <option value="asc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'asc') ? 'selected' : '' ?>>A-Z</option>
            <option value="desc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'desc') ? 'selected' : '' ?>>Z-A</option>
        </select>

        <button type="submit">Buscar</button>
    </form>

    <a href="index.php?page=<?= $page ?>&action=create">+</a>
</div>

<table class="container-form">
 
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Product</th>
            <th>Price</th>
            <th>Action</th>
            <th>Hidden</th>
        </tr>
  
<?php foreach ($products as $product): ?>
<tr>
    <td style="text-align: right;">
     
        <a href="index.php?page=<?= $page ?>&action=edit&id=<?= $product['id'] ?>&currentPage=<?= $currentPage ?>">
            <?= $product['id'] ?>
        </a>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo date('Y-m-d', strtotime($product['created_at'])); ?>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo $product['name']; ?>
    </td>
    <td style="padding: 0 0 0 10px; text-align: right;">
        <?php// echo number_format($product['unit_value'], 2); ?>
    </td>
    <td>  
        <a href="javascript:void(0);" class="delete-btn" data-id="<?php echo $product['id']; ?>">
            <i class="fas fa-trash" title="Delete" alt="Delete"></i>
        </a>
    </td>
    <td>
        <label class="switch">
        <input 
            type="checkbox" 
            data-id="<?= $product['id'] ?>" 
            class="toggle-hidden" 
            <?= $product['is_active'] == 1 ? 'checked' : '' ?>
        >
        <span class="slider"></span>
        </label>
    </td>
</tr>
<?php endforeach; ?>
   
</table>


<!-- Modal de Confirmación -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de que deseas eliminar este producto?</p>
        <button id="confirmDelete">Sí, eliminar</button>
        <button id="cancelDelete">Cancelar</button>
    </div>
</div>
</main>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let deleteId = null;
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");

    // Mostrar modal al hacer clic en "Delete"
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            deleteId = this.getAttribute("data-id");
            modal.style.display = "block";
        });
    });

    // Confirmar eliminación
    confirmBtn.addEventListener("click", function () {
        if (deleteId) {
            window.location.href = `index.php?page=<?php echo $page; ?>&action=delete&id=${deleteId}`;
        }
    });

    // Cancelar eliminación
    cancelBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
// Switch
document.querySelectorAll('.toggle-hidden').forEach(button => {
    button.addEventListener('change', () => {
        const productId = button.getAttribute('data-id');  // corregido
      
        fetch(`index.php?page=products&action=toggle-hidden&id=${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + productId
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
        });
    });
});
</script>

<style>
.product-img {
    width: 100px;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    object-position: center;
    paddding: 10px;
    display: block;
}

.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 26px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0;
  right: 0; bottom: 0;
  background-color: #f44336;
  transition: 0.4s;
  border-radius: 26px;
}

.slider::before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #4CAF50;
}

input:checked + .slider::before {
  transform: translateX(24px);
}

main {
    margin: 0 auto; /* Elimina m��rgenes */
    padding: 0; /* Elimina padding */
}

.container {
    width: 40%;
    margin: 0 auto; /* Elimina m��rgenes */
    padding: 0; /* Elimina padding */
}

 .pagination {
            text-align: right;
            margin: 20px;
        }
        .pagination a {
            text-decoration: none;
            color: var(--color-primary, #F15A24);
            padding: 5px 10px;
            border: 1px solid var(--color-primary, #F15A24);
            margin: 0 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .pagination a:hover {
            background-color: var(--color-secondary, #8DC63F);
            color: white;
        }
        .pagination span {
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
            background-color: var(--color-primary, #F15A24);
            color: white;
        }

/*Forms*/
/* .container-form {
    margin: 0 auto;
    width: 80%;
    display: flex;
    flex-direction: column;
    align-items: center;
     max-width: 700px;  /*Limita el ancho m��ximo  */
   /* padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}  */

.input-field {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn-submit {
    width: 100%;
    background-color: var(--color-primary); 
    border-radius: 8px;
    color: white;
    border: none;
    height: 40px;
}

.btn-submit hover{
    background-color: var(--color-secondary); 
}

.button-principal {
    width: 100%;
    background-color: var(--color-primary);
}

        table {
            margin: 0 auto; /* Centra la tabla horizontalmente */
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
            display: flex;
        }
        .button-container button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            background-color: var(--color-primary, #F15A24);
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }
        .button-container button:hover {
            background-color: var(--color-secondary, #8DC63F);
        }

/*Search Bar*/
.search-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 15px;
    padding: 10px;
    /*background-color: var(--background-light-primary, rgba(241, 90, 36, 0.1));*/
    border-radius: 8px;
}

.search-bar a {
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    color: var(--color-primary, #F15A24);
    padding: 0 15px;
    border: 1px solid var(--color-primary, #F15A24);
    border-radius: 5px;
    transition: background 0.3s ease;
    height: 42px;
    line-height: 1;
}

.search-bar a:hover {
    background-color: var(--color-primary, #F15A24);
    color: #fff;
}

.search-bar form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.search-bar input, .search-bar select, .search-bar button {
    padding: 0 10px 0 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    height: 42px;
    line-height: 1;
    display: flex;
    align-items: center;
}

.search-bar input {
    flex-grow: 1;
    min-width: 180px;
}

.search-bar button {
    background-color: var(--color-primary, #F15A24);
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
    height: 42px;
    line-height: 1;
}

.search-bar button:hover {
    background-color: var(--color-secondary, #8DC63F);
}

/*Modal*/
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
}

.modal-content button {
    margin: 5px;
    padding: 10px;
    border: none;
    cursor: pointer;

}

#confirmDelete {
    /*background: var(--color-secondary);*/
    /*color: white;*/

    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    color: var(--color-primary, #F15A24);
    padding: 0 15px;
    border: 1px solid var(--color-primary, #F15A24);
    border-radius: 5px;
    transition: background 0.3s ease;
    height: 42px;
    line-height: 1;
}

#cancelDelete {
    /*background: gray;*/
    /*color: white;*/
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    color: var(--color-primary, #F15A24);
    padding: 0 15px;
    border: 1px solid var(--color-primary, #F15A24);
    border-radius: 5px;
    transition: background 0.3s ease;
    height: 42px;
    line-height: 1;
       
}
@media only screen and (max-width: 800px) {
    main {
        width:95%;
    }
}
    

</style>


