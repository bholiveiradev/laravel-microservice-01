<?php

namespace App\Services;

use App\Services\Traits\ConsumeExternalService;

class EvaluationService
{
    use ConsumeExternalService;

    /**
     * @return mixed
     */
    public function getEvaluationsByCompanyUuid(string $uuid)
    {
        return $this->request('get', "/evaluations/{$uuid}");
    }
}
