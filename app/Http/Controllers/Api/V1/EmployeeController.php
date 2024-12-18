<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Employee\DeleteEmployeeAction;
use App\Actions\V1\Employee\GetAllEmployeesAction;
use App\Actions\V1\Employee\GetEmployeeByIdAction;
use App\Actions\V1\Employee\StoreEmployeeAction;
use App\Actions\V1\Employee\UpdateEmployeeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Employee\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\Employee\UpdateEmployeeRequest;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *     title="Funcionários",
 *     description="Funcionários",
 *     required={"id", "name", "cpf", "situation"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do funcionário",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do funcionário",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         description="CPF do funcionário",
 *         example="12345678901"
 *     ),
 *     @OA\Property(
 *         property="situation",
 *         type="string",
 *         description="Situação do funcionário",
 *         enum={"A", "I"},
 *         example="A"
 *     ),
 *     @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *     description="Data de criação do funcionário",
 *     example="2021-09-01T00:00:00"
 *    ),
 *     @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *     description="Data de atualização do funcionário",
 *     example="2021-09-01T00:00:00"
 *   ),
 *     @OA\Property(
 *     property="deleted_at",
 *     type="string",
 *     format="date-time",
 *     description="Data de exclusão do funcionário",
 *     example="2021-09-01T00:00:00"
 *  )
 * )
 */
class EmployeeController extends Controller
{
    use HttpResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/employees",
     *     tags={"Funcionários"},
     *     summary="Busca todos os funcionários",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de funcionários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     )
     * )
     */
    public function index(GetAllEmployeesAction $action): JsonResponse
    {
        $employees = $action->execute();
        return $this->response(
            'Todos os funcionários',
            200,
            EmployeeResource::collection($employees)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/employees",
     *     tags={"Funcionários"},
     *     summary="Cria um novo funcionário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "cpf"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="cpf", type="string", example="12345678901"),
     *             @OA\Property(property="situation", type="string", enum={"A", "I"}, example="A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Funcionário cadastrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
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
    public function store(StoreEmployeeRequest $request, StoreEmployeeAction $action): JsonResponse
    {
        try {
            $employee = $action->execute($request);

            Log::info('Funcionário cadastrado com sucesso', ['employee' => $employee]);

            return $this->response(
                'Funcionário cadastrado com sucesso',
                201,
                new EmployeeResource($employee)
            );
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar o funcionário', ['error' => $e->getMessage()]);
            return $this->error('Erro ao cadastrar o funcionário', 400, ['error' => $e->getMessage()]);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/employees/{id}",
     *     tags={"Funcionários"},
     *     summary="Busca o funcionário pelo código",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Código do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do funcionário",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Funcionário não encontrado")
     *         )
     *     )
     * )
     */
    public function show(string $employee_id, GetEmployeeByIdAction $action): JsonResponse
    {
        $employee = $action->execute($employee_id);
        if (!$employee) {
            return $this->error('Funcionário não encontrado', 404, ['Employee not found']);
        }
        return $this->response(
            'Funcionário encontrado',
            200,
            new EmployeeResource($employee)
        );
    }

    /**
     * @OA\Put(
     *     path="/api/v1/employees/{id}",
     *     tags={"Funcionários"},
     *     summary="Atualiza o funcionário",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="cpf", type="string", example="12345678901"),
     *             @OA\Property(property="situation", type="string", enum={"A", "I"}, example="A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funcionário editado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
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
    public function update(
        UpdateEmployeeRequest $request,
        Employee              $employee,
        UpdateEmployeeAction  $action
    )
    {
        try {
            $employee = $action->execute($request, $employee);

            Log::info('Funcionário editado com sucesso', ['employee' => $employee]);

            return $this->response(
                'Funcionário editado com sucesso',
                200,
                new EmployeeResource($employee)
            );

        } catch (\Exception $e) {
            Log::error('Erro ao editar o funcionário', ['error' => $e->getMessage()]);
            return $this->error('Erro ao editar o funcionário', 400);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/v1/employees/{id}",
     *     tags={"Funcionários"},
     *     summary="Deleta um funcionário",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Funcionário deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao deletar o funcionário",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao deletar o funcionário")
     *         )
     *     )
     * )
     */
    public function destroy(string $employeeId, DeleteEmployeeAction $action): JsonResponse
    {
        try {
            $action->execute($employeeId);
            Log::info('Funcionário deletado com sucesso', ['employee' => $employeeId]);
            return $this->response('Funcionário deletado com sucesso', 204);
        } catch (\DomainException $e) {
            Log::error('Erro ao deletar o funcionário', ['error' => $e->getMessage()]);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
