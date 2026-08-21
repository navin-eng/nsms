---
name: UI Standards & Styling
description: Rules for styling new backend UI pages (Tables, Modals, Headers)
---

# UI Standards & Styling for Backend Pages

Whenever creating or refactoring a backend management page (e.g., Subjects, Classes, Sections), you must follow these UI styling guidelines to ensure consistency and a premium feel:

## 1. Page Header (Responsive Layout)
Always use a responsive flexbox layout for the page header so buttons stack nicely on mobile.
```html
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Page Title</h3>
        <p class="text-muted mb-0">Page description here.</p>
    </div>
    <div class="d-grid d-md-block">
        <button type="button" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Item
        </button>
    </div>
</div>
```

## 2. Table Containers
Tables should be wrapped inside a borderless, shadow-sm card with rounded corners (`rounded-4`) and overflow hidden.
```html
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead class="bg-light">
                   ...
                </thead>
                ...
            </table>
        </div>
    </div>
</div>
```

## 3. Custom CSS (Icons, Badges, Tables)
Include this `<style>` block (or add it to your global CSS) for premium elements:
```css
/* Table headers */
.table-custom th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6c757d;
    border-bottom: 2px solid #f1f3f5;
}
.table-custom td {
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
}

/* Beautiful Action Buttons */
.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}
.action-btn:hover {
    transform: translateY(-2px);
}

/* Colored Icon Boxes (for names/titles) */
.subject-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    font-size: 1.2rem;
}

/* Soft Pill Badges */
.badge.bg-primary.bg-opacity-10 {
    /* Use for primary, warning, success variants */
}
```

## 4. Horizontal Scrolling Sliders for Lists
If a table cell contains a large number of tags (e.g., Assigned Classes), use a horizontal scrolling slider instead of wrapping.
```html
<div class="classes-slider d-flex gap-1" style="max-width: 250px; overflow-x: auto; white-space: nowrap; padding-bottom: 4px;">
    <!-- Items here -->
</div>
```
With the custom scrollbar CSS:
```css
.classes-slider::-webkit-scrollbar { height: 4px; }
.classes-slider::-webkit-scrollbar-track { background: #f1f3f5; border-radius: 4px; }
.classes-slider::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 4px; }
.classes-slider::-webkit-scrollbar-thumb:hover { background: #adb5bd; }
```
