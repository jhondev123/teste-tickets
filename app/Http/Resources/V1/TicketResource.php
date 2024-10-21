<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $situations = ["A" => "Ativo", "I" => "Inativo"];
        return [
            'id' => $this->id,
            'employee' => $this->employee->name,
            'quantity' => $this->quantity,
            'situation' => $situations[$this->situation],
            //'delivery_date' => Carbon::parse($this->delivery_date)->format('d/m/Y'),
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            'employee_data' => new EmployeeResource($this->employee),
        ];
    }
}
