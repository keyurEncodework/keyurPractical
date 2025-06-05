@extends('web.layout.main')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> {{$title}} </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <button class="btn btn-inverse-primary btn-fw"> + User</button>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="userDatatable" class="table dataTable">
                            <thead>
                                <tr>
                                    <th>Id #</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Date Of Birth</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            @csrf
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
<!-- Custom js for this page -->
    <script src="{{asset('assets/js/data-table.js')}}"></script>

<!-- End custom js for this page -->
    <script>
        var csrfToken = $("[name=_token").val();

        var table = $("#userDatatable").DataTable({
            order: [[0, 'desc']],
            ordering: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: $(window).height() - 350,
            ajax:{
                url: "{{route('users.ajax')}}",
                type: "POST",
                data: {
                    "_token" : csrfToken,
                },
            },
            column:[
                {data: 'id'},
                {data: 'full_name'},
                {data: 'email'},
                {data: 'mobile_no'},
                {data: 'date_of_birth'},
                {data: 'address', sortable:false},
                {data: 'action', sortable:false},
            ]
        });

    </script>
@endsection
