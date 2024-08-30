<?php

namespace App\Repository;

use App\Http\Resources\ProductTypeView;
use App\Interfaces\ProductTypeInterface;
use App\Models\ProductType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProductTypeRepository implements ProductTypeInterface
{

    public function index(): array
    {

        try {
            $data = $this->findAll();

            //dd($data->count());

            if ($data->count() === 0) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => "No Product types where found!",
                ];
            }
            return [
                'success' => true,
                'data' => $data->count() > 1 ?  ProductTypeView::collection($data) : new ProductTypeView($data),
            ];
        } catch (QueryException $queryException) {
            return [
                'success' => false,
                'message' => $queryException->getMessage()
            ];
        }
    }

    public function store(array $type): array
    {

        try {
            ProductType::create([
                'type_name' => $type['typeName']
            ]);
            return [
                'success' => true,
                'message' => "Product was Registered with success",
            ];
        } catch (QueryException $queryException) {
            return [
                'success' => false,
                'message' => $queryException->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            ProductType::find($id)->delete();
            return [
                'success' => true,
                'message' => "Product type was removed with success",
            ];
        } catch (QueryException $queryException) {
            return [
                'success' => false,
                'message' => $queryException->getMessage()
            ];
        }
    }
    public function update(array $updateData, int $id): array
    {

        try {
            $result = $this->findProductType($id);

            if ($result == null) {
                return [
                    'success' => false,
                    'message' => "Product type not found!",
                ];
            }
            $result->type_name = $updateData['typeName'];

            $result->save();

            return [
                'success' => true,
                'message' => "Product was Registered with success",
            ];
        } catch (QueryException $queryException) {
            return [
                'success' => false,
                'data' => null,
                'message' => $queryException->getMessage()
            ];
        }
    }

    private function findProductType(int $id)
    {
        try {
            return ProductType::find($id);
        } catch (QueryException $queryException) {
            Log::error($queryException);
            return null;
        }
    }

    private function findAll()
    {
        try {
            return ProductType::all();
        } catch (QueryException $queryException) {
            Log::error($queryException);
            return null;
        }
    }
}
