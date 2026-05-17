<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlansTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 50],
            'price_monthly' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'price_yearly' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'max_surveys' => ['type' => 'INT', 'default' => 3],
            'max_responses_per_survey' => ['type' => 'INT', 'default' => 100],
            'max_team_members' => ['type' => 'INT', 'default' => 1],
            'max_questions_per_survey' => ['type' => 'INT', 'default' => 10],
            'features' => ['type' => 'JSON', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('plans');

        // Seed default plans
        $this->db->table('plans')->insertBatch([
            [
                'name'                       => 'Free',
                'slug'                       => 'free',
                'price_monthly'              => 0.00,
                'price_yearly'               => 0.00,
                'max_surveys'                => 3,
                'max_responses_per_survey'   => 100,
                'max_team_members'           => 1,
                'max_questions_per_survey'   => 10,
                'features'                   => json_encode(['basic_analytics', 'email_notifications']),
                'is_active'                  => 1,
                'sort_order'                 => 1,
                'created_at'                 => date('Y-m-d H:i:s'),
                'updated_at'                 => date('Y-m-d H:i:s'),
            ],
            [
                'name'                       => 'Pro',
                'slug'                       => 'pro',
                'price_monthly'              => 29.00,
                'price_yearly'               => 290.00,
                'max_surveys'                => 50,
                'max_responses_per_survey'   => 1000,
                'max_team_members'           => 5,
                'max_questions_per_survey'   => 50,
                'features'                   => json_encode(['advanced_analytics', 'export_csv', 'export_pdf', 'custom_branding', 'email_notifications', 'skip_logic']),
                'is_active'                  => 1,
                'sort_order'                 => 2,
                'created_at'                 => date('Y-m-d H:i:s'),
                'updated_at'                 => date('Y-m-d H:i:s'),
            ],
            [
                'name'                       => 'Enterprise',
                'slug'                       => 'enterprise',
                'price_monthly'              => 99.00,
                'price_yearly'               => 990.00,
                'max_surveys'                => -1,
                'max_responses_per_survey'   => -1,
                'max_team_members'           => -1,
                'max_questions_per_survey'   => -1,
                'features'                   => json_encode(['advanced_analytics', 'export_csv', 'export_pdf', 'custom_branding', 'email_notifications', 'skip_logic', 'api_access', 'white_label', 'priority_support', 'sso']),
                'is_active'                  => 1,
                'sort_order'                 => 3,
                'created_at'                 => date('Y-m-d H:i:s'),
                'updated_at'                 => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('plans');
    }
}
