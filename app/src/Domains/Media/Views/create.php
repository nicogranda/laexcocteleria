<form id="imageForm">
    <input type="number" name="product_id" id="productId" placeholder="ID producto" required>

    <div id="dropzone">
        <p>Arrastra imágenes aquí o haz clic para seleccionar</p>
        <input type="file" id="imagesInput" multiple accept="image/*" hidden>
    </div>

<ul id="previewList"></ul>


    <button type="submit">Enviar imágenes</button>
</form>
<div id="messages"></div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/heic2any/dist/heic2any.min.js"></script>

<script>
const form = document.getElementById('imageForm');
const input = document.getElementById('imagesInput');
const previewList = document.getElementById('previewList');
const messages = document.getElementById('messages');
const dropzone = document.getElementById('dropzone');


let filesBuffer = [];

// ===== Mensajes =====
function showMessage(text, type = 'error') {
    const msg = document.createElement('div');
    msg.textContent = text;
    msg.className = `msg ${type}`;
    messages.appendChild(msg);
    setTimeout(() => {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 300);
    }, 4000);
}

// ===== Drag & Drop =====
new Sortable(previewList, {
    animation: 150,
    onEnd: () => {
        filesBuffer = [...previewList.children].map(li => li.file);
    }
});


dropzone.addEventListener('click', () => input.click());

dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragover');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragover');
});

dropzone.addEventListener('drop', async (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');

    const files = e.dataTransfer.files;
    await handleFiles(files);
});

async function handleFiles(fileList) {
    for (let file of fileList) {
        const processed = await processImage(file);
        if (!processed) continue;

        filesBuffer.push(processed);
        renderPreview(processed);
    }
}




// ===== Previsualización y procesamiento =====
input.addEventListener('change', async () => {
    await handleFiles(input.files);
    input.value = '';
});


// ===== Submit =====
form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (!filesBuffer.length) {
        showMessage('No hay imágenes', 'error');
        return;
    }

    const formData = new FormData(form);
    filesBuffer.forEach(file => formData.append('images[]', file));

    fetch('https://maletachic.com/admin/index.php?page=media&action=store', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'ok') {
            showMessage('Imágenes subidas correctamente', 'success');
            // Limpiar buffer y preview
            filesBuffer = [];
            previewList.innerHTML = '';
        } else {
            showMessage(data.error || 'Error al subir las imágenes', 'error');
        }
    })
    .catch(err => showMessage('Error de conexión: ' + err.message, 'error'));
});

// ===== Función de procesamiento =====
async function processImage(file) {
    let blob = file;

    // HEIC → JPG
    if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')) {
        try {
            blob = await heic2any({ blob, toType: 'image/jpeg', quality: 0.85 });
        } catch(e) {
            showMessage('Error al convertir HEIC: ' + e.message, 'error');
            return null;
        }
    }

    const img = new Image();
    img.src = URL.createObjectURL(blob);
    await img.decode();

    // ❌ Rechazar verticales (9:16)
    if (img.height > img.width) {
        showMessage('Solo se aceptan imágenes horizontales 16:9', 'error');
        return null;
    }

    // Resize + optimización
    const canvas = document.createElement('canvas');
    const maxWidth = 1600;
    const scale = Math.min(1, maxWidth / img.width);

    canvas.width = img.width * scale;
    canvas.height = img.height * scale;
    canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);

    return new Promise(resolve => {
        canvas.toBlob(blob => {
            resolve(new File([blob], file.name.replace(/\.\w+$/, '.jpg'), { type: 'image/jpeg' }));
        }, 'image/jpeg', 0.85);
    });
}

// ===== Render preview =====
function renderPreview(file) {
    const li = document.createElement('li');
    li.file = file;

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);

    const btn = document.createElement('button');
    btn.textContent = '✕';
    btn.onclick = () => {
        filesBuffer = filesBuffer.filter(f => f !== file);
        li.remove();
    };

    li.append(img, btn);
    previewList.appendChild(li);
}
</script>

<style>
/* Cuadro de previsualización */
#previewList {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
    list-style: none;
    margin-top: 10px;
}

#previewList li {
    width: 120px;
    height: 90px;
    position: relative;
    border: 1px solid #ccc;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f9f9;
    border-radius: 5px;
}

#previewList li img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

/* Botón de eliminar */
#previewList li button {
    position: absolute;
    top: 2px;
    right: 2px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

/* Mensajes */
#messages {
    margin-bottom: 10px;
}

.msg {
    padding: 8px 12px;
    border-radius: 5px;
    margin-bottom: 5px;
    font-size: 0.9rem;
    opacity: 0.95;
    transition: opacity 0.3s;
}

.msg.error {
    background-color: #ffe5e5;
    color: #b80000;
}

.msg.success {
    background-color: #e5ffe5;
    color: #007a00;
}
</style>
