<?php

// Home

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('admin', function ($trail) {
    $trail->push('Dashboard', route('admin'));
});

// Home > About
Breadcrumbs::for('admin.sekolah', function ($trail) {
    $trail->parent('admin');
    $trail->push('Sekolah', route('admin.sekolah'));
});
Breadcrumbs::for('admin.addsekolah', function ($trail) {
    $trail->parent('admin.sekolah');
    $trail->push('Add', route('admin.addsekolah'));
});
Breadcrumbs::for('admin.updsekolah', function ($trail) {
    $trail->parent('admin.sekolah');
    $trail->push('Update', route('admin.updsekolah'));
});
