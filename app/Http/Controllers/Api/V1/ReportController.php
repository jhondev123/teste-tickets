<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Reports\SearchTicketsByEmployeeAndPeriodAction;
use App\Actions\Reports\SearchTicketsByPeriodAction;
use App\DTO\Reports\SearchTicketByEmployeeAndPeriodDTO;
use App\DTO\Reports\SearchTicketsByPeriodDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    use HttpResponse;
    public function searchTicketsByEmployeeAndPeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|integer|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors(), 400);
        }
        $dto = new SearchTicketByEmployeeAndPeriodDTO(
            $request->start_date,
            $request->end_date,
            $request->employee_id ?? null,

        );

        $tickets = SearchTicketsByEmployeeAndPeriodAction::execute($dto);


        return $this->response('Tickets',200, TicketResource::collection($tickets));
    }

    public function generateReportSearchTickets()
    {

    }

}
