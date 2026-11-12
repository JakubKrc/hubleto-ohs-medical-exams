<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Models;

use Hubleto\App\External\WaiBlue\OhsCore\Models\Hazard;
use Hubleto\Framework\Db\Column\Integer;
use Hubleto\Framework\Db\Column\Lookup;
use Hubleto\Framework\Db\Column\Virtual;

/**
 * One factor, hazard or examination of a medical exam, as submitted in the exam form (legacy lpp_risks).
 * Level 1–4 for hazard types with categories, 1 ("yes") for the others.
 */
class ExamHasHazard extends \Hubleto\Erp\Model
{
  public string $table = 'ohs_exam_has_hazards';
  public string $recordManagerClass = RecordManagers\ExamHasHazard::class;
  public ?string $lookupSqlValue = '(select name from ohs_hazards where ohs_hazards.id = {%TABLE%}.id_hazard)';
  public ?string $lookupUrlDetail = 'ohs/medical-exams/exam-has-hazards/{%ID%}';

  public array $relations = [
    'EXAM' => [ self::BELONGS_TO, Exam::class, 'id_exam', 'id' ],
    'HAZARD' => [ self::BELONGS_TO, Hazard::class, 'id_hazard', 'id' ],
  ];

  public function describeColumns(): array
  {
    /** @var Hazard */
    $mHazard = $this->getModel(Hazard::class);

    return array_merge(parent::describeColumns(), [
      'id_exam' => (new Lookup($this, $this->translate('Medical exam'), Exam::class, 'CASCADE'))->setRequired(),
      'id_hazard' => (new Lookup($this, $this->translate('Hazard'), Hazard::class))->setRequired()->setDefaultVisible(),
      'level' => (new Integer($this, $this->translate('Level')))
        ->setRequired()
        ->setDefaultValue(Hazard::LEVEL_YES)
        ->setEnumValues($mHazard->getLevelTexts())
      ,
      'virt_level' => (new Virtual($this, $this->translate('Level')))
        ->setDefaultVisible()
        ->setProperty('sql', $mHazard->getLevelTextSql($this->table))
      ,
    ]);
  }

  public function onBeforeCreate(array $record): array
  {
    $record = parent::onBeforeCreate($record);
    $this->refuseInvalidHazardLevel($record);
    return $record;
  }

  public function onBeforeUpdate(array $record): array
  {
    $record = parent::onBeforeUpdate($record);
    $this->refuseInvalidHazardLevel($record);
    return $record;
  }

  private function refuseInvalidHazardLevel(array $record): void
  {
    $idHazard = (int) ($record['id_hazard'] ?? 0);
    $level = (int) ($record['level'] ?? 0);

    // A save may send only some columns; the others keep their stored values.
    $storedRecord = $this->record->where('id', (int) ($record['id'] ?? 0))->first();
    if ($storedRecord && !array_key_exists('id_hazard', $record)) $idHazard = (int) $storedRecord->id_hazard;
    if ($storedRecord && !array_key_exists('level', $record)) $level = (int) $storedRecord->level;

    $this->getModel(Hazard::class)->refuseInvalidLevel($idHazard, $level);
  }

  public function describeTable(): \Hubleto\Framework\Description\Table
  {
    $description = parent::describeTable();
    $description->ui['addButtonText'] = $this->translate('Add hazard');
    $description->show(['header', 'fulltextSearch', 'columnSearch', 'moreActionsButton']);
    $description->hide(['footer']);
    return $description;
  }

  public function describeForm(): \Hubleto\Framework\Description\Form
  {
    return parent::describeForm();
  }
}
