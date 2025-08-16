<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use App\Classes\BaseController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Repositories\AuthRepository;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Exceptions\CustomException;

class AuthController extends BaseController
{
    public function __construct(protected AuthRepository $repository){}

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->repository->register($request);

            DB::commit();

            return $this->sendResponse('', "Register successfully done");
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            return $this->sendError('Something went wrong in register');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->repository->login($request);

            $data = [
                "user"  => new UserResource($data["user"]),
                "token" => $data["token"],
            ];

            return $this->sendResponse($data, "Login successfully done");

        } catch (CustomException $exception) {

            return $this->sendError($exception->getMessage());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError('Something went wrong');
        }
    }
}
