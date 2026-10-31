<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceToanCauRbac
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string|null  ...$allowedRoles
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'));
        }

        // 1. Viewer cannot perform mutating actions (POST, PUT, PATCH, DELETE)
        if ($user->hasRole('Viewer') && ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only), không được phép thêm, sửa, xóa hoặc đổi trạng thái.');
        }

        // 2. Strict Rule: Content Admin is strictly FORBIDDEN from accessing any Leads data
        $isLeadRoute = $request->is('admin/leads*') || $request->is('admin/api/leads*');
        if ($isLeadRoute && $user->hasRole('Content Admin')) {
            abort(403, 'Quy chuẩn bảo mật Toàn Cầu: Vai trò Content Admin tuyệt đối bị chặn không được xem hoặc truy cập dữ liệu Lead bán hàng.');
        }

        // 3. If explicit allowedRoles were passed to middleware parameter
        if (! empty($allowedRoles)) {
            // Admin always has full access
            if ($user->hasRole('Admin')) {
                return $next($request);
            }

            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return $next($request);
                }
            }

            abort(403, 'Bạn không có vai trò phù hợp ('.implode(', ', $allowedRoles).') để truy cập phân hệ này.');
        }

        return $next($request);
    }
}
