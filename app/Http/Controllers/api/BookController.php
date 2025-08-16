<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\api\BookRequest;
use App\Repositories\api\BookRepository;
use App\Http\Resources\api\BookResource;

class BookController extends BaseController
{
    public function __construct(protected BookRepository $repository){}

    public function index()
    {
        try {
            $books = $this->repository->index();
            $books = BookResource::collection($books);
            return $this->sendResponse($books, 'Books fetched successfully', 200, 'books');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    public function store(BookRequest $request)
    {
        try {
            $book = $this->repository->store($request);
            $book = new BookResource($book);
            return $this->sendResponse($book, 'Book shared successfully', 201, 'book');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__("common.commonError"));
        }
    }
}
