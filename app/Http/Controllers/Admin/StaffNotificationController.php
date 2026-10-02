<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\StaffNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffNotificationController extends Controller
{
    /**
     * GET /admin/notifications/mine
     * جلب الإشعارات المستحقة للموظف الحالي (polling).
     */
    public function mine()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'notifications' => []]);
        }

        $notifications = StaffNotification::where('user_id', $user->id)
            ->dueUnread()
            ->latest('scheduled_at')
            ->limit(10)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'body' => $n->body,
                'icon' => $n->icon,
                'color' => $n->color,
                'scheduled_at' => $n->scheduled_at?->format('Y-m-d H:i'),
                'sender' => $n->sender?->name ?? 'الإدارة',
            ]);

        return response()->json([
            'success' => true,
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * POST /admin/notifications/{n}/read
     * تأشير الإشعار كمقروء.
     */
    public function markRead(StaffNotification $notification)
    {
        // Only the recipient can mark it read
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح.'], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * POST /admin/notifications
     * إنشاء إشعار مجدول لموظف (أو موظفين).
     * Body: { user_ids[], title, body, icon, color, scheduled_at }
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:1000',
            'icon' => 'in:bell,task,warning,info',
            'color' => 'in:green,blue,amber,red',
            'scheduled_at' => 'required|date',
            'notification_type' => 'nullable|in:once,recurring',
            'recurrence_pattern' => 'nullable|in:daily,weekly,monthly',
        ], [
            'user_ids.required' => 'اختر موظفاً على الأقل',
            'title.required' => 'عنوان الإشعار مطلوب',
            'scheduled_at.required' => 'وقت الإشعار مطلوب',
        ]);

        $created = 0;
        foreach ($data['user_ids'] as $uid) {
            StaffNotification::create([
                'user_id' => $uid,
                'sent_by' => Auth::id(),
                'title' => $data['title'],
                'body' => $data['body'] ?? null,
                'icon' => $data['icon'] ?? 'bell',
                'color' => $data['color'] ?? 'green',
                'scheduled_at' => $data['scheduled_at'],
                'notification_type' => $data['notification_type'] ?? 'once',
                'recurrence_pattern' => $data['recurrence_pattern'] ?? null,
            ]);
            $created++;
        }

        return response()->json([
            'success' => true,
            'count' => $created,
            'message' => "تم إرسال/جدولة الإشعار لـ {$created} موظف بنجاح.",
        ]);
    }

    /**
     * DELETE /admin/notifications/{n}
     * حذف إشعار (فقط من أنشأه أو نفس المستلم).
     */
    public function destroy(StaffNotification $notification)
    {
        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * GET /admin/notifications/sent
     * قائمة الإشعارات التي أرسلها المسؤول.
     */
    public function sent()
    {
        $notifications = StaffNotification::where('sent_by', Auth::id())
            ->with(['recipient:id,name'])
            ->latest('scheduled_at')
            ->limit(50)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'body' => $n->body,
                'recipient' => $n->recipient?->name,
                'scheduled_at' => $n->scheduled_at?->format('Y-m-d H:i'),
                'read_at' => $n->read_at?->format('Y-m-d H:i'),
                'is_read' => $n->isRead(),
            ]);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    // =========================================================================
    // Permission Management (Role-based)
    // =========================================================================

    /**
     * GET /admin/permissions/{role}
     * جلب صلاحيات دور معين.
     */
    public function getRolePermissions(Role $role)
    {
        return response()->json([
            'success' => true,
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ],
            'permissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }

    /**
     * PUT /admin/permissions/{role}
     * تحديث صلاحيات دور.
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|max:100',
        ]);

        $ids = [];
        foreach ($data['permissions'] ?? [] as $name) {
            $group = str_contains($name, '.') ? explode('.', $name)[0] : null;
            $perm = Permission::firstOrCreate(['name' => $name], ['group' => $group]);
            $ids[] = $perm->id;
        }

        $role->permissions()->sync($ids);

        return response()->json([
            'success' => true,
            'message' => "تم تحديث صلاحيات دور «{$role->name}» بنجاح.",
            'permissions' => $role->permissions()->pluck('name'),
        ]);
    }
}
