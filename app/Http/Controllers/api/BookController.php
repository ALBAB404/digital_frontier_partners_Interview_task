<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\api\BookRequest;
use App\Repositories\api\BookRepository;

class BookController extends BaseController
{
    public function __construct(protected BookRepository $repository){}

    public function index()
    {
        $books = $this->repository->index();

        return $this->sendResponse($books, "Books fetched successfully");
    }

    public function store(BookRequest $request)
    {
        try {
            $book = $this->repository->store($request);

            // $category = new CategoryResource($category);

            return $this->sendResponse($book, 'Book created successfully', 201);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__("common.commonError"));
        }
    }
}
