<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Helper
{
  public const LATIN_EXTENDED = "a-zA-Z0-9";
  public const LATIN_EXTENDED_WITH_SPECIAL = "a-zA-Z0-9 0-9,\;\.\-\/";

  public static function isDev()
  {
    return !App::environment(['production', 'staging']);
  }

  public static function checkRole($roles, $user = 'unset')
  {
    if($user === 'unset'){
      $user = Helper::user();
    }
    if(!is_array($roles)){
      $roles = [$roles];
    }
    if(!isset($user)){
        return false;
    }
    $userRole = $user->role;
    foreach ($roles as $key => $role) {
        $valid = false;
        switch ($role) {
            case 'fulladmin': $valid = $userRole === Role::getKey(Role::FULLADMIN); break;
            case 'admin': $valid = $userRole === Role::getKey(Role::ADMIN); break;
            case 'facilityadmin': $valid = $userRole === Role::getKey(Role::FACILITYADMIN); break;
            case 'facilityuser': $valid = $userRole === Role::getKey(Role::FACILITY); break;
            case 'nurse': $valid = $userRole === Role::getKey(Role::NURSE); break;
        }
        if($valid){
            return true;
        }
    }
    return false;
  }

  public static function user()
  {
    $user = Auth::user();
    if($user instanceof User){
      return $user;
    }
    return null;
  }

  public static function guard($ability, $object)
  {
    $allow = self::user()->can($ability, $object);
    if(!$allow){

    }
  }
  
}