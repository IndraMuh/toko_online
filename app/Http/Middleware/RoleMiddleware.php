<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ... $roles)
    {
        if (!Auth::guard('api')->check()) 
        return Response()->json([
            "status"=>false,
            "message"=>"token not found / invalid token"
        ],401);
    $user = Auth::guard('api')->user();
    foreach($roles as $role) {
        if($user->role==$role)
            return $next($request);
}
return Response()->json([
    "status"=>false,
    "message"=>"Anda tidak memiliki hak akses untuk halaman ini"
],401);
}
}
