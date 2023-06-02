@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="title">
                <h1 class="title-text">Manajemen Buku</h1>
                <p class="title-desc mt-1">Manajemen Buku SMP N 1 Nasional</p>
            </div>
            <div>
            <a href="/dashboard/product-management/create"><button class="btn btn-success">Tambah Buku</button></a>

            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-6">
                <form action="/dashboard/product-management/search" method="GET">
                    <div class="input-group mb-3 mt-2">
                        <input class="i-search" type="text" name="keyword" placeholder="Cari nama produk" value="{{ request()->input('keyword') }}">
                        <input type="submit" class="btn btn-outline-secondary" value="Cari" style="border: 1px solid #D0D0D0;"/>
                    </div>
                </form>
            </div>

            <div>
                <form action="/dashboard/product-management/search" method="GET">
                    <div class="input-group mb-3 mt-2">
                        <input class="form-control" type="date" id="expired_date" name="expired_date" placeholder="Cari nama produk" value="{{ request()->input('expired_date') }}">
                        <input type="submit" class="btn btn-primary" value="Filter" style="border: 1px solid #D0D0D0;"/>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Nama Buku</th>
                        <th scope="col">Penulis</th>
                        <th scope="col">Penerbit</th>
                        <th scope="col">Jumlah Halaman</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $key=>$book)
                        <tr>
                            <td class="align-middle">{{ $book['title'] }}</td>
                            <td class="align-middle">{{ $book['author'] }}</td>
                            <td class="align-middle">{{ $book['publisher'] }}</td>
                            <td class="align-middle">{{ $book['total_pages'] }}</td>
                            <td class="align-middle">{{ $book['description'] }}</td>
                            <td class="align-middle">delte</td>
                        </tr>
                        @endforeach
                        @if(count($books) < 1) 
                        <tr>
                            <td colspan=8 class="text-center align-middle pt-5 pb-5"><h5>Obat Tidak Ditemukan</h5></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center" style="background: none;">
                {{$books->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection
