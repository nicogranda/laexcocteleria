<?php
namespace App\Domains\Customers;

require_once __DIR__ . '/../../Shared/Model.php';

class Customer extends \App\Shared\Models\Model
{
    protected string $table = 'customers';

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    public function findByEmail(string $email): ?array
    {
        $result = $this->getByColumn('email', $email);
        return $result[0] ?? null;
    }

    public function findOrCreate(array $data): int
    {
        $existing = $this->findByEmail($data['email']);

        if ($existing) {
            $this->updateById($existing['id'], $data);
            return $existing['id'];
        }

        return $this->create($data);
    }
}