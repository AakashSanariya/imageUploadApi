<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    
    public static function getLogin($userDetails){
        try{
            $userPassword = md5($userDetails->password);
            $userCheck = User::Select()
                ->where('email', $userDetails->email)
                ->where('password', $userPassword)
                ->first();
            if($userCheck != "NULL"){
                $token = $userCheck->createToken('Create Token')->accessToken;
                return $token;
            }
            else{
                return response();
            }
        }
        catch (ModelNotFoundException $e){
            return redirect();
        }
    }
}
