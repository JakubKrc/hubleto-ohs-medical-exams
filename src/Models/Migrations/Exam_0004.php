<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Models\Migrations;

use Hubleto\Framework\Migration;

// The exam keeps the employee's job at the time of the exam (models and lookups review B1).
class Exam_0004 extends Migration
{
  public function upgradeSchema(): void
  {
    $this->db->execute("ALTER TABLE `ohs_exams` ADD COLUMN `id_job` int(8) NULL DEFAULT NULL AFTER `id_employee`");
    $this->db->execute("ALTER TABLE `ohs_exams` ADD INDEX `id_job` (`id_job`)");

    // Existing exams get the employee's current job, the best value there is.
    $this->db->execute("UPDATE `ohs_exams`
      JOIN `ohs_employees` ON `ohs_employees`.`id` = `ohs_exams`.`id_employee`
      SET `ohs_exams`.`id_job` = `ohs_employees`.`id_job`
      WHERE `ohs_exams`.`id_job` IS NULL");
  }

  public function downgradeSchema(): void
  {
    $this->db->execute("ALTER TABLE `ohs_exams` DROP COLUMN `id_job`");
  }

  public function upgradeForeignKeys(): void
  {
    $this->db->execute("ALTER TABLE `ohs_exams` ADD CONSTRAINT `fk__ohs_exams__id_job` FOREIGN KEY (`id_job`) REFERENCES `ohs_jobs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
  }

  public function downgradeForeignKeys(): void
  {
    $this->db->execute("ALTER TABLE `ohs_exams` DROP FOREIGN KEY `fk__ohs_exams__id_job`");
  }
}
