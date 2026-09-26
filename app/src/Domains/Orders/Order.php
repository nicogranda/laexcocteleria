<?php
namespace App\Domains\Orders;

require_once __DIR__ . '/../../Shared/Model.php';

class Order extends \App\Shared\Models\Model
{
    protected string $tableOrders  = 'orders';
    protected string $tableDetails = 'order_details';

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    /**
     * Crear una orden junto con sus detalles
     */
    public function createWithItems(array $orderData, array $items): int
    {
        $this->mysqli->begin_transaction();
    
        try {
            // 0️⃣ Validar customer_id
            $customerId = $orderData['customer_id'] ?? null;
            if (!$customerId) {
                throw new \Exception("No se proporcionó customer_id válido.");
            }
    
            // 1️⃣ Insertar la orden principal
            $sqlOrder = "INSERT INTO {$this->tableOrders} 
            (
                user_id,
                customer_id,
                order_date,
                total,
                shipping_address,
                shipping_secondary_address,
                shipping_city,
                shipping_state,
                shipping_zip,
                shipping_cost,
                discount_code,
                discount_amount,
                status,
                payment_intent_id
            )
            VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->mysqli->prepare($sqlOrder);
            
            $userId        = $orderData['user_id'] ?? null;
            $total         = $orderData['total'] ?? 0;
            $address       = $orderData['shipping_address'] ?? '';
            $address2      = $orderData['shipping_secondary_address'] ?? null;
            $city          = $orderData['shipping_city'] ?? '';
            $state         = $orderData['shipping_state'] ?? '';
            $zip           = $orderData['shipping_zip'] ?? '';
            $shippingCost  = $orderData['shipping_cost'] ?? 0;
            $discountCode  = $orderData['discount_code'] ?? null;
            $discountAmount= $orderData['discount_amount'] ?? 0;
            $status        = $orderData['status'] ?? 'paid';
            $paymentIntent = $orderData['payment_intent_id'] ?? null;
            
            $stmt->bind_param(
                "iidsssssssdss",
                $userId,
                $customerId,
                $total,
                $address,
                $address2,
                $city,
                $state,
                $zip,
                $shippingCost,
                $discountCode,
                $discountAmount,
                $status,
                $paymentIntent
            );

            $stmt->execute();
            $orderId = $stmt->insert_id;
            $stmt->close();
    
            if (!$orderId) {
                throw new \Exception("No se pudo crear la orden principal.");
            }
    
            // 2️⃣ Insertar detalles de la orden
            $sqlDetail = "INSERT INTO {$this->tableDetails} 
                (order_id, variant_id, quantity, price)
                VALUES (?, ?, ?, ?)";
    
            $stmtDetail = $this->mysqli->prepare($sqlDetail);
    
            foreach ($items as $item) {
                $variantId = (int)($item['variant_id'] ?? 0);
                $qty       = (int)($item['qty'] ?? 1);
                $price     = (float)($item['price'] ?? 0.0);
    
                if ($variantId === 0) {
                    throw new \Exception("Error: variant_id no válido para el producto.");
                }
    
                $stmtDetail->bind_param("iiid", $orderId, $variantId, $qty, $price);
                $stmtDetail->execute();
            }
    
            $stmtDetail->close();
    
            $this->mysqli->commit();
    
            return $orderId;
    
        } catch (\Exception $e) {
            $this->mysqli->rollback();
            throw $e;
        }
    }

    public function getById(int $orderId): array
    {
        $sql = "
            SELECT o.*, 
                   c.name AS customer_name
            FROM {$this->tableOrders} o
            LEFT JOIN customers c ON o.customer_id = c.id
            WHERE o.id = ? 
            LIMIT 1
        ";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
    
        return $order ?: [];
    }

    public function getDetails(int $orderId): array
    {
        $sql = "
            SELECT 
                od.id,
                od.order_id,
                od.variant_id,
                od.quantity,
                od.price,
                p.name AS product_name
            FROM {$this->tableDetails} od
            JOIN product_variants pv ON od.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE od.order_id = ?
        ";
    
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $details;
    }

    public function getOrderDimensions(int $orderId): array
    {
        $query = "
            SELECT 
                od.quantity,
                pv.weight,
                pv.length,
                pv.width,
                pv.height
            FROM order_details od
            JOIN product_variants pv ON od.variant_id = pv.id
            WHERE od.order_id = ?
        ";
    
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $total_weight = 0;
        $total_length = 0;
        $max_width    = 0;
        $max_height   = 0;
    
        while ($item = $result->fetch_assoc()) {
            $quantity = (int)($item['quantity'] ?? 1);
    
            $total_weight += ($item['weight'] ?? 0) * $quantity;
            $total_length += ($item['length'] ?? 0) * $quantity;
    
            if (($item['width'] ?? 0) > $max_width) {
                $max_width = $item['width'];
            }
            if (($item['height'] ?? 0) > $max_height) {
                $max_height = $item['height'];
            }
        }
    
        return [
            'total_weight' => $total_weight > 0 ? $total_weight : 0.5,
            'total_length' => $total_length > 0 ? $total_length : 6,
            'max_width'    => $max_width > 0 ? $max_width : 4,
            'max_height'   => $max_height > 0 ? $max_height : 1
        ];
    }

    public function updateTrackingAndLabel($orderId, $trackingNumber, $labelFileName)
    {
        $query = "UPDATE orders SET tracking_number = ?, label_url = ?, status = 'paid' WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ssi", $trackingNumber, $labelFileName, $orderId);
        return $stmt->execute();
    }

    public function getOrderIdByTracking(string $tracking): ?int
    {
        $sql = "SELECT id FROM {$this->tableOrders} WHERE tracking_number = ? LIMIT 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $tracking);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    
        return $row['id'] ?? null;
    }
}