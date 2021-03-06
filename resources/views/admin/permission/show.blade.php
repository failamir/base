@extends('base::layouts.admin')

@section('title')
  Dasboard
@endsection

@section('page-header')
  Dashboard
@endsection

@section('page-desc')
  Dashboard
@endsection

@section('content')
  <div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Permission Detail</h3></div>
    <div class="box-body">
      <admin-permission-view
        :permission="{{$permissionJson or '{}' }}"
        edit-url="{{route('admin.permission.edit', $permission)}}"
        delete-url="{{route('admin.permission.destroy', $permission)}}"
        redirect-url="{{route('admin.permission.index')}}"
        ></admin-permission-view>
    </div>
</div>
@endsection
