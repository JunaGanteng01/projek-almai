<?php
namespace App\Models;
use CodeIgniter\Model;
class AutomationWorkflowModel extends Model {
    protected $table='automation_workflows'; protected $returnType='array'; protected $useTimestamps=true;
    protected $allowedFields=['name','trigger','workflow_json','is_active','created_by'];
}
