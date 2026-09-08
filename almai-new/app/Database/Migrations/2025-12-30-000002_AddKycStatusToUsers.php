<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKycStatusToUsers extends Migration
{
    public function up()
    {
        // Column already added in CreateUsersTable
    }

    public function down()
    {
        // Nothing to do
    }
}
