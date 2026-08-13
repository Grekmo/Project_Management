<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }*/

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // user()->role = l user lidayer login -> chno howa role daylo,  MATALAN (employee)
        // $roles = [employee/admin/manager]  /  in_array($role, $roles) = check if $role is in $roles
        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'You Cant Access, Unauthorized'
            ], 403);
        }
        return $next($request);
        
        /*if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }
        return response()->json([
            'message' => 'You Cant Access, Unauthorized'
        ], 403);*/
    }
    //example : in_array('admin', ['admin', 'manager']);  true /  in_array('employee', ['admin', 'manager']); // false + (!) false = true

}
