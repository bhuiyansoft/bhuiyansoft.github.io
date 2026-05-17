<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionModel extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['question_id', 'label', 'value', 'sort_order', 'is_other'];
    protected $useTimestamps = true;

    public function getByQuestion(int $questionId): array
    {
        return $this->where('question_id', $questionId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    public function syncOptions(int $questionId, array $options): void
    {
        $this->where('question_id', $questionId)->delete();

        foreach ($options as $index => $option) {
            $this->insert([
                'question_id' => $questionId,
                'label'       => $option['label'],
                'value'       => $option['value'] ?? $option['label'],
                'sort_order'  => $index,
                'is_other'    => $option['is_other'] ?? 0,
            ]);
        }
    }
}
