<?php
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../helpers/Validator.php';

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryModel->all();

        http_response_code(200);
        return json_encode([
            'status' => true,
            'data' => $categories,
            'message' => 'İşlem başarılı',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            http_response_code(404);
            return json_encode(['message' => 'Category not found']);
        }

        http_response_code(200);
        return json_encode($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        $validator = new Validator($data, [
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            http_response_code(422);

            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->categoryModel = new Category($data);
        $res = $this->categoryModel->create();

        if ($res === true) {
            http_response_code(201);
            return json_encode([
                'status' => true,
                'message' => 'Category created successfully',
            ]);
        } else {
            http_response_code(500);
            return json_encode([
                'status' => false,
                'message' => $res
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, $data)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            http_response_code(404);
            return json_encode(['message' => 'Category not found']);
        }

        $validator = new Validator($data, [
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            http_response_code(422);
            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->categoryModel = new Category($data);
        $res = $this->categoryModel->update($id);

        if ($res === true) {
            http_response_code(200);
            return json_encode([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);
        } else {
            http_response_code(500);
            return json_encode([
                'status' => false,
                'message' => $res
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            http_response_code(404);
            return json_encode([
                'status' => false,
                'message' => 'Category not found'
            ]);
        }

        $this->categoryModel->delete($id);

        http_response_code(200);
        return json_encode([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
