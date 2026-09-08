<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WpaSeeder extends Seeder
{
    public function run()
    {
        $baseUrl = 'https://almai.id/demo-wpa/';
        
        $data = [
            [
                'name' => 'Alit Widiastika, S.E, M.H.',
                'photo' => $baseUrl . 'images/wpa/Alit Widiastika, S.E, M.H.png',
                'specialty' => 'Gold Trading',
                'experience' => '10 Tahun',
                'rating' => 4.9,
                'total_classes' => 5,
                'bio' => 'Wakil Penasihat Berjangka bersertifikat dengan keahlian di bidang Gold Trading. Berpengalaman dalam analisis pasar emas dan strategi trading yang profitable.',
                'instagram' => '@alitwidiastika',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK', 'OJK']),
                'status' => 'active',
            ],
            [
                'name' => 'Aries Yuangga, S.Si.',
                'photo' => $baseUrl . 'images/wpa/Aries Yuangga, S.Si.png',
                'specialty' => 'Forex Trading',
                'experience' => '12 Tahun',
                'rating' => 4.8,
                'total_classes' => 8,
                'bio' => 'Expert Forex Trading dengan background sains. Mengajarkan analisis teknikal dan fundamental untuk trading mata uang dengan pendekatan sistematis.',
                'instagram' => '@ariesyuangga',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'Erick Perdana Haryanto, S.Kom.',
                'photo' => $baseUrl . 'images/wpa/Erick Perdana Haryanto, S.kom.png',
                'specialty' => 'Algorithmic Trading',
                'experience' => '8 Tahun',
                'rating' => 4.9,
                'total_classes' => 6,
                'bio' => 'Spesialis Algorithmic Trading dan pengembangan Expert Advisor. Menggabungkan keahlian IT dengan trading untuk menciptakan sistem trading otomatis.',
                'instagram' => '@erickperdana',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'I Dewa Gede Sugiarta Putra, S.P.',
                'photo' => $baseUrl . 'images/wpa/I Dewa Gede Sugiarta Putra, S.P.png',
                'specialty' => 'Commodity Trading',
                'experience' => '9 Tahun',
                'rating' => 4.7,
                'total_classes' => 4,
                'bio' => 'Ahli trading komoditas dengan pengalaman di pasar berjangka Indonesia. Fokus pada CPO, kopi, dan komoditas pertanian lainnya.',
                'instagram' => '@dewasugiarta',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK', 'ICDX']),
                'status' => 'active',
            ],
            [
                'name' => 'I Gede Eka Chandra Maheswara, S.E.',
                'photo' => $baseUrl . 'images/wpa/I Gede Eka Chandra Maheswara, S.E.png',
                'specialty' => 'Index Trading',
                'experience' => '7 Tahun',
                'rating' => 4.8,
                'total_classes' => 5,
                'bio' => 'Spesialis Index Trading dengan keahlian di Hang Seng, Nikkei, dan Kospi. Mengajarkan strategi swing trading dan day trading index.',
                'instagram' => '@ekachandra',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'I Gusti Bagus Aditya, S.P.',
                'photo' => $baseUrl . 'images/wpa/I Gusti Bagus Aditya, S.P.png',
                'specialty' => 'Gold Trading',
                'experience' => '6 Tahun',
                'rating' => 4.9,
                'total_classes' => 7,
                'bio' => 'Praktisi Gold Trading dengan track record konsisten. Mengajarkan money management dan psikologi trading untuk hasil optimal.',
                'instagram' => '@gustiaditya',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'I Ketut Gede Baskara Tirtha, S.Hum.',
                'photo' => $baseUrl . 'images/wpa/I Ketut Gede Baskara Tirtha, S.Hum.png',
                'specialty' => 'Crypto Trading',
                'experience' => '5 Tahun',
                'rating' => 4.9,
                'total_classes' => 6,
                'bio' => 'Crypto enthusiast dan trader profesional. Fokus pada analisis on-chain, DeFi, dan market sentiment cryptocurrency.',
                'instagram' => '@baskaratirtha',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'I Made Dwi Wijaya, S.T.',
                'photo' => $baseUrl . 'images/wpa/I Made Dwi Wijaya, S.T.png',
                'specialty' => 'Technical Analysis',
                'experience' => '8 Tahun',
                'rating' => 4.7,
                'total_classes' => 10,
                'bio' => 'Expert Technical Analysis dengan background engineering. Mengajarkan chart pattern, candlestick, dan price action secara mendalam.',
                'instagram' => '@dwiwijaya',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'I Putu Agi Sumara Jaya, S.T.',
                'photo' => $baseUrl . 'images/wpa/I Putu Agi Sumara Jaya, S.T.png',
                'specialty' => 'Risk Management',
                'experience' => '7 Tahun',
                'rating' => 4.8,
                'total_classes' => 5,
                'bio' => 'Spesialis Risk Management dan Money Management. Mengajarkan teknik pengelolaan risiko untuk trading yang sustainable.',
                'instagram' => '@agisumara',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
            [
                'name' => 'Victor Alexander Sutio, S.Kom.',
                'photo' => $baseUrl . 'images/wpa/Victor Alexander Sutio S.Kom.png',
                'specialty' => 'Crypto Trading',
                'experience' => '6 Tahun',
                'rating' => 4.9,
                'total_classes' => 5,
                'bio' => 'Crypto Trader profesional dengan keahlian di blockchain dan Web3. Mengajarkan trading cryptocurrency dan analisis DeFi.',
                'instagram' => '@victoralexander',
                'certifications' => json_encode(['BAPPEBTI', 'LSP PBK']),
                'status' => 'active',
            ],
        ];

        $this->db->table('wpa')->insertBatch($data);
    }
}
