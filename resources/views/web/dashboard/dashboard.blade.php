@extends('web.layout.main')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> {{$title}} </h3>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center pb-4">
                <img src="{{$avatar}}" alt="profile" class="img-lg rounded-circle mb-3">
                <h3>Welcome Back</h3>
                <p>{{$message}}</p>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
<!-- Custom js for this page -->
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
<!-- End custom js for this page -->
@endsection
