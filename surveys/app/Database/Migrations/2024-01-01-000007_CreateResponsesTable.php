<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResponsesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'survey_id' => ['type' => 'INT', 'unsigned' => true],
            'respondent_ip' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'respondent_email' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'respondent_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['partial', 'complete'], 'default' => 'partial'],
            'time_taken' => ['type' => 'INT', 'null' => true, 'comment' => 'seconds'],
            'user_agent' => ['type' => 'TEXT', 'null' => true],
            'referrer' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'metadata' => ['type' => 'JSON', 'null' => true],
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('survey_id');
        $this->forge->addKey('status');
        $this->forge->createTable('survey_responses');
    }

    public function down(): void
    {
        $this->forge->dropTable('survey_responses');
    }
}
