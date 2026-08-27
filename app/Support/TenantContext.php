<?php

namespace App\Support;

use App\Models\School;
use Illuminate\Support\Facades\Auth;

class TenantContext
{
    protected static ?int $publicSchoolId = null;

    public static function setPublicSchoolId(int $schoolId)
    {
        self::$publicSchoolId = $schoolId;
    }

    public static function user()
    {
        return Auth::guard('web')->user() ?: Auth::guard('accounting')->user();
    }

    public static function school(): ?School
    {
        $user = self::user();

        if ($user && !empty($user->school_id)) {
            return $user->school ?: School::find($user->school_id);
        }

        if (self::$publicSchoolId) {
            return School::find(self::$publicSchoolId);
        }

        return null;
    }

    public static function schoolId(): ?int
    {
        return self::user()?->school_id ?: self::$publicSchoolId;
    }
}
