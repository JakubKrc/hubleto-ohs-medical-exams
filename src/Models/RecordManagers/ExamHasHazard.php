<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Models\RecordManagers;

use Hubleto\App\External\WaiBlue\OhsCore\Models\RecordManagers\Hazard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamHasHazard extends \Hubleto\Erp\RecordManager
{
  public $table = 'ohs_exam_has_hazards';

  public function EXAM(): BelongsTo
  {
    return $this->belongsTo(Exam::class, 'id_exam', 'id');
  }

  public function HAZARD(): BelongsTo
  {
    return $this->belongsTo(Hazard::class, 'id_hazard', 'id');
  }

  public function addUrlFiltersToQuery(mixed $query): mixed
  {
    $query = parent::addUrlFiltersToQuery($query);

    $hubleto = \Hubleto\Erp\Loader::getGlobalApp();
    $idExam = $hubleto->router()->urlParamAsInteger('idExam');
    if ($idExam > 0) {
      $query = $query->where($this->table . '.id_exam', $idExam);
    }

    return $query;
  }

  public function prepareLookupData(array $dataRaw): array
  {
    return parent::prepareLookupData($dataRaw);
  }
}
