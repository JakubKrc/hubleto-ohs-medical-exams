<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Controllers;

use Hubleto\App\External\WaiBlue\OhsBranches\Models\Branch;
use Hubleto\App\External\WaiBlue\OhsMedicalExams\Models\Exam;
use Hubleto\App\External\WaiBlue\OhsMedicalExams\Services\ExamExport as ExamExportService;

/**
 * Filter form of "Export lekárskych prehliadok"; the period defaults to the next month (legacy).
 */
class ExamExport extends \Hubleto\Erp\Controller
{
  public function prepareView(): void
  {
    parent::prepareView();

    /** @var Exam */
    $mExam = $this->getModel(Exam::class);
    $branches = $this->getModel(Branch::class)->record->prepareReadQuery()->with(['CUSTOMER'])->orderBy('name')->get();

    $branchOptions = [];
    foreach ($branches as $branch) {
      $branchOptions[$branch->id] = ($branch->CUSTOMER->name ?? '') . ' - ' . $branch->name;
    }

    $this->viewParams['dateFrom'] = date('Y-m-01', strtotime('first day of next month'));
    $this->viewParams['dateTo'] = date('Y-m-t', strtotime('first day of next month'));
    $this->viewParams['branchOptions'] = $branchOptions;
    $this->viewParams['typeOptions'] = $mExam->getTypeLabels();
    $this->viewParams['resultOptions'] = $mExam->getResultLabels();
    $this->viewParams['any'] = ExamExportService::ANY;
    $this->setView('@Hubleto:App:External:WaiBlue:OhsMedicalExams/ExamExport.twig');
  }
}
