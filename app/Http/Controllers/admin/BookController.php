<?php

namespace App\Http\Controllers\admin;

use Exception;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\BookRepository;
use App\Http\Resources\admin\BookResource;

class BookController extends BaseController
{
    public function __construct(protected BookRepository $repository){}

    public function index()
    {
        try {
            $books = $this->repository->getAllBooks();
            $books = BookResource::collection($books);
            return $this->sendResponse($books, 'Books fetched successfully', 200, 'books');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->destroy($id);
            return $this->sendResponse(null, 'Book deleted successfully', 200);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError(__("common.commonError"));
        }
    }
}
