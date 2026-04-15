<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_case_model extends CI_Model
{
    protected $table = 'doctor_cases';

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
            `doctor_id` INT UNSIGNED NULL,
            `consultation_note` TEXT NULL,
            `recommended_tests` TEXT NULL,
            `status` VARCHAR(40) NOT NULL DEFAULT 'new',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_doctor_case_patient` (`patient_id`),
            KEY `idx_doctor_status` (`doctor_id`, `status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function createForPatient($patientId)
    {
        $exists = $this->db->get_where($this->table, ['patient_id' => (int) $patientId])->row();
        if ($exists) {
            return $exists->id;
        }

        $this->db->insert($this->table, [
            'patient_id' => (int) $patientId,
            'status' => 'new',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->insert_id();
    }

    public function getNewCases()
    {
        return $this->db->select('doctor_cases.*, patients.patient_code, patients.name, patients.age, patients.gender, patients.phone')
            ->from($this->table)
            ->join('patients', 'patients.id = doctor_cases.patient_id', 'inner')
            ->where('doctor_cases.status', 'new')
            ->order_by('doctor_cases.id', 'ASC')
            ->get()
            ->result();
    }

    public function getDoctorCases($doctorId)
    {
        return $this->db->select('doctor_cases.*, patients.patient_code, patients.name, patients.age, patients.gender, patients.phone')
            ->from($this->table)
            ->join('patients', 'patients.id = doctor_cases.patient_id', 'inner')
            ->where('doctor_cases.doctor_id', (int) $doctorId)
            ->order_by('doctor_cases.updated_at IS NULL, doctor_cases.updated_at DESC, doctor_cases.id DESC', '', false)
            ->get()
            ->result();
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function getCaseWithPatient($id)
    {
        return $this->db->select('doctor_cases.*, patients.patient_code, patients.name, patients.age, patients.gender, patients.phone')
            ->from($this->table)
            ->join('patients', 'patients.id = doctor_cases.patient_id', 'inner')
            ->where('doctor_cases.id', (int) $id)
            ->get()
            ->row();
    }

    public function assignToDoctor($caseId, $doctorId)
    {
        return $this->db->where('id', (int) $caseId)->update($this->table, [
            'doctor_id' => (int) $doctorId,
            'status' => 'consulting',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateConsultation($caseId, $doctorId, $note, $recommendedTests, $status)
    {
        return $this->db->where('id', (int) $caseId)->where('doctor_id', (int) $doctorId)->update($this->table, [
            'consultation_note' => $note,
            'recommended_tests' => $recommendedTests,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateStatus($caseId, $status)
    {
        return $this->db->where('id', (int) $caseId)->update($this->table, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function countByStatus($status, $doctorId = null)
    {
        if ($doctorId !== null) {
            $this->db->where('doctor_id', (int) $doctorId);
        }

        return (int) $this->db->where('status', $status)->count_all_results($this->table);
    }
}
