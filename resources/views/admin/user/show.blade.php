@extends('admin.layouts.main')


@section('title',$user->name)

@section('content')
    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6 d-flex align-items-center gap-2">
                        <h3 class="mb-0">{{$user->name}}</h3>
                        <a href="{{route('admin.users.edit', $user->id)}}" class="text -success"><i
                                class="fa-solid fa-pen-nib"></i></a>
                        <form action="{{route('admin.users.delete', $user->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="border-0 bg-transparent">
                                <i class="fa-solid fa-trash text-danger" role="button"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{route('admin.users.index')}}">users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">show</li>
                        </ol>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--enl::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->


                <div class="row mt-2">

                    <div class="card col-6">
                        <div class="card-header">
                            <h3 class="card-title">Responsive Hover Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">

                                <tbody>

                                <tr>
                                    <td>id</td>
                                    <td>{{$user->id}}</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <td>role</td>
                                    <td>{{$user->role}}</td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->

            <!-- /.row (main row) -->
        </div>


    </main>
@endsection
