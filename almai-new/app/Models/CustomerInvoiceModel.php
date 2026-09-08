<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerInvoiceModel extends Model
{
    protected $table = 'customer_invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'invoice_number',
        'tanggal',
        'jatuh_tempo',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'product_name',
        'currency',
        'items',
        'subtotal',
        'ppn',
        'total',
        'status',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
