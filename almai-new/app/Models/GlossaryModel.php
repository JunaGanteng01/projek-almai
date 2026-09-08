<?php

namespace App\Models;

use CodeIgniter\Model;

class GlossaryModel extends Model
{
    protected $table = 'glossaries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['term', 'slug', 'short_description', 'definition'];
    protected $useTimestamps = true;
}
