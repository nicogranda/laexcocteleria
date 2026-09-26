<?php
namespace App\Domains\Payments;

class PaymentsController
{
    protected \mysqli $mysqli;
    protected string $lang;
    protected array $stripeConfig;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->lang = $_SESSION['lang'] ?? 'es';

        // Nueva ubicación de config
        $configPath = __DIR__ . '/../../Shared/config/stripe.php';
        if (!file_exists($configPath)) {
            throw new \RuntimeException("Stripe config no encontrado");
        }

        $this->stripeConfig = require $configPath;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=cart");
            exit;
        }

        $data = [
            'email'          => $_POST['email'] ?? '',
            'name'           => $_POST['name'] ?? '',
            'address'        => $_POST['address'] ?? '',
            'secondary_address' => $_POST['secondary_address'] ?? '',
            'city'           => $_POST['city'] ?? '',
            'state'          => $_POST['state'] ?? '',
            'zip'            => $_POST['zip'] ?? '',
            'country'        => $_POST['country'] ?? 'US',
            'amount'         => (float) ($_POST['amount'] ?? 0),
            'shipping_cost'  => (float) ($_POST['shipping_price'] ?? 0),
            'coupon_code'    => $_POST['coupon_code'] ?? '',
            'discount'       => (float) ($_POST['discount_amount'] ?? 0),
            'order_id'       => uniqid('ORD_')
        ];

        if ($data['amount'] <= 0) {
            die('Invalid amount');
        }

        // Pasar datos + config al view
        $config = $this->stripeConfig;

        require __DIR__ . '/../../../views/pages/payments/stripe.php';
    }
}