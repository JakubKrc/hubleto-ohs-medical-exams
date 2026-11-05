# Ohs Medical Exams

Medical exams (LPP): next exam date, planned exams, fitness assessment PDF, export, calendar.

Hubleto app of the Ohs set (occupational health service, Slovakia). Namespace: `Hubleto\App\External\WaiBlue\OhsMedicalExams`.
Needs: `wai-blue/hubleto-ohs-core`, `wai-blue/hubleto-ohs-branches`, `wai-blue/hubleto-ohs-employees`.

## Install into a Hubleto project

```bash
composer config repositories.wai-blue/hubleto-ohs-medical-exams vcs <repository-url>
composer require wai-blue/hubleto-ohs-medical-exams:dev-main
php hubleto app install "Hubleto\App\External\WaiBlue\OhsMedicalExams"
npm run build
```
