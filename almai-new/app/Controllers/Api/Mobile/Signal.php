<?php

namespace App\Controllers\Api\Mobile;

use CodeIgniter\RESTful\ResourceController;

class Signal extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/mobile/signal
     * Coming soon - placeholder
     */
    public function index()
    {
        return $this->respond([
            'success' => true,
            'data'    => [
                'status'      => 'coming_soon',
                'title'       => 'Signal Trading',
                'description' => 'Fitur signal trading sedang dalam pengembangan. Segera hadir!',
                'providers'   => [
                    [
                        'id'          => 'plus500',
                        'name'        => 'Signal Plus 500',
                        'logo'        => base_url('images/plus500.png'),
                        'description' => 'Signal trading dari broker Plus 500',
                        'status'      => 'coming_soon',
                    ],
                    [
                        'id'          => 'gatra',
                        'name'        => 'Signal Gatra',
                        'logo'        => base_url('images/gatra.png'),
                        'description' => 'Signal trading dari Gatra Mega Berjangka',
                        'status'      => 'coming_soon',
                    ],
                ],
            ],
        ]);
    }
}
