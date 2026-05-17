<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSurveysTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'closed', 'archived'], 'default' => 'draft'],
            'start_date' => ['type' => 'DATETIME', 'null' => true],
            'end_date' => ['type' => 'DATETIME', 'null' => true],
            'is_anonymous' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'allow_multiple_responses' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'show_progress_bar' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'show_question_numbers' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'redirect_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'thank_you_message' => ['type' => 'TEXT', 'null' => true],
            'cover_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'primary_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#4F46E5'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'response_limit' => ['type' => 'INT', 'null' => true],
            'total_responses' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'settings' => ['type' => 'JSON', 'null' => true],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('surveys');
    }

    public function down(): void
    {
        $this->forge->dropTable('surveys');
    }
}
