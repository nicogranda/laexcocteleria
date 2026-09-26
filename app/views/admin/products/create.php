<!-- Incluye Dropzone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<main class="container-form">

<form id="productForm" method="POST" action="" enctype="multipart/form-data">

  <!-- IDENTIFICACIÓN DEL PRODUCTO -->
  <fieldset>
    <legend>Identificación del Producto</legend>
    <input type="hidden" name="language" value="EN">
    
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" required class="input-field"><br>
    
    <label for="category_id">Categoría:</label>
    <select id="category_id" name="category_id" required class="input-field">
      <option value="">Selecciona una categoría</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select><br>
    
    <label for="unit">Unidad:</label>
    <input type="text" id="unit" name="unit" value="pza" required class="input-field"><br>
    
    <label for="description">Descripción:</label>
    <textarea id="description" name="description" class="input-field"></textarea><br>
  </fieldset>

  <!-- VARIANTES -->
  <fieldset>
    <legend>Variantes</legend>
    <div id="variantsContainer" class="variants-flex">

<div class="variant">
  <input type="number" name="variants[0][price]" placeholder="Precio" step="0.01" required>
  <input type="number" name="variants[0][stock]" placeholder="Cantidad" required>
  <input type="number" name="variants[0][weight]" placeholder="Peso" step="0.01">
  <input type="number" name="variants[0][width]" placeholder="Ancho" step="0.01">
  <input type="number" name="variants[0][height]" placeholder="Alto" step="0.01">
  <input type="number" name="variants[0][lenght]" placeholder="Largo" step="0.01">
  <input type="file" name="variant_images[0]" accept="image/*" required>

  <div class="variant-attributes">
    <input type="text" name="variants[0][attributes][0][atributo]" placeholder="Atributo" required>
    <input type="text" name="variants[0][attributes][0][atributo_valor]" placeholder="Valor" required>
  </div>

  <!-- Botones juntos -->
  <div class="variant-buttons">
    <button type="button" class="add-btn">
      <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
    <button type="button" class="removeVariant">
      <i class="fa fa-trash" aria-hidden="true"></i>
    </button>
  </div>
</div>


    </div>

    <!--<button type="button" id="addVariant" class="add-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>-->
  </fieldset>

  <!-- DROPZONE PARA GALERÍA -->

  <button type="submit" class="btn-submit">Crear Producto</button>
</form>

</main>

<style>

</style>

<script>
let variantIndex = 1;

document.getElementById('addVariant').addEventListener('click', () => {
  const container = document.getElementById('variantsContainer');

  const div = document.createElement('div');
  div.classList.add('variant');

  div.innerHTML = `
    <input type="number" name="variants[${variantIndex}][price]" placeholder="Precio" step="0.01" required>
    <input type="number" name="variants[${variantIndex}][stock]" placeholder="Cantidad" required>
    <input type="number" name="variants[${variantIndex}][weight]" placeholder="Peso" step="0.01">
    <input type="file" name="variant_images[${variantIndex}]" accept="image/*" required>

    <div class="variant-attributes">
      <div class="attribute">
        <input type="text" name="variants[${variantIndex}][attributes][0][atributo]" placeholder="Atributo (ej: color)" required>
        <input type="text" name="variants[${variantIndex}][attributes][0][atributo_valor]" placeholder="Valor (ej: Rojo)" required>
      </div>
    </div>

    <button type="button" class="removeVariant">Eliminar</button>
  `;

  container.appendChild(div);
  variantIndex++;
});

document.getElementById('variantsContainer').addEventListener('click', e => {
  if (e.target.classList.contains('removeVariant')) {
    e.target.closest('.variant').remove();
  }
});

Dropzone.autoDiscover = false;
const dropzoneElement = document.querySelector("#dropzone");

const myDropzone = new Dropzone(dropzoneElement, {
  url: "#",
  autoProcessQueue: false,
  uploadMultiple: true,
  parallelUploads: 10,
  addRemoveLinks: true,
  maxFilesize: 5,
  acceptedFiles: "image/*",
});

// Enviar formulario
const form = document.getElementById("productForm");
form.addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(form);

  // Agregar galería
  myDropzone.files.forEach((file) => {
    formData.append('gallery_images[]', file, file.name);
  });

  fetch(form.action || "index.php?page=products&action=create", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    console.log(data);
    window.location.href = "./index.php?page=products&action=index";
  })
  .catch(err => console.error(err));
});
</script>
