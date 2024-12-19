<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Tickets\DeleteTicketAction;
use App\Actions\V1\Tickets\GetAllTicketsAction;
use App\Actions\V1\Tickets\GetTicketByIdAction;
use App\Actions\V1\Tickets\StoreTicketAction;
use App\Actions\V1\Tickets\UpdateTicketByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tickets\StoreTicketRequest;
use App\Http\Requests\Api\V1\Tickets\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    public function index(GetAllTicketsAction $action):JsonResponse
    {
        $tickets = $action->execute();
        return $this->response('Tickets listados com sucesso', 200, TicketResource::collection($tickets));

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
    public function store(StoreTicketRequest $request, StoreTicketAction $action):JsonResponse
    {
        try {
            $ticket = $action->execute($request);
            Log::info('Ticket cadastrado com sucesso', ['ticket' => $ticket]);
            return $this->response('Ticket cadastrado com sucesso', 201, new TicketResource($ticket));
        } catch (\DomainException $e) {
            Log::error("Erro ao Cadastrar um Ticket" . $e->getMessage(), ['code' => $e->getCode()]);
            return $this->error($e->getMessage(), $e->getCode());
        }


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
    public function show(string $tickedId, GetTicketByIdAction $action):JsonResponse
    {
        $ticket = $action->execute($tickedId);
        if ($ticket) {
            return $this->response('Ticket encontrado com sucesso', 200, new TicketResource($ticket));
        }

        return $this->error('Ticket não encontrado', 404);

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
    public function update(UpdateTicketRequest $request, string $ticketId, UpdateTicketByIdAction $action):JsonResponse
    {
        try {
            $ticket = $action->execute($request, $ticketId);
            Log::info('Ticket atualizado com sucesso', ['ticket' => $ticket]);
            return $this->response('Ticket atualizado com sucesso', 200, new TicketResource($ticket));
        } catch (\DomainException $e) {
            Log::error("Erro ao Atualizar um Ticket" . $e->getMessage(), ['code' => $e->getCode()]);
            return $this->error($e->getMessage(), $e->getCode());
        }

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
    public function destroy(string $ticketId, DeleteTicketAction $action):JsonResponse
    {
        try {
            $action->execute($ticketId);
            Log::info('Ticket deletado com sucesso', ['ticket_id' => $ticketId]);
            return $this->response('Ticket deletado com sucesso', 204);
        } catch (\DomainException $e) {
            Log::error("Erro ao deletar um Ticket" . $e->getMessage(), ['code' => $e->getCode()]);
            return $this->error($e->getMessage(), $e->getCode());
        }

    }

}
