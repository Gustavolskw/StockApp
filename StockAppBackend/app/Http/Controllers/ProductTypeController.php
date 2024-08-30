<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductTypeInterface;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller implements HasMiddleware
{
    use HttpResponses;
    private ProductTypeInterface $productType;


    public function __construct(ProductTypeInterface $productTypeInterface)
    {
        $this->productType = $productTypeInterface;
    }
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index'])
        ];
    }

    public function index()
    {
        $typeSrc = $this->productType->index();

        if ($typeSrc['success'] == true) {

            return $this->successResponse("", 200, $typeSrc['data']);
        } else {
            if ($typeSrc['data'] == null) {
                return $this->exceptionResponse('Erro while Fetching data', 404, $typeSrc['message']);
            }
            return $this->exceptionResponse('Erro while Fetching data', 400, $typeSrc['message']);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typeName' => ['required', 'string', 'max:45', 'unique:product_types,type_name']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }

        $validated = $validator->validate();

        $storeResp = $this->productType->store($validated);

        if ($storeResp['success'] == true) {
            return $this->successMessageResponse("Product Type Registered with success!", 201);
        } else {
            return $this->exceptionResponse("Error while inserting Product Type", 400, $storeResp['message']);
        }
    }

    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse("Invalid ID provided!", 422, $validatorId->errors());
        }

        $validator = Validator::make($request->all(), [
            'typeName' => ['required', 'string', 'max:45', 'unique:product_types,type_name']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }

        $validated = $validator->validate();
        $idvalidate = $validatorId->validate();


        $result = $this->productType->update($validated, (int)$idvalidate['id']);

        if ($result['success'] == false) {
            return $this->exceptionResponse("Error while updateing Product Type", 400, $result['message']);
        } else {
            return $this->successMessageResponse("Product Type updated with success!", 200);
        }
    }

    public function delete($id)
    {
        $validatorId = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse("Invalid ID provided!", 422, $validatorId->errors());
        }
        $idvalidate = $validatorId->validate();
        $result = $this->productType->delete($idvalidate['id']);
        if ($result['success'] == false) {
            return $this->exceptionResponse("Error while Deleting Product Type", 400, $result['message']);
        } else {
            return $this->successMessageResponse("Product Type was removed with success!", 200);
        }
    }
}
