<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateCwpaSlugSeeder extends Seeder
{
    public function run()
    {
        $cwpaModel = new \App\Models\CwpaModel();
        $cwpaList = $cwpaModel->findAll();
        
        foreach ($cwpaList as $cwpa) {
            if (empty($cwpa['slug'])) {
                $slug = url_title($cwpa['name'], '-', true);
                $cwpaModel->update($cwpa['id'], ['slug' => $slug]);
            }
        }
        
        echo "Slug berhasil di-generate untuk " . count($cwpaList) . " CWPA\n";
    }
}
