<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['key', 'value', 'type', 'group'];
    protected $useTimestamps = true;

    public function get($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function setValue($key, $value, $type = 'text', $group = 'general')
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        }
        
        return $this->insert([
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'group' => $group
        ]);
    }

    public function getByGroup($group)
    {
        return $this->where('group', $group)->findAll();
    }

    public function getAllAsArray()
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        return $result;
    }

    /**
     * Get setting value by key
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Set setting value by key
     */
    public function setSetting($key, $value, $type = 'textarea', $group = 'general')
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return $this->insert([
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
