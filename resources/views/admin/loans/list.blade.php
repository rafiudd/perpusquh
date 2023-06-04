@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="title">
                <h1 class="title-text">Manajemen Peminjaman</h1>
                <p class="title-desc mt-1">Manajemen Peminjaman Buku SMP N 1 Nasional</p>
            </div>
            <div>
            <a href="/dashboard/loan-management/create"><button class="btn btn-success">Tambah</button></a>

            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-6">
                <form action="/dashboard/loan-management/search" method="GET">
                    <div class="input-group mb-3 mt-2">
                        <input class="i-search" type="text" name="keyword" placeholder="Cari nama / nisn" value="{{ request()->input('keyword') }}">
                        <input type="submit" class="btn btn-outline-secondary" value="Cari" style="border: 1px solid #D0D0D0;"/>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">NISN</th>
                        <th scope="col">Meminjam Buku</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Pengembalian</th>
                        <th class="text-center" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                        <tr>
                            <td class="align-middle">{{ $loan['student']['name'] }}</td>
                            <td class="align-middle">{{ $loan['student']['nisn'] }}</td>
                            <td class="align-middle">{{ $loan['loan_items'][0]['book_title'] }}</td>
                            @if($loan['status'] == 'Telah Dikembalikan')
                                <td class="align-middle"><button disabled class="btn btn-outline-success">{{ $loan['status'] }}</button></td>
                            @else
                                <td class="align-middle"><button disabled class="btn btn-outline-danger">{{ $loan['status'] }}</button></td>
                            @endif
                            <td class="align-middle">{{ $loan['return_date'] }}</td>
                            <td class="text-center">
                                @if($loan['status'] == 'Sedang Dipinjam')
                                    <a href="/dashboard/loan-management/{{ $loan['id'] }}"><button class="btn btn-primary">Lihat Detail</button></a>
                                    <a href="/dashboard/loan-management/approve/{{ $loan['id'] }}"><button class="btn btn-success">Telah Dikembalikan</button></a>
                                @else
                                    <a href="/dashboard/loan-management/{{ $loan['id'] }}"><button class="btn btn-primary">Lihat Detail</button></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if(count($loans) < 1) 
                        <tr>
                            <td colspan=8 class="text-center align-middle pt-5 pb-5"><h5>Tidak Ada Peminjaman</h5></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center" style="background: none;">
                {{$loans->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection
