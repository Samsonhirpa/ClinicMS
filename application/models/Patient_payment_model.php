<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_payment_model extends CI_Model
{
    protected $table = 'patient_payments';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    public function ensureTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `patient_id` INT UNSIGNED NOT NULL,
            `payment_type` VARCHAR(40) NOT NULL,
            `amount` DECIMAL(10,2) NOT NULL,
            `currency` VARCHAR(5) NOT NULL DEFAULT 'ETB',
            `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
            `approved_by` INT UNSIGNED NULL,
            `approved_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `idx_payment_type_status` (`payment_type`, `status`),
            KEY `idx_patient` (`patient_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function create(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function getPendingByType($type)
    {
        return $this->db->select('patient_payments.*, patients.name as patient_name, patients.patient_code')
            ->from($this->table)
            ->join('patients', 'patients.id = patient_payments.patient_id', 'inner')
            ->where('patient_payments.payment_type', $type)
            ->where('patient_payments.status', 'pending')
            ->order_by('patient_payments.id', 'DESC')
            ->get()
            ->result();
    }

    public function countPendingByType($type)
    {
        return (int) $this->db->where('payment_type', $type)->where('status', 'pending')->count_all_results($this->table);
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function markStatus($id, $status, $approvedBy = null)
    {
        $payload = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($status === 'approved') {
            $payload['approved_by'] = (int) $approvedBy;
            $payload['approved_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->where('id', (int) $id)->update($this->table, $payload);
    }

    public function hasApprovedRegistration($patientId)
    {
        return $this->db->where('patient_id', (int) $patientId)
            ->where('payment_type', 'registration')
            ->where('status', 'approved')
            ->count_all_results($this->table) > 0;
    }

    public function getLatestForPatient($patientId)
    {
        return $this->db->where('patient_id', (int) $patientId)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->row();
    }

    public function getLatestByTypeForPatient($patientId, $type)
    {
        return $this->db->where('patient_id', (int) $patientId)
            ->where('payment_type', $type)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->row();
    }

    public function getOpdReadyPatients()
    {
        return $this->db->select('patients.*, MAX(patient_payments.approved_at) as approved_at')
            ->from('patients')
            ->join('patient_payments', 'patient_payments.patient_id = patients.id', 'inner')
            ->where('patient_payments.payment_type', 'registration')
            ->where('patient_payments.status', 'approved')
            ->group_by('patients.id')
            ->order_by('approved_at', 'DESC')
            ->get()
            ->result();
    }
}
