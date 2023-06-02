@extends('layouts.master')

@section('content')
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
        <title>Apotek Lamganda</title>
        <style>
            body {
                font-family: 'Inter', sans-serif;
                        }

            .title-text {
                font-family: 'Playfair Display', serif;
                font-size: 3rem;
            }
            .title-desc {
                color: #848484;
                font-size: 1.1em;
            }
        </style>
    </head>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="title">
                <h1 class="title-text">Manajemen Pengguna</h1>
                <p class="title-desc mt-1">Manajemen Pengguna Apotek Lamganda</p>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-6">
                <form action="/dashboard/user-management/search" method="GET">
                    <div class="input-group mb-3 mt-2">
                        <input class="i-search" type="text" name="keyword" placeholder="Cari nama pengguna" value="{{ request()->input('keyword') }}">
                        <input type="submit" class="btn btn-outline-secondary" value="Cari" style="border: 1px solid #D0D0D0;"/>
                    </div>
                </form>
            </div>

            <div>
                <a href="/dashboard/user-management/create"><button class="btn btn-success">Tambah User</button></a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th class="text-center" scope="col">Tanggal Pendaftaran</th>
                        <th class="text-center" scope="col">Update Terakhir</th>
                        <th class="text-center" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">{{ $user['id'] }}</td>
                            <td class="align-middle">{{ $user['name'] }}</td>
                            <td class="align-middle">{{ $user['email'] }}</td>
                            <td class="align-middle">{{ $user['role'] }}</td>
                            <td class="text-center">{{ $user['created_at'] }}</td>
                            <td class="text-center">{{ $user['updated_at'] }}</td>
                            <td class="text-center">
                                <a href="/dashboard/user-management/delete/{{ $user['id'] }}"><button class="btn btn-danger">Delete</button></a>
                                <a href="/dashboard/user-management/{{ $user['id'] }}"><button class="btn btn-primary">Edit</button></a>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($users) < 1) 
                        <tr>
                            <td colspan=8 class="text-center align-middle pt-5 pb-5"><h5>User Tidak Ditemukan</h5></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center" style="background: none;">
                {{$users->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>

    <script>
        var urlParams = new URLSearchParams(window.location.search);
        let queryString = urlParams.get('status');

        // Find the option in the select that has the same value as
        // the query string value and make it be selected
        document.getElementById("filter-status").querySelector("option[value='" + queryString + "']").selected = true;

    </script>
@endsection
