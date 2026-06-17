@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">تعديل العنوان #{{ $address->id }}</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('addresses.update', $address) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('addresses._form', ['address' => $address])

                    <button type="submit" class="btn btn-primary">تحديث العنوان</button>
                    <a href="{{ route('.addresses.index') }}" class="btn btn-secondary">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection