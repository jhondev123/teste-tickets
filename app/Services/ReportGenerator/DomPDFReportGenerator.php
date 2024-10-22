<?php

namespace App\Services\ReportGenerator;
//use Barryvdh\DomPDF\Facade as PDF;

use App\Interfaces\ReportGenerator;
use Barryvdh\DomPDF\Facade\Pdf;

class DomPDFReportGenerator implements ReportGenerator
{
    public function generate(array $data,string $view, string $fileName)
    {
        return Pdf::loadView($view, compact('data'))->download($fileName);
    }

}
