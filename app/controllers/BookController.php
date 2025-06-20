<?php
require_once __DIR__ . '/../models/Books.php';
require_once __DIR__ . '/../models/Authors.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../helpers/Validator.php';

class BookController
{
    private $bookModel;
    private $authorModel;
    private $categoryModel;

    public function __construct()
    {
        $this->bookModel = new Books();
        $this->authorModel = new Authors();
        $this->categoryModel = new Category();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = $this->bookModel->allWithPagination($_GET['page'] ?? 1, $_GET['limit'] ?? 10);

        foreach ($books as &$book) {
            $book['author_name'] = $this->authorModel->find($book['author_id'])['name'];
            $book['category_name'] = $this->categoryModel->find($book['category_id'])['name'];
        }

        http_response_code(200);
        return json_encode([
            'status' => true,
            'data' => $books,
            'message' => 'İşlem başarılı',
            'pagination' => [
                'current_page' => $_GET['page'] ?? 1,
                'total_pages' => ceil($this->bookModel->count() / ($_GET['limit'] ?? 10)),
                'per_page' => $_GET['limit'] ?? 10,
                'total_items' => $this->bookModel->count(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = $this->bookModel->find($id);

        if (!$book) {
            http_response_code(404);
            return json_encode(['message' => 'Book not found']);
        }

        http_response_code(200);
        return json_encode($book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        $validator = new Validator($data, [
            'title' => 'required|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|min:13|max:13|isbn|unique:books,isbn',
            'publication_year' => 'required|date_format:Y',
            'page_count' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            http_response_code(422);

            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->bookModel = new Books($data);
        $res = $this->bookModel->create();

        if ($res === true) {
            http_response_code(201);
            return json_encode([
                'status' => true,
                'message' => 'Book created successfully',
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
        $book = $this->bookModel->find($id);

        if (!$book) {
            http_response_code(404);
            return json_encode(['message' => 'Book not found']);
        }

        $validator = new Validator($data, [
            'title' => 'required|max:255',
            'author_id' => "required|exists:authors,id",
            'category_id' => "required|exists:categories,id",
            'isbn' => "required|min:13|max:13|unique:books,isbn,$id",
            'publication_year' => 'required|date_format:Y',
            'page_count' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            http_response_code(422);
            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->bookModel = new Books($data);
        $res = $this->bookModel->update($id);

        if ($res === true) {
            http_response_code(200);
            return json_encode([
                'status' => true,
                'message' => 'Book updated successfully',
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
        $book = $this->bookModel->find($id);

        if (!$book) {
            http_response_code(404);
            return json_encode([
                'status' => false,
                'message' => 'Book not found'
            ]);
        }

        $this->bookModel->delete($id);

        http_response_code(200);
        return json_encode([
            'status' => true,
            'message' => 'Book deleted successfully'
        ]);
    }

    /**
     * Search for books by title or ISBN.
     */
    public function search($query)
    {
        $books = $this->bookModel->search($query);

        foreach ($books as &$book) {
            $book['author_name'] = $this->authorModel->find($book['author_id'])['name'];
            $book['category_name'] = $this->categoryModel->find($book['category_id'])['name'];
        }

        http_response_code(200);
        return json_encode([
            'status' => true,
            'data' => $books,
            'message' => 'Search results',
        ]);
    }
}
