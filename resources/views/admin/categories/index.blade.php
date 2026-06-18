@extends('admin.layouts.app')

@section('content')
    @section('breadcrumb')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
            <li class="breadcrumb-item active">الأقسام</li>
        </ol>
    @endsection

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الأقسام</h4>
        <div class="d-flex">
            <form method="GET" class="me-2">
                <div class="input-group">
                    <input name="q" class="form-control" placeholder="بحث" value="{{ $q ?? '' }}">
                    <button class="btn btn-outline-secondary">بحث</button>
                </div>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">إضافة قسم</a>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>مفعل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                    <tr>
                        <td class="align-middle">
                            @if($cat->image_url)
                                <img src="{{ $cat->image_url }}" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px;margin-left:8px">
                            @endif
                            {{ $cat->name }}
                        </td>
                        <td class="align-middle">{{ $cat->is_active ? 'نعم' : 'لا' }}</td>
   
                        <td class="table-actions align-middle">
                 
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف?')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
@endsection
