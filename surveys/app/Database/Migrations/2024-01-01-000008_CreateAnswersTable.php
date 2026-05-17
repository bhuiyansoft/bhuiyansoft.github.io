<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnswersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'response_id' => ['type' => 'INT', 'unsigned' => true],
            'question_id' => ['type' => 'INT', 'unsigned' => true],
            'answer_value' => ['type' => 'TEXT', 'null' => true],
            'answer_values' => ['type' => 'JSON', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('response_id');
        $this->forge->addKey('question_id');
        $this->forge->createTable('survey_answers');
    }

    public function down(): void
    {
        $this->forge->dropTable('survey_answers');
    }
}
