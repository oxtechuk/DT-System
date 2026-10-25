<?php

namespace App\Observers;

use App\Models\Setting;
use App\Models\TeacherCommission;
use App\Models\TeacherVisit;

class TeacherVisitObserver
{
    /**
     * يُنشئ سجل كوميشن تلقائياً عند تسجيل كل زيارة جديدة.
     */
    public function created(TeacherVisit $visit): void
    {
        $teacher = $visit->teacher;

        $baseValue = (float) Setting::get('teacher_visit_base_value', 100);
        $commissionRate = (float) $teacher->commission_rate;
        $amount = round($baseValue * ($commissionRate / 100), 2);

        TeacherCommission::create([
            'teacher_id' => $teacher->id,
            'teacher_visit_id' => $visit->id,
            'amount' => $amount,
            'commission_rate_snapshot' => $commissionRate,
            'visit_base_value_snapshot' => $baseValue,
            'status' => 'pending',
        ]);
    }
}
