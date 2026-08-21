<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'show_in',
        'is_school',
        'target_roles',
        'target_classes',
        'target_sections',
        'target_users',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_school'      => 'boolean',
        'target_roles'   => 'array',
        'target_classes' => 'array',
        'target_sections' => 'array',
        'target_users'   => 'array',
        'published_at'   => 'datetime',
    ];

    /**
     * Scope notices for a specific student (SQLite & MySQL compatible).
     */
    public function scopeForStudent($query, $student = null)
    {
        $query->where('status', 'published');

        return $query->where(function ($q) use ($student) {
            $q->where(function ($roleSub) {
                $roleSub->whereNull('target_roles')
                    ->orWhere('target_roles', '[]')
                    ->orWhere('target_roles', 'LIKE', '%student%')
                    ->orWhere('target_roles', 'LIKE', '%Student%');
            });

            if ($student) {
                $enrollment = $student->currentEnrollment;
                if ($enrollment) {
                    $classId = (string) $enrollment->academic_class_id;
                    $sectionId = (string) $enrollment->section_id;

                    $q->where(function ($classSub) use ($classId) {
                        $classSub->whereNull('target_classes')
                            ->orWhere('target_classes', '[]')
                            ->orWhere('target_classes', 'LIKE', '%"' . $classId . '"%')
                            ->orWhere('target_classes', 'LIKE', '%' . $classId . '%');
                    });

                    if ($sectionId) {
                        $q->where(function ($secSub) use ($sectionId) {
                            $secSub->whereNull('target_sections')
                                ->orWhere('target_sections', '[]')
                                ->orWhere('target_sections', 'LIKE', '%"' . $sectionId . '"%')
                                ->orWhere('target_sections', 'LIKE', '%' . $sectionId . '%');
                        });
                    }
                }
            }
        });
    }

    /**
     * Scope notices for a specific guardian/parent (SQLite & MySQL compatible).
     */
    public function scopeForGuardian($query, $guardian = null)
    {
        $query->where('status', 'published');

        return $query->where(function ($q) use ($guardian) {
            $q->where(function ($roleSub) {
                $roleSub->whereNull('target_roles')
                    ->orWhere('target_roles', '[]')
                    ->orWhere('target_roles', 'LIKE', '%parent%')
                    ->orWhere('target_roles', 'LIKE', '%Parent%')
                    ->orWhere('target_roles', 'LIKE', '%guardian%')
                    ->orWhere('target_roles', 'LIKE', '%Guardian%');
            });

            if ($guardian) {
                $students = $guardian->students ?? collect();
                $classIds = [];
                $sectionIds = [];

                foreach ($students as $child) {
                    $enrollment = $child->currentEnrollment;
                    if ($enrollment) {
                        if ($enrollment->academic_class_id) $classIds[] = (string) $enrollment->academic_class_id;
                        if ($enrollment->section_id) $sectionIds[] = (string) $enrollment->section_id;
                    }
                }

                if (!empty($classIds)) {
                    $q->where(function ($classSub) use ($classIds) {
                        $classSub->whereNull('target_classes')
                            ->orWhere('target_classes', '[]')
                            ->orWhere(function ($orSub) use ($classIds) {
                                foreach ($classIds as $cid) {
                                    $orSub->orWhere('target_classes', 'LIKE', '%"' . $cid . '"%')
                                          ->orWhere('target_classes', 'LIKE', '%' . $cid . '%');
                                }
                            });
                    });
                }

                if (!empty($sectionIds)) {
                    $q->where(function ($secSub) use ($sectionIds) {
                        $secSub->whereNull('target_sections')
                            ->orWhere('target_sections', '[]')
                            ->orWhere(function ($orSub) use ($sectionIds) {
                                foreach ($sectionIds as $sid) {
                                    $orSub->orWhere('target_sections', 'LIKE', '%"' . $sid . '"%')
                                          ->orWhere('target_sections', 'LIKE', '%' . $sid . '%');
                                }
                            });
                    });
                }
            }
        });
    }
}
