<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TicketSituation;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Ticket",
 *     type="object",
 *     title="Tickets",
 *     description="Tickets",
 *     required={"id", "employee_id", "quantity", "situation"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do ticket",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="employee_id",
 *         type="integer",
 *         description="ID do funcionário associado ao ticket",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="Quantidade de tickets",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="situation",
 *         type="string",
 *         description="Situação do ticket",
 *         enum={"A", "I"},
 *         example="A"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação do ticket",
 *         example="2021-09-01T00:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização do ticket",
 *         example="2021-09-01T00:00:00"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de exclusão do ticket",
 *         example="2021-09-01T00:00:00"
 *     )
 * )
 */
class TicketController extends Controller
{
    use HttpResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/tickets",
     *     tags={"Tickets"},
     *     summary="Busca todos os tickets",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tickets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Ticket")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return TicketResource::collection(Ticket::with('employee')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tickets",
     *     tags={"Tickets"},
     *     summary="Cria um novo ticket",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"employee_id", "quantity"},
     *             @OA\Property(property="employee_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=5),
     *             @OA\Property(property="situation", type="string", enum={"A", "I"}, example="A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ticket cadastrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Ticket")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Dados Inválidos")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'quantity' => 'required|integer',
            'situation' => 'nullable|in:A',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados Inválidos', 422, $validator->errors());
        }

        $ticket = Ticket::create($validator->validated());

        if(!$ticket->situation){
            $ticket->situation = (TicketSituation::Active)->value;
        }

        if($ticket){
            return $this->response('Ticket cadastrado com sucesso', 201, new TicketResource($ticket));
        }

        return $this->error('Erro ao cadastrar ticket', 400);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tickets/{id}",
     *     tags={"Tickets"},
     *     summary="Busca o ticket pelo código",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Código do ticket",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do ticket",
     *         @OA\JsonContent(ref="#/components/schemas/Ticket")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket não encontrado")
     *         )
     *     )
     * )
     */
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);

    }

    /**
     * @OA\Put(
     *     path="/api/v1/tickets/{id}",
     *     tags={"Tickets"},
     *     summary="Atualiza o ticket",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ticket",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=5),
     *             @OA\Property(property="situation", type="string", enum={"A", "I"}, example="A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Ticket")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Ticket não encontrado",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Dados Inválidos")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'nullable|integer',
            'situation' => 'nullable|in:A,I',
        ]);

        if($validator->fails()){
            return $this->error('Dados Inválidos', 422, $validator->errors());
        }
        if($ticket->update($validator->validated())){
            return $this->response('Ticket atualizado com sucesso', 200, new TicketResource($ticket));
        }

        return $this->error('Erro ao atualizar ticket', 400);

    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tickets/{id}",
     *     tags={"Tickets"},
     *     summary="Deleta um ticket",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ticket",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Ticket deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao deletar o ticket",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao deletar o ticket")
     *         )
     *     )
     * )
     */
    public function destroy(Ticket $ticket)
    {
        if($ticket->delete()){
            return $this->response('Ticket deletado com sucesso', 204);
        }

        return $this->error('Erro ao deletar ticket', 400);

    }
}
