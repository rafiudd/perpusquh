@extends('layouts.app')

@section('content')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> -->
    <div class="container pt-5">
        <div class="row justify-content-between align-items-center mt-3">
            <div class="col-md-6">
                <h2>Cari Buku Favorit Kamu</h2>
                <p style="font-family: 'Inter'; font-size: 18px">Buku adalah jendela dunia <br> Semua buku terbaik ada disini. Yuk mulai baca sekarang</p>

                <form action="/search" method="GET">
                    <div class="input-group mb-3 mt-2">
                        <input class="i-search" type="text" name="keyword" placeholder="Cari judul buku / penulis / penerbit" value="{{ request()->input('keyword') }}">
                        <input type="submit" class="btn btn-outline-secondary" value="Cari" style="border: 1px solid #D0D0D0;height: 50px !important; width: 70px;"/>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <img width="100%" src="https://res.cloudinary.com/sarjanalidi/image/upload/v1697855383/illus_zeukdj.png" alt="">
            </div>
        </div>
    </div>
@endsection
