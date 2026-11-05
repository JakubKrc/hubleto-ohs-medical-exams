<?php

namespace Hubleto\App\External\WaiBlue\OhsMedicalExams\Controllers;

class Exams extends \Hubleto\Erp\Controller
{
  public function prepareView(): void
  {
    parent::prepareView();
    $this->setView('@Hubleto:App:External:WaiBlue:OhsMedicalExams/Exams.twig');
  }
}
