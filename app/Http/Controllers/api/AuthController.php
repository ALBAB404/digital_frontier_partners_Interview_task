<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Classes\BaseController;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use App\Http\Requests\api\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Repositories\api\AuthRepository;
use App\Http\Requests\RegisterRequest;

class AuthController extends BaseController
{
    public function __construct(protected AuthRepository $repository){}

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->repository->register($request);

            DB::commit();

            return $this->sendResponse($user, "User registered successfully", 201);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            return $this->sendError('Something went wrong in register');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->repository->login($request);

            return $this->sendResponse($token, "Login successful");

        } catch (CustomException $exception) {

            return $this->sendError($exception->getMessage());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendError('Something went wrong');
        }
    }
}
