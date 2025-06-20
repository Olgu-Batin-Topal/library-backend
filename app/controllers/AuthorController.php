<?php
require_once __DIR__ . '/../models/Authors.php';
require_once __DIR__ . '/../../helpers/Validator.php';

class AuthorController
{
    private $authorModel;

    public function __construct()
    {
        $this->authorModel = new Authors();
    }

    public function index()
    {
        $authors = $this->authorModel->all();

        http_response_code(200);
        return json_encode([
            'status' => true,
            'data' => $authors,
            'message' => 'İşlem başarılı',
        ]);
    }

    public function show($id)
    {
        $author = $this->authorModel->find($id);

        if (!$author) {
            http_response_code(404);
            return json_encode(['message' => 'Author not found']);
        }

        http_response_code(200);
        return json_encode($author);
    }

    public function store($data)
    {
        $validator = new Validator($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:authors,email',
        ]);

        if ($validator->fails()) {
            http_response_code(422);

            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->authorModel = new Authors($data);
        $res = $this->authorModel->create();

        if ($res === true) {
            http_response_code(201);
            return json_encode([
                'status' => true,
                'message' => 'Author created successfully',
            ]);
        } else {
            http_response_code(500);
            return json_encode([
                'status' => false,
                'message' => $res
            ]);
        }
    }

    public function update($id, $data)
    {
        $author = $this->authorModel->find($id);

        if (!$author) {
            http_response_code(404);
            return json_encode(['message' => 'Author not found']);
        }

        $validator = new Validator($data, [
            'name' => 'required|max:255',
            'email' => "required|max:255|email|unique:authors,email,$id",
        ]);

        if ($validator->fails()) {
            http_response_code(422);
            return json_encode([
                'status' => false,
                'message' => implode(', ', $validator->errors()[array_key_first($validator->errors())]),
                'errors' => $validator->errors(),
            ]);
        }

        $this->authorModel = new Authors($data);
        $res = $this->authorModel->update($id);

        if ($res === true) {
            http_response_code(200);
            return json_encode([
                'status' => true,
                'message' => 'Author updated successfully',
            ]);
        } else {
            http_response_code(500);
            return json_encode([
                'status' => false,
                'message' => $res
            ]);
        }
    }

    public function destroy($id)
    {
        $author = $this->authorModel->find($id);

        if (!$author) {
            http_response_code(404);
            return json_encode([
                'status' => false,
                'message' => 'Author not found'
            ]);
        }

        $this->authorModel->delete($id);

        http_response_code(200);
        return json_encode([
            'status' => true,
            'message' => 'Author deleted successfully'
        ]);
    }

    public function getBooksByAuthor($authorId)
    {
        $books = $this->authorModel->getBooksByAuthor($authorId);

        http_response_code(200);
        return json_encode([
            'status' => true,
            'data' => $books,
            'message' => 'Books retrieved successfully',
        ]);
    }
}
