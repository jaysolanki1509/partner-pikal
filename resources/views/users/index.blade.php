@extends('partials.default')
@section('pageHeader-left')
    Users
@stop
@section('pageHeader-right')
    <a href="/users/create" class="btn btn-primary" title="Add new Users"><i class="fa fa-plus"></i>&nbsp;User</a>


@stop
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="UserTable">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Outlet</th>
                                    <th>Web Login</th>
                                    <th data-sort-ignore="true">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <?php
                                $name=ucfirst($user->user_name);
                            ?>
                            <tr class="odd gradeX">
                                <td>{{ $name or ''}}</td>
                                <td>{{ $outs[$user->id] or ''}}</td>

                                @if( $user->web_login == 0  )
                                    <?php $enable = 'False';?>
                                @else
                                    <?php $enable = 'True';?>
                                @endif

                                <td>{{ $enable }}</td>

                                <td>
                                    <a href="/users/{!! $user->id !!}/edit" title="Edit">
                                        <span class="zmdi zmdi-edit" ></span>
                                    </a>
                                </td>
                             </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif
        });
    </script>
@stop