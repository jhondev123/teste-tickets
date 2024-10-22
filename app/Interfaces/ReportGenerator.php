<?php

namespace App\Interfaces;

interface ReportGenerator
{
    public function generate(array $data, string $view, string $fileName);
}
