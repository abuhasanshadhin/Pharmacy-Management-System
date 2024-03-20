<?php

namespace App\Http\Controllers;

use App\Utils;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $userId = Auth::id();
        $logged_in_user_permissions = [];
        $logged_in_user_profile = Auth::user();
        $app_options = Utils::app_option(['app_name']);

        if (is_object($logged_in_user_profile)) {
            $logged_in_user_profile->roles = DB::table('roles as r')
                ->join('role_user as ru', 'ru.role_id', '=', 'r.id')
                ->where('ru.user_id', $userId)
                ->pluck('r.name');
        }

        $logged_in_user_is_super_admin = DB::table('role_user as ru')
            ->join('roles as r', 'r.id', '=', 'ru.role_id')
            ->where('r.name', Role::SUPER_ADMIN_ROLE_NAME)
            ->where('ru.user_id', $userId)
            ->exists();

        if (!$logged_in_user_is_super_admin) {
            $logged_in_user_permissions = DB::table('role_user as ru')
                ->join('permission_role as pr', 'pr.role_id', '=', 'ru.role_id')
                ->join('permissions as p', 'p.id', '=', 'pr.permission_id')
                ->where('ru.user_id', $userId)
                ->pluck('p.name')
                ->toArray();
        }

        return view('app', compact(
            'logged_in_user_is_super_admin',
            'logged_in_user_permissions',
            'logged_in_user_profile',
            'app_options'
        ));
    }
}
