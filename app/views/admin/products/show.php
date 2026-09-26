<?php $page = "products"; 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

    <style>
p {
    line-height: 0.80; /* Ajusta el espacio entre líneas */
    min-height: 20px; /* Evita que el párrafo sea demasiado pequeño */
    margin: 0; /* Elimina márgenes extra */
    padding: 5px 0; /* Espaciado interno opcional */
}

.table-container {
    width: 90%; /* Usa el ancho completo */
    max-width: 100%; /* Previene desbordamiento horizontal */
    overflow-x: auto; /* Activa scroll horizontal solo si es necesario */
}

table {
    width: 100%; /* Ancho completo dentro del contenedor */
    max-width: 100%; /* Asegura que no desborde */
    border-collapse: collapse;
    margin: 0 auto;
    word-wrap: break-word; /* Envuelve contenido largo */
}
        .container {
            text-align: center; /* Centra el contenido del contenedor */
            width: 100%; /* Ajusta el ancho del contenedor */
        }
        
        .container img {
            display: block; /* Necesario para que margin auto funcione correctamente */
            margin: 0 auto; /* Centra la imagen horizontalmente */
            width: 50%; /* Ajusta el ancho de la imagen */
        }
        
        th, td {
            border: 1px solid #ccc; /* Borde alrededor de las celdas */
            padding: 10px; /* Espacio dentro de las celdas */
            /* text-align: right;  Centra el texto dentro de las celdas */
        }
        
        th {
            /* background-color: orangered;  Fondo gris claro para las cabeceras */
            /* color: white; Fuente blanca */
        }
        
        th:first-child {
            width: 60%; /* Ajusta el ancho de la primera columna (Producto) */
        }
        
        th:nth-child(2), td:nth-child(2),
        th:nth-child(3), td:nth-child(3),
        th:nth-child(4), td:nth-child(4),
        th:nth-child(5), td:nth-child(5){
            width: 10%; /* Ajusta el ancho de las demás columnas */
        }
        
        .item input {
            height: 100%;
            width: 100%;
            padding: 0px;
            text-align: right;
            border: none;
        }
    
        .events {
            padding-top: 20px;
        }
      
#stripeModal div {
    width: 90%;
    max-width: 600px;
}

@media (max-width: 768px) {
    #stripeModal div {
        width: 95%;
    }
}

    </style>

<section class="container-form">
    <form name="form1" method="post" action="index.php?page=<?php echo $page;?>&action=update" accept-charset="UTF-8" onsubmit="return validateForm();" enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden" name="id" id="id"   value="<?php echo htmlspecialchars($product['id']); ?>">
            <input type="text" name="name" id="name" placeholder="Name" class="input-field" value="<?php echo htmlspecialchars($product['name']); ?>">
            <span id="nameError" class="error-message" style="display:none;">Please fill in the Name field</span>
        </div>

        <div class="form-group">
            <input type="text" name="slug" id="slug" placeholder="Slug" class="input-field" value="<?php echo htmlspecialchars($product['slug']); ?>">
            <span id="slugError" class="error-message" style="display:none;">Please fill in the Unit field</span>
        </div>

        <div class="form-group">
            <input type="text" name="unit" id="unit" placeholder="Unit" class="input-field" value="<?php echo htmlspecialchars($product['unit']); ?>">
            <span id="unitError" class="error-message" style="display:none;">Please fill in the Unit field</span>
        </div>

        <div class="form-group">
            <input type="number" name="unit_value" id="unitValue" placeholder="Unit Value" class="input-field" value="<?php echo htmlspecialchars($product['unit_value']); ?>">
            <span id="unitValueError" class="error-message" style="display:none;">Please fill in the Unit Value field</span>
        </div>

        <div class="form-group">
            <input type="text" name="category_id" id="categoryId" placeholder="Category ID" class="input-field" value="<?php echo htmlspecialchars($product['category_id']); ?>">
            <span id="categoryError" class="error-message" style="display:none;">Please fill in the Category ID field</span>
        </div>

        <div class="form-group">
            <input type="number" name="vat_rate" id="vatRate" placeholder="VAT Rate" class="input-field" value="<?php echo htmlspecialchars($product['vat_rate']); ?>">
            <span id="vatRateError" class="error-message" style="display:none;">Please fill in the VAT Rate field</span>
        </div>

        <input type="submit" value="Update" class='button-primary'>
    </form>
</section>

<script>
function validateForm() {
    let isValid = true;

    // Obtener elementos
    const name = document.getElementById('name');
    const unit = document.getElementById('unit');
    const unitValue = document.getElementById('unitValue');
    const categoryId = document.getElementById('categoryId');
    const vatRate = document.getElementById('vatRate');
    const width = document.getElementById('width');
    const height = document.getElementById('height');
    const long = document.getElementById('long');
    const weight = document.getElementById('weight');
    const photo = document.getElementById('photo');

    const nameError = document.getElementById('nameError');
    const unitError = document.getElementById('unitError');
    const unitValueError = document.getElementById('unitValueError');
    const categoryError = document.getElementById('categoryError');
    const vatRateError = document.getElementById('vatRateError');

    // Validar campos vacíos
    const validateField = (field, errorElement) => {
        if (!field.value.trim()) {
            errorElement.style.display = 'block';
            isValid = false;
        } else {
            errorElement.style.display = 'none';
        }
    };

    validateField(name, nameError);
    validateField(unit, unitError);
    validateField(unitValue, unitValueError);
    validateField(categoryId, categoryError);
    validateField(vatRate, vatRateError);

    // Validar campos de envío si está seleccionado "Yes"
    const shipmentYes = document.querySelector('input[name="shipment"][value="Yes"]:checked');
    if (shipmentYes) {
        [width, height, long, weight].forEach(field => {
            if (!field.value.trim()) {
                field.nextElementSibling?.classList.add('error-message');
                field.nextElementSibling?.classList.remove('hidden');
                isValid = false;
            } else {
                field.nextElementSibling?.classList.add('hidden');
            }
        });
    }

    // Validar que al menos una opción de instalación esté seleccionada
    const installationChecked = document.querySelector('input[name="installation"]:checked');
    if (!installationChecked) {
        alert('Please select an option for Installation.');
        isValid = false;
    }

    // Validar que al menos una opción de envío esté seleccionada
    const shipmentChecked = document.querySelector('input[name="shipment"]:checked');
    if (!shipmentChecked) {
        alert('Please select an option for Shipment.');
        isValid = false;
    }

    // Validar que se haya seleccionado una foto si es obligatorio
    if (!photo.files.length) {
        alert('Please upload a photo.');
        isValid = false;
    }

    return isValid;
}
</script>

<script>
function openStripeWindow() {
    window.open('https://ikusa.net/stripe/index.php', '_blank', 'width=600,height=700');
}
function downloadPDF() {
  
    // Capturamos el valor de budget_id y redirigimos al enlace con ese parámetro
    var operationId = document.getElementById('operation_id').value;
    //var url = 'https://ikusa.net/admin/fpdf/quote.php?id=' + operationId;
     
    var url = 'https://ikusa.net/admin/index.php?page=quotes&action=print&id=' + operationId;
    // var url = 'https://ikusa.net/admin/fpdf/quote.php?id=' + quoteId;
    // Abre la URL en una nueva pestaña
    window.open(url, '_blank');
    // Redirige a la URL que genera el PDF
    //window.location.href = url;
}

// // function openStripeModal(amount) {
//     alert(amount);
//     const modal = document.getElementById('stripeModal');
//     const iframe = document.getElementById('stripeIframe');
//     iframe.src = `https://ikusa.net/stripe/index.php?amount=${amount}`; // Pasa el monto a Stripe
//     modal.style.display = 'block';
// }

// document.getElementById('closeModal').onclick = function () {
//     const modal = document.getElementById('stripeModal');
//     const iframe = document.getElementById('stripeIframe');
//     iframe.src = ''; // Limpia el iframe para evitar que siga cargando en segundo plano
//     modal.style.display = 'none';
// };


// function openStripeModal(total) {
//     var modal = document.getElementById("stripeModal");
//     var iframe = document.getElementById("stripeIframe");
//     iframe.src = `https://ikusa.net/stripe/index.php?amount=${total}`; // Pasa el monto a Stripe
//     // Asegura que 'total' esté en el formato correcto
//     if (!total || isNaN(parseFloat(total))) {
//         alert("El monto no es válido");
//         return;
//     }

//     // Define la URL de Stripe (ajústala según tu integración)
//     //iframe.src = "tu_url_de_pago_de_stripe_aqui?amount=" + total;

//     // Muestra el modal
//     modal.style.display = "block";
// }

    function openStripeModal(total) {
        var modal = document.getElementById("stripeModal");
        var iframe = document.getElementById("stripeIframe");
        iframe.src = `https://ikusa.net/stripe/index.php?amount=${total}`; // Pasa el monto a Stripe
        // Asegura que 'total' esté en el formato correcto
        if (!total || isNaN(parseFloat(total))) {
            alert("El monto no es válido");
            return;
        }

        // Muestra el modal
        modal.style.display = "block";
    }

    // Cerrar modal al hacer clic en el botón de cerrar
    document.getElementById("closeModal").addEventListener("click", function () {
        document.getElementById("stripeModal").style.display = "none";
        document.getElementById("stripeIframe").src = ''; // Limpia el iframe
    });

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function (event) {
        const modal = document.getElementById('stripeModal');
        if (event.target === modal) {
            const iframe = document.getElementById('stripeIframe');
            iframe.src = ''; 
            modal.style.display = 'none';
        }
    };

// Cerrar modal
// document.getElementById("closeModal").addEventListener("click", function () {
//     document.getElementById("stripeModal").style.display = "none";
// });



window.onclick = function (event) {
    const modal = document.getElementById('stripeModal');
    if (event.target === modal) {
        const iframe = document.getElementById('stripeIframe');
        iframe.src = ''; 
        modal.style.display = 'none';
    }
};

</script>