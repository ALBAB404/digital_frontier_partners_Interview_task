<?php

namespace App\Http\Controllers\admin;

use Exception;
use App\Classes\BaseController;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use App\Http\Resources\admin\UserResource;

class UserController extends BaseController
{
    public function __construct(protected UserRepository $repository){}

    public function index()
    {
        try {
            $users = $this->repository->index();
            $users = UserResource::collection($users);
            return $this->sendResponse($users, "Users fetched successfully");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError('Something went wrong in index');
        }
    }
}
