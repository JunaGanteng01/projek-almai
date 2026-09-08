<?php

namespace App\Commands;

use App\Models\LevelModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateCeoAccount extends BaseCommand
{
    protected $group = 'CEO';
    protected $name = 'ceo:create-account';
    protected $description = 'Membuat atau merotasi kredensial akun CEO dengan password acak yang kuat.';
    protected $usage = 'ceo:create-account [email] [name]';
    protected $arguments = ['email' => 'Default: ceo@almai.id', 'name' => 'Default: Dr. Hendra Pratama'];

    public function run(array $params)
    {
        $email = strtolower(trim((string) ($params[0] ?? 'ceo@almai.id')));
        $name = trim((string) ($params[1] ?? 'Dr. Hendra Pratama'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { CLI::error('Format email tidak valid.'); return; }

        $db = \Config\Database::connect();
        if (!$db->tableExists('users') || !$db->tableExists('levels')) { CLI::error('Tabel users/levels belum tersedia.'); return; }
        if (!$db->table('levels')->where('id', LevelModel::LEVEL_CEO)->get()->getRowArray()) { CLI::error('Level CEO belum tersedia. Jalankan CeoSeeder.'); return; }

        $password = $this->generatePassword();
        $now = date('Y-m-d H:i:s');
        $fields = array_flip($db->getFieldNames('users'));
        $candidate = ['name'=>$name,'email'=>$email,'password'=>password_hash($password,PASSWORD_DEFAULT),'level_id'=>LevelModel::LEVEL_CEO,'status'=>'active','email_verified_at'=>$now,'updated_at'=>$now];
        $data = array_intersect_key($candidate, $fields);

        $db->transStart();
        $existing = $db->table('users')->select('id')->where('email', $email)->get()->getRowArray();
        if ($existing) { $db->table('users')->where('id', $existing['id'])->update($data); $action = 'diperbarui'; }
        else { if (isset($fields['created_at'])) $data['created_at'] = $now; $db->table('users')->insert($data); $action = 'dibuat'; }
        $db->transComplete();
        if (!$db->transStatus()) { CLI::error('Akun CEO gagal disimpan.'); return; }

        CLI::write('Akun CEO berhasil ' . $action . '.', 'green');
        CLI::write('Email    : ' . $email, 'white');
        CLI::write('Password : ' . $password, 'yellow');
        CLI::write('Simpan password ini; eksekusi berikutnya akan merotasinya.', 'dark_gray');
    }

    private function generatePassword(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $random = '';
        for ($i = 0; $i < 12; $i++) $random .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        return 'CEO!' . $random . '#26';
    }
}
