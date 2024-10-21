<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\Cpf;
class EmployeeResource extends JsonResource
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
            'name' => $this->name,
            'cpf' => Cpf::format($this->cpf),
            'situation' => $situations[$this->situation],
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at_)->format('d/m/Y H:i:s'),
            'tickets' => [
                'total_quantity' => $this->tickets->count(),
                'total_actives' => $this->tickets->where('situation', 'A')->count(),
                'total_inactives' => $this->tickets->where('situation', 'I')->count(),
                'tickets' => $this->tickets->map(function ($ticket) {
                    return [
                            'id' => $ticket->id,
                            'quantity' => $ticket->quantity,
                            'situation' => $ticket->situation,
                            'created_at' => Carbon::parse($ticket->created_at)->format('d/m/Y H:i:s'),
                            'updated_at' => Carbon::parse($ticket->updated_at)->format('d/m/Y H:i:s')
                        ];
                    }),
                ]
        ];
    }

}
