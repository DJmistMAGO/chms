@extends('layouts.authenticated.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Management" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Users</h3>
        <x-user-management.user-management-table />

    </div>
@endsection
