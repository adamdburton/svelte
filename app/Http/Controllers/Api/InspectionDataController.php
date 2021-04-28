<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspectionDataController extends Controller
{
    public function data()
    {
        return [
            [
                'id' => 1,
                'position' => [4, 1, 2],
                'rotation' => [0, 65, 0],
                'parts' => $this->getTurbineParts(1)
            ],
            [
                'id' => 2,
                'position' => [-8.5, 1, -4],
                'rotation' => [0, 20, 0],
                'parts' => $this->getTurbineParts(2)
            ],
            [
                'id' => 3,
                'position' => [-2, 0, -10],
                'rotation' => [0, 0, 0],
                'parts' => $this->getTurbineParts(3)
            ],
        ];
    }

    protected function getTurbineParts($turbineId)
    {
        return collect(range(1, 100))->map(function($i) use ($turbineId) {
            $issues = $this->getConditionForPart($i, $turbineId === 1);

            return [
                'id' => $i,
                'issues' => $issues
            ];
        })->all();
    }

    protected function getConditionForPart($part, $hasFaults = false)
    {
        return $hasFaults ? array_values(array_filter([
            $part % 3 === 0 ? 'Coating Damage' : null,
            $part % 5 === 0 ? 'Lightning Strike' : null,
        ])) : [];
    }
}
