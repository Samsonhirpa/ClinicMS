<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_request_model extends CI_Model
{
    protected $table = 'lab_requests';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    public function ensureTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `doctor_case_id` INT UNSIGNED NOT NULL,
            `patient_id` INT UNSIGNED NOT NULL,
            `requested_by` INT UNSIGNED NOT NULL,
            `test_name` VARCHAR(255) NOT NULL,
            `instructions` TEXT NULL,
            `result_text` TEXT NULL,
            `status` VARCHAR(30) NOT NULL DEFAULT 'pending_payment',
            `completed_by` INT UNSIGNED NULL,
            `completed_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `idx_lab_status` (`status`),
            KEY `idx_lab_case` (`doctor_case_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function create(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function findByCaseId($caseId)
    {
        return $this->db->get_where($this->table, ['doctor_case_id' => (int) $caseId])->row();
    }

    public function setStatus($id, $status)
    {
        return $this->db->where('id', (int) $id)->update($this->table, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getPaidRequests()
    {
        return $this->db->select('lab_requests.*, patients.patient_code, patients.name')
            ->from($this->table)
            ->join('patients', 'patients.id = lab_requests.patient_id', 'inner')
            ->where('lab_requests.status', 'paid')
            ->order_by('lab_requests.id', 'ASC')
            ->get()
            ->result();
    }

    public function getAllRequests()
    {
        return $this->db->select('lab_requests.*, patients.patient_code, patients.name')
            ->from($this->table)
            ->join('patients', 'patients.id = lab_requests.patient_id', 'inner')
            ->order_by('lab_requests.id', 'DESC')
            ->get()
            ->result();
    }

    public function complete($id, $resultText, $labUserId)
    {
        return $this->db->where('id', (int) $id)->update($this->table, [
            'result_text' => $resultText,
            'status' => 'completed',
            'completed_by' => (int) $labUserId,
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function countByStatus($status)
    {
        return (int) $this->db->where('status', $status)->count_all_results($this->table);
    }
}
