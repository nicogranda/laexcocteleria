<section class="container">
<form id="productForm" method="POST" action="index.php?page=products&action=update&id=<?= $product['id'] ?>" enctype="multipart/form-data">

  <!-- Cambiar idioma -->
    <div class="language-switcher" style="margin-bottom:15px;">
      <button type="button" class="lang-btn" data-lang="es">ES</button>
      <button type="button" class="lang-btn" data-lang="en">EN</button>
      <button type="button" class="lang-btn" data-lang="fr">FR</button>
    </div>

  <!-- Tabs principales -->
  <div class="tabs">
    <button type="button" class="tab-btn active" data-tab="general">General</button>
    <button type="button" class="tab-btn" data-tab="variants">Variants</button>
    <button type="button" class="tab-btn" data-tab="media">Media</button>
  </div>

  <!-- TAB GENERAL -->
  <div class="tab-content active" id="general">
    <h3>Información General</h3><?= 'ID: '.$product['id'];?><br>
    
    <label>Nombre</label>
    <input type="text" name="name" required class="input-field" value="<?= htmlspecialchars($product['name']) ?>">

    <label>Categoría</label>
    <select name="category_id" required class="input-field">
      <option value="">Selecciona categoría</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $cat['id']==$product['category_id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Unidad comentable si quieres desactivarlo -->
    <label>Unidad</label>
    <input type="text" name="unit" class="input-field" value="<?= htmlspecialchars($product['unit']) ?>">

     <label>Slug</label>
    <input type="text" name="slug" class="input-field" value="<?= htmlspecialchars($product['slug']) ?>">
    
    <label>Short Description</label>
    <textarea name="short_description" class="input-field"><?= htmlspecialchars($product['short_description'] ?? '') ?></textarea>

    <label>Descripción</label>
    <textarea name="description" class="input-field" id="description"><?= htmlspecialchars($product['description']) ?></textarea>

    <button type="button" class="btn-ai" id="generateAI">Optimizar/Traducir con AI</button><br><br>

    <label>SKU</label>
    <input type="text" name="sku" class="input-field" value="<?= htmlspecialchars($product['sku'] ?? '') ?>">

    <label>Status</label>
    <select name="status" class="input-field">
      <option value="draft" <?= ($product['status']??'')=='draft'?'selected':'' ?>>Draft</option>
      <option value="active" <?= ($product['status']??'')=='active'?'selected':'' ?>>Active</option>
      <option value="archived" <?= ($product['status']??'')=='archived'?'selected':'' ?>>Archived</option>
    </select>

    <label>Meta Title</label>
    <input type="text" name="meta_title" class="input-field" value="<?= htmlspecialchars($product['meta_title'] ?? '') ?>">

    <label>Meta Description</label>
    <textarea name="meta_description" class="input-field"><?= htmlspecialchars($product['meta_description'] ?? '') ?></textarea>
    
    <input type="hidden" name="lang" id="currentLang" value="<?= $lang ?>">
    <input type="hidden" name="currentPage" value="<?= $_GET['currentPage'] ?? 1 ?>">
  </div>

  <!-- TAB VARIANTS -->
  <div class="tab-content" id="variants">
    <h3>Variantes del Producto</h3>
    <div id="variantsContainer" class="variants-flex">
      <?php foreach($productVariants as $vIndex => $variant): ?>
      <div class="variant" data-index="<?= $vIndex ?>">
        <h4>Variant <?= $vIndex + 1 ?></h4>
        <input type="hidden" name="variants[<?= $vIndex ?>][id]" value="<?= $variant['id'] ?>">
        <label>Precio:</label>
        <input type="number" name="variants[<?= $vIndex ?>][price]" value="<?= $variant['price'] ?>" step="0.01" required>
        <label>Stock:</label>
        <input type="number" name="variants[<?= $vIndex ?>][stock]" value="<?= $variant['stock'] ?>" required>
        <label>Peso:</label>
        <input type="number" name="variants[<?= $vIndex ?>][weight]" value="<?= $variant['weight'] ?>" step="0.01">
        <label>Ancho:</label>
        <input type="number" name="variants[<?= $vIndex ?>][width]" value="<?= $variant['width'] ?>" step="0.01">
        <label>Alto:</label>
        <input type="number" name="variants[<?= $vIndex ?>][height]" value="<?= $variant['height'] ?>" step="0.01">
        <label>Largo:</label>
        <input type="number" name="variants[<?= $vIndex ?>][length]" value="<?= $variant['length'] ?>" step="0.01">
        <label>Imagen (path)</label>
        <input type="text" name="variants[<?= $vIndex ?>][image_path]" class="input-field" value="<?= htmlspecialchars($variant['image_path'] ?? '') ?>">

        <div class="variant-attributes">
          <?php foreach($variant['attributes'] as $aIndex => $attr): ?>
            <input type="text" name="variants[<?= $vIndex ?>][attributes][<?= $aIndex ?>][atributo]" value="<?= htmlspecialchars($attr['attribute']) ?>" required>
            <input type="text" name="variants[<?= $vIndex ?>][attributes][<?= $aIndex ?>][atributo_valor]" value="<?= htmlspecialchars($attr['attribute_value']) ?>" required>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <button type="button" id="addVariant" class="btn-ai">+ Añadir Variant</button>
  </div>

  <!-- TAB MEDIA -->
  <div class="tab-content" id="media">
    <h3>Medios</h3>
    <div class="subtabs">
      <button type="button" class="subtab-btn active" data-subtab="images">Images</button>
      <button type="button" class="subtab-btn" data-subtab="videos">Videos</button>
      <button type="button" class="subtab-btn" data-subtab="documents">Documents</button>
    </div>

    <!-- Images -->
    <div class="subtab-content active" id="images">
      <div class="dropzone" id="imagesDropzone">Arrastra imágenes aquí o haz click</div>
      <input type="file" id="imagesInput" name="images[]" accept="image/*" multiple style="display:none;">
      <div id="imagesPreview" class="preview-grid"></div>
    </div>

    <!-- Videos -->
    <div class="subtab-content" id="videos">
      <div class="dropzone" id="videosDropzone">Arrastra videos aquí o haz click</div>
      <input type="file" id="videosInput" name="videos[]" accept="video/*" multiple style="display:none;">
      <div id="videosPreview" class="preview-grid"></div>
    </div>

    <!-- Documents -->
    <div class="subtab-content" id="documents">
      <div class="dropzone" id="docsDropzone">Arrastra documentos aquí o haz click</div>
      <input type="file" id="docsInput" name="documents[]" accept=".pdf,.doc,.docx,.txt" multiple style="display:none;">
      <div id="docsPreview" class="preview-grid"></div>
    </div>
  </div>

  <!-- SUBMIT -->
  <div class="form-footer">
    <button type="submit" class="btn-submit">Actualizar Producto</button>
  </div>

</form>
</section>

<style>
.tabs { display:flex; border-bottom:2px solid #ddd; margin-bottom:20px; }
.tab-btn { flex:1; padding:12px; border:none; background:#f5f5f5; cursor:pointer; font-weight:600; }
.tab-btn.active { background:white; border-bottom:3px solid orangered; }
.tab-content { display:none; }
.tab-content.active { display:block; }
.input-field, textarea, select { width:100%; box-sizing:border-box; margin-bottom:15px; padding:8px; }
.variants-flex { display:flex; flex-direction:column; gap:15px; }
.variant { border:1px solid #ddd; padding:10px; border-radius:4px; margin-bottom:10px; }
.variant label { display:block; margin-top:5px; }
.subtabs { display:flex; margin-bottom:20px; border-bottom:1px solid #ddd; }
.subtab-btn { flex:1; padding:10px; border:none; background:#f5f5f5; cursor:pointer; font-weight:500; }
.subtab-btn.active { background:white; border-bottom:2px solid orangered; }
.subtab-content { display:none; }
.subtab-content.active { display:block; }
.dropzone { border:2px dashed #bbb; padding:30px; text-align:center; cursor:pointer; border-radius:4px; background:#fafafa; }
.preview-grid { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
.preview-grid img, .preview-grid video { width:80px; height:80px; object-fit:cover; border-radius:4px; border:1px solid #ccc; }
.form-footer { margin-top:30px; text-align:right; }
.btn-submit { padding:10px 20px; background:orangered; color:white; border:none; border-radius:4px; cursor:pointer; }
.btn-ai { background:#4CAF50; color:white; padding:8px 12px; border:none; border-radius:4px; cursor:pointer; margin-top:10px; }
</style>

<script>

let processedImages = [];
let currentLang = 'es';
let variantIndex = <?= count($productVariants) ?>;

// =============================
// INIT GENERAL
// =============================
function initProductForm(){
  initTabs();
  initSubtabs();
  initDropzones();
  initSubmit();
  initAI();
  initVariants();
  initLangSwitcher();
}

// =============================
// Tabs principales
// =============================
function initTabs(){
  document.querySelectorAll(".tab-btn").forEach(btn=>{
    btn.addEventListener("click",function(){
      document.querySelectorAll(".tab-btn").forEach(b=>b.classList.remove("active"));
      document.querySelectorAll(".tab-content").forEach(c=>c.classList.remove("active"));
      this.classList.add("active");
      document.getElementById(this.dataset.tab).classList.add("active");
    });
  });
}

// =============================
// Subtabs
// =============================
function initSubtabs(){
  document.querySelectorAll(".subtab-btn").forEach(btn=>{
    btn.addEventListener("click",function(){
      document.querySelectorAll(".subtab-btn").forEach(b=>b.classList.remove("active"));
      document.querySelectorAll(".subtab-content").forEach(c=>c.classList.remove("active"));
      this.classList.add("active");
      document.getElementById(this.dataset.subtab).classList.add("active");
    });
  });
}

// =============================
// Dropzones
// =============================
function initDropzones(){
  setupDropzone('imagesDropzone','imagesInput','imagesPreview');
  setupDropzone('videosDropzone','videosInput','videosPreview');
  setupDropzone('docsDropzone','docsInput','docsPreview');
}

function setupDropzone(dropzoneId, inputId, previewId){

  const dropzone = document.getElementById(dropzoneId);
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);

  if(!dropzone || !input) return;

  dropzone.onclick = ()=>input.click();

  dropzone.ondragover = e=>{
    e.preventDefault();
    dropzone.style.backgroundColor='#eee';
  };

  dropzone.ondragleave = e=>{
    e.preventDefault();
    dropzone.style.backgroundColor='#fafafa';
  };

  dropzone.ondrop = e=>{
    e.preventDefault();
    dropzone.style.backgroundColor='#fafafa';
    handleFiles(e.dataTransfer.files);
  };

  input.onchange = e=>handleFiles(e.target.files);

  function handleFiles(files){

    const maxSize = 1200;

    Array.from(files).forEach(file=>{

      if(!file.type.startsWith('image')) return;

      const reader = new FileReader();

      reader.onload = function(e){

        const img = new Image();

        img.onload = function(){

          let width = img.width;
          let height = img.height;

          if(width > maxSize || height > maxSize){
            if(width > height){
              height = height * (maxSize / width);
              width = maxSize;
            }else{
              width = width * (maxSize / height);
              height = maxSize;
            }
          }

          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');

          canvas.width = width;
          canvas.height = height;

          ctx.drawImage(img,0,0,width,height);

          canvas.toBlob(blob=>{

            const newFile = new File([blob], file.name, {
              type:'image/jpeg'
            });

            processedImages.push(newFile);

          },'image/jpeg',0.82);

          const el = document.createElement('img');
          el.src = canvas.toDataURL('image/jpeg',0.82);
          preview.appendChild(el);

        };

        img.src = e.target.result;

      };

      reader.readAsDataURL(file);

    });

  }
}

// =============================
// Submit
// =============================
function initSubmit(){

  const form = document.getElementById("productForm");
  if(!form) return;

  form.addEventListener("submit", function(e){

    const formData = new FormData(form);

    // 🔥 FORZAMOS idioma correcto
    formData.set("lang", currentLang);

    if(processedImages.length === 0) return;

    e.preventDefault();

    formData.delete("images[]");

    processedImages.forEach(file=>{
      formData.append("images[]", file);
    });

    fetch(form.action,{
      method:"POST",
      body:formData
    })
    .then(res=>res.text())
    .then(()=>{
      location.reload();
    })
    .catch(err=>{
      console.error(err);
      alert("Error subiendo imágenes");
    });

  });
}

// =============================
// AI
// =============================
function initAI(){

  const btn = document.getElementById('generateAI');
  if(!btn) return;

  btn.addEventListener('click', async ()=>{

    const description = document.getElementById('description').value;

    if(!description){
      alert('Ingrese descripción antes');
      return;
    }

    try{

      const response = await fetch('/api/generate-description',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ description })
      });

      const data = await response.json();

      if(data.en)
        document.getElementById('description').value = data.en;

      if(data.es_short)
        document.querySelector('textarea[name="short_description"]').value = data.es_short;

      alert('Descripciones generadas correctamente');

    }catch(e){
      console.error(e);
      alert('Error AI');
    }

  });
}

// =============================
// Variants
// =============================
function initVariants(){

  const btn = document.getElementById('addVariant');
  if(!btn) return;

  btn.addEventListener('click', ()=>{

    const container = document.getElementById('variantsContainer');

    const div = document.createElement('div');
    div.classList.add('variant');
    div.dataset.index = variantIndex;

    div.innerHTML = `
      <h4>Variant ${variantIndex + 1}</h4>

      <label>Precio:</label>
      <input type="number" name="variants[${variantIndex}][price]" step="0.01" required>

      <label>Stock:</label>
      <input type="number" name="variants[${variantIndex}][stock]" required>

      <label>Peso:</label>
      <input type="number" name="variants[${variantIndex}][weight]" step="0.01">

      <label>Ancho:</label>
      <input type="number" name="variants[${variantIndex}][width]" step="0.01">

      <label>Alto:</label>
      <input type="number" name="variants[${variantIndex}][height]" step="0.01">

      <label>Largo:</label>
      <input type="number" name="variants[${variantIndex}][length]" step="0.01">

      <label>Imagen</label>
      <input type="text" name="variants[${variantIndex}][image_path]" class="input-field">

      <div class="variant-attributes"></div>
    `;

    container.appendChild(div);
    variantIndex++;

  });
}

// =============================
// LANG SWITCHER (FIX REAL)
// =============================
function initLangSwitcher(){

  document.querySelectorAll('.lang-btn').forEach(btn => {

    btn.addEventListener('click', async () => {

      const lang = btn.dataset.lang;
      currentLang = lang;

      const productId = <?= $product['id'] ?>;

      try {

        const res = await fetch(`index.php?page=products&action=edit&id=${productId}&lang=${lang}`);
        const html = await res.text();

        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newForm = doc.querySelector('#productForm');

        document.querySelector('#productForm').replaceWith(newForm);

        // 🔥 REHIDRATAR TODO
        initProductForm();

      } catch (e) {
        console.error(e);
        alert('Error cargando idioma');
      }

    });

  });
}

// =============================
// START
// =============================
document.addEventListener('DOMContentLoaded', ()=>{
  initProductForm();
});

</script>