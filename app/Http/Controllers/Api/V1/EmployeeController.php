<?php
namespace App\Http\Controllers\Api\V1;

use App\Enums\EmployeeSituation;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Traits\HttpResponse;
use App\Utils\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public function index()
    {
        return EmployeeResource::collection(Employee::with('tickets')->limit(500)->get());
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
    public function store(Request $request)
    {
        $request->merge([
            'cpf' => Cpf::unformat($request->cpf),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf' => ['required', new \App\Rules\Cpf(), 'unique:employees'],
            'situation' => 'nullable|in:A',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados Inválidos', 422, $validator->errors());
        }

        $employee = Employee::create($validator->validated());

        if (!$employee->situation) {
            $employee->situation = (EmployeeSituation::Active)->value;
        }

        if ($employee) {
            return $this->response('Funcionário Criado com sucesso', 201, new EmployeeResource($employee));
        }

        return $this->error('Erro ao Criar o Funcionário', 400);
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
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
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
    public function update(Request $request, Employee $employee)
    {
        if (isset($request->cpf)) {
            $request->merge([
                'cpf' => Cpf::unformat($request->cpf),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'cpf' => ['nullable', new \App\Rules\Cpf(), 'unique:employees'],
            'situation' => 'nullable|in:A,I',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados Inválidos', 422, $validator->errors());
        }

        if ($employee->update($validator->validated())) {
            return $this->response('Funcionário editado com sucesso', 200, new EmployeeResource($employee));
        }

        return $this->error('Erro ao editar o funcionário', 400);
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
    public function destroy(Employee $employee)
    {
        if ($employee->delete()) {
            return $this->response('Funcionário deletado com sucesso', 204);
        }

        return $this->error('Erro ao deletar o funcionário', 400);
    }
}
