<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'INT', 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'ENUM', 'constraint' => ['owner', 'admin', 'member', 'viewer'], 'default' => 'owner'],
            'avatar' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'pending'], 'default' => 'active'],
            'email_verified_at' => ['type' => 'DATETIME', 'null' => true],
            'reset_token' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'reset_token_expires' => ['type' => 'DATETIME', 'null' => true],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'last_login_ip' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'preferences' => ['type' => 'JSON', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['tenant_id', 'email']);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('email');
        $this->forge->createTable('users');
    }

    public function down(): void
    {
        $this->forge->dropTable('users');
    }
}
