<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOptionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'question_id' => ['type' => 'INT', 'unsigned' => true],
            'label' => ['type' => 'VARCHAR', 'constraint' => 500],
            'value' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'is_other' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('question_id');
        $this->forge->createTable('options');
    }

    public function down(): void
    {
        $this->forge->dropTable('options');
    }
}
