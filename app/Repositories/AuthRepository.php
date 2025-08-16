<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Classes\Helper;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\UserResource;

class AuthRepository
{
    public function __construct(protected User $model){}

    public function register($request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name'             => $request->name,
                'email'            => $request->email,
                'phone_number'     => $request->phone_number,
                'password'         => Hash::make($request->password),
            ]);

            DB::commit();

            return $user;
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }
    }

    public function login($request)
    {
        info("login");
        try {
            $user = $this->model->with(["roles"])->where("phone_number", $request->phone_number)->first();

            if (!$user) {
                throw new CustomException("User not found");
            }

            if (!$user->is_verified) {
                throw new CustomException("You are not verified");
            }

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                $data = [
                    "user"  => $user,
                    "token" => $token
                ];

                return $data;
            } else {
                throw new CustomException("User credential dosen't match");
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}
