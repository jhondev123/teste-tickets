<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use App\Traits\HttpResponse;
use App\Utils\CpfFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
use HttpResponse;
    public function index()
    {
        return EmployeeResource::collection(Employee::all());
    }


    public function store(Request $request)
    {
        $request->merge([
            'cpf' => CpfFormatter::unformat($request->cpf),
        ]);

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'cpf' => 'required|size:11|unique:employees',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados Inválidos', 422, $validator->errors());
        }

        $employee = Employee::create($validator->validated());
        if($employee){
            return $this->response('Funcionário Criado', 201, new EmployeeResource($employee));
        }

        return $this->error('Erro ao Criar o Funcionário', 400);

    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
