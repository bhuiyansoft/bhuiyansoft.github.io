<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100],
            'plan_id' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'plan_expires_at' => ['type' => 'DATETIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'suspended', 'cancelled'], 'default' => 'active'],
            'logo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'primary_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#4F46E5'],
            'website' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'industry' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'size' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'settings' => ['type' => 'JSON', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('plan_id');
        $this->forge->createTable('tenants');
    }

    public function down(): void
    {
        $this->forge->dropTable('tenants');
    }
}
