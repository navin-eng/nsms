<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'value', 'target', 'parent_id', 'order', 'css_class'
    ];

    public function parent()
    {
        return $this->belongsTo(NavbarItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavbarItem::class, 'parent_id')->orderBy('order');
    }

    public function getActualUrl()
    {
        if ($this->type == 'route') {
            return \Illuminate\Support\Facades\Route::has($this->value) ? route($this->value) : '#';
        } elseif ($this->type == 'page') {
            $page = Page::find($this->value);
            return $page ? route('page', $page->slug) : '#';
        } elseif ($this->type == 'custom') {
            return $this->value ?? '#';
        }
        return '#';
    }
}
