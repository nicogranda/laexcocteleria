<?php
$variant = $variants[0] ?? null;
if (!$variant) return;

$productName = htmlspecialchars($product['name'] ?? 'Sin nombre');
$productImage = htmlspecialchars($variant['image_path'] ?? $product['image_path'] ?? 'default.jpg');
$productPrice = $variant['price'] ?? 0;

$baseUrl = $baseUrl ?? rtrim($_ENV['APP_URL']);
?>

<form action="<?= $baseUrl ?>/index.php?page=cart&action=add" method="POST" class="cart-form">
    <input type="hidden" name="variant_id" value="<?= $variant['variant_id'] ?>">
     <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"
        data-product-name="<?= $productName ?>"
        data-product-image="<?= $baseUrl ?>/products/<?= $productImage ?>"
        data-product-price="<?= $productPrice ?>">
        Add to Cart
    </button>
</form>

<script>
document.querySelectorAll('.cart-form').forEach(form => {
    form.addEventListener('submit', async e => {
        e.preventDefault();
        
        console.log('🛒 Form submitted');
        
        const button = form.querySelector('button');
        const formData = new FormData(form);
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'AddToCart', {
                content_ids: [formData.get('variant_id')],
                content_name: button.dataset.productName,
                content_type: 'product',
                value: parseFloat(button.dataset.productPrice) || 0,
                currency: 'USD'
            });
        }
        
        try {
            console.log('📤 Sending request to:', form.action);
            
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            console.log('📥 Response status:', res.status);
            
            const responseText = await res.text();
            console.log('📄 Response text:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
                console.log('✅ Parsed JSON:', data);
            } catch (parseError) {
                console.error('❌ JSON parse error:', parseError);
                console.error('Response was:', responseText);
                throw new Error('Invalid JSON response');
            }
            
            if (data.status !== 'ok') {
                throw new Error('Server returned error status');
            }
            
            // Google Analytics (GA4)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'add_to_cart', {
                    currency: 'USD',
                    value: parseFloat(button.dataset.productPrice) || 0,
                    items: [{
                        item_id: formData.get('variant_id'),
                        item_name: button.dataset.productName,
                        price: parseFloat(button.dataset.productPrice) || 0,
                        quantity: 1
                    }]
                });
            }
            
            // Actualizar badge
            const badge = document.querySelector('.mini-cart-badge');
            console.log('🏷️ Badge element:', badge);
            console.log('📊 New count:', data.cartCount);
            
            if (badge) {
                badge.textContent = data.cartCount;
                if (data.cartCount > 0) {
                    badge.classList.remove('hidden');
                    badge.style.display = 'block';
                } else {
                    badge.classList.add('hidden');
                }
                console.log('✅ Badge updated');
            } else {
                console.error('❌ Badge element not found!');
            }
            
            // Actualizar dropdown
            const dropdown = document.querySelector('.mini-cart-dropdown');
            if (dropdown && data.dropdownHtml) {
                dropdown.innerHTML = data.dropdownHtml;
                console.log('✅ Dropdown updated');
            }
            
            // Feedback visual
            const originalText = button.textContent;
            button.textContent = '✓ Added!';
            button.style.backgroundColor = 'green';
            button.style.color = 'white';
            
            setTimeout(() => {
                button.textContent = originalText;
                button.style.backgroundColor = '';
                button.style.color = '';
            }, 1500);
            
        } catch(err) {
            console.error('❌ Error:', err);
            alert('Error: ' + err.message);
        }
    });
});

console.log('✅ Cart forms initialized:', document.querySelectorAll('.cart-form').length);
</script>


<style>
.add-to-cart-btn {
    display: block;
    margin: 0 auto; 
    background-color: transparent;
    border: 2px solid black;
    border-radius: 15px;
    padding: 8px 18px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.add-to-cart-btn:hover {
    background-color: var(--color-primary) !important;
    color: white;
    transform: scale(1.05);
}

.add-to-cart-btn:active {
    transform: scale(0.97);
    opacity: 0.85;
}

.cart-form {
    display: inline-block;
    margin: 0;
}
</style>