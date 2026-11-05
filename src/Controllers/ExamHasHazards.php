<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Controllers;

class ExamHasHazards extends \Hubleto\Erp\Controller
{
  public function prepareView(): void
  {
    parent::prepareView();
    $this->setView('@Hubleto:App:External:WaiBlue:OhsMedicalExams/ExamHasHazards.twig');
  }
}
