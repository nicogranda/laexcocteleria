<section class="hero-portfolio">
    <img src="/images/heros/portfolio_desk.png">
</section>   
<?php
$total   = $data['amount'];

?>

<script src="https://js.stripe.com/v3/"></script>

<div class="body-container">
    <div class="form-container-tdd">
        <h2 class="principal">Payment</h2>
        <p><strong>Total:</strong> <?= number_format($total, 2) ?></p>
        <div id="card-element" class="card-input"></div>
        <div id="card-errors" class="error-message"></div>
        <button id="payBtn" class="btn-submit">Pay Now</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const stripe = Stripe("<?= $config['stripe']['public_key'] ?>");
    const elements = stripe.elements();

    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': { color: '#a0aec0' }
            }
        }
    });

    card.mount('#card-element');

    const payBtn = document.getElementById('payBtn');

    payBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        payBtn.disabled = true;

        // 1️⃣ Crear PaymentMethod
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: card
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            payBtn.disabled = false;
            return;
        }

        // 2️⃣ Enviar PaymentMethod a tu endpoint para crear PaymentIntent
        const data = new FormData();
        data.append('payment_method', paymentMethod.id);
        data.append('amount', <?= (int) round($total * 100) ?>);
        data.append('customer_email', "<?= htmlspecialchars($data['email']) ?>");

        let paymentRes;
        try {
            paymentRes = await fetch('/api/stripe/payment.php', {
                method: 'POST',
                body: data
            }).then(r => r.json());
        } catch (err) {
            alert("Error procesando el pago. Revisa la consola.");
            console.error(err);
            payBtn.disabled = false;
            return;
        }

        if (!paymentRes.success) {
            alert(paymentRes.message || 'Payment failed');
            payBtn.disabled = false;
            return;
        }

        // 3️⃣ Crear formulario para enviar al OrderController
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?page=order&action=create';

        const fields = {
            payment_intent_id: paymentRes.payment_intent_id,
            amount: <?= (int) round($total * 100) ?>,
            shipping_cost: "<?= (float)$data['shipping_cost'] ?>",
            discount_code: "<?= htmlspecialchars($data['coupon_code']) ?>",
            discount_amount: "<?= (float)$data['discount'] ?>",
            email: "<?= htmlspecialchars($data['email']) ?>",
            name: "<?= htmlspecialchars($data['name']) ?>",
            address: "<?= htmlspecialchars($data['address']) ?>",
            secondary_address: "<?= htmlspecialchars($data['secondary_address'] ?? '') ?>",
            city: "<?= htmlspecialchars($data['city']) ?>",
            state: "<?= htmlspecialchars($data['state']) ?>",
            zip: "<?= htmlspecialchars($data['zip']) ?>",
            country: "<?= htmlspecialchars($data['country']) ?>"
        };

        for (const key in fields) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    });
});
</script>
<style>
    /* ================================
   MOBILE STRIPE FIX (FULL WIDTH)
    ================================ */
@media (max-width: 768px) {

    /* Formulario ocupa casi toda la pantalla */
    .form-container-tdd {
        width: 95% !important;
        max-width: none !important;
        padding: 1.5rem;
    }

    /* Stripe wrapper ocupa todo */
    #card-element {
        width: 100% !important;
    }

    /* El input visual de Stripe */
    .card-input {
        width: 100% !important;
        box-sizing: border-box;
    }

    /* El iframe interno de Stripe */
    .card-input iframe {
        width: 100% !important;
        min-width: 100% !important;
    }

    /* Body container no tan centrado vertical */
    .body-container {
        align-items: flex-start;
        padding-top: 40px;
    }
}

#payBtn {
    display: flex;              /* Flexbox */
    justify-content: center;    /* Centrado horizontal */
    align-items: center;        /* Centrado vertical */
    width: 100%;                /* Ocupa todo el contenedor */
    padding: 12px 0;            /* Espaciado interno */
    background-color: var(--color-primary);  /* Color de fondo */
    color: #fff;                /* Color del texto */
    font-size: 16px;            /* Tamaño de letra */
    font-weight: 600;           /* Negrita opcional */
    border: none;               /* Sin borde */
    border-radius: 6px;         /* Bordes redondeados */
    cursor: pointer;            /* Mano al pasar por encima */
    transition: background 0.2s;
}

#payBtn:hover {
    background-color: var(--color-secondary);  /* Hover más oscuro */
}

#payBtn:disabled {
    background-color: #a0aec0;  /* Estado deshabilitado */
    cursor: not-allowed;
}

</style>
