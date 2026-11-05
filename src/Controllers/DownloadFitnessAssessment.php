<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Controllers;

use Hubleto\App\External\WaiBlue\OhsCore\Services\ProtectedFile;
use Hubleto\App\External\WaiBlue\OhsMedicalExams\Models\Exam;

/**
 * Downloads the PDF of the exam's fitness assessment; the exam must be visible to the user.
 */
class DownloadFitnessAssessment extends \Hubleto\Erp\Controller
{
  public int $returnType = self::RETURN_TYPE_STRING;

  public function renderString(): string
  {
    /** @var ProtectedFile */
    $protectedFile = $this->getService(ProtectedFile::class);
    $idExam = $this->router()->urlParamAsInteger('idExam');

    /** @var Exam */
    $mExam = $this->getModel(Exam::class);
    $exam = $mExam->record->prepareReadQuery()->where('ohs_exams.id', $idExam)->first();
    if (!$exam || (string) $exam->fitness_assessment_file == '') {
      return $protectedFile->refuse(404, $this->translate('No fitness assessment was generated yet.'));
    }

    return $protectedFile->send((string) $exam->fitness_assessment_file);
  }
}
