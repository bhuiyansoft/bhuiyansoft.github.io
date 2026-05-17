<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuestionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'survey_id' => ['type' => 'INT', 'unsigned' => true],
            'type' => ['type' => 'ENUM', 'constraint' => [
                'short_text', 'long_text', 'single_choice', 'multiple_choice',
                'dropdown', 'rating', 'nps', 'date', 'email', 'number',
                'file_upload', 'matrix', 'ranking', 'yes_no'
            ]],
            'title' => ['type' => 'TEXT'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'placeholder' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_required' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'settings' => ['type' => 'JSON', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('survey_id');
        $this->forge->createTable('questions');
    }

    public function down(): void
    {
        $this->forge->dropTable('questions');
    }
}
