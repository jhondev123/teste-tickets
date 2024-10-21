<?php

namespace App\Http\Controllers\Api\V1;

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
            'employee_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors(), 400);
        }

        $tickets = Ticket::where('employee_id', $request->employee_id)
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->get();

        return $this->response('',200,$tickets);

    }

    public function searchAllTicketsByPeriod()
    {

    }
}
