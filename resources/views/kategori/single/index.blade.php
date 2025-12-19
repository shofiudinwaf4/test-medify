@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group mb-2">
                    <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
                    <a href="{{ route('kategori.print', $data->id) }}" class="btn btn-success" target="_blank">
                        Print
                    </a>
                </div>
                <div class="card">
                    <div class="card-header">Kategori</div>

                    <div class="card-body">
                        <table>
                            <tr>
                                <th>Kode</th>
                                <td>:</td>
                                <td>{{ $data->kode_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Nama Kategori</th>
                                <td>:</td>
                                <td>{{ $data->nama_kategori }}</td>
                            </tr>
                        </table>
                        <a class="btn btn-info" href="{{ url('kategori/form/edit') }}/{{ $data->id }}">Edit</a>
                        <a class="btn btn-danger" href="{{ url('kategori/delete') }}/{{ $data->id }}"
                            onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </div>
                </div>
                <div class="row mt-2">
                    @foreach ($data->items as $item)
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <th>Nama</th>
                                            <td>:</td>
                                            <td>{{ $item->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Harga Beli</th>
                                            <td>:</td>
                                            <td>{{ $item->harga_beli }}</td>
                                        </tr>
                                        <tr>
                                            <th>Laba</th>
                                            <td>:</td>
                                            <td>{{ $item->laba }}</td>
                                        </tr>
                                        <tr>
                                            <th>Harga Jual</th>
                                            <td>:</td>
                                            <td>{{ $item->harga_beli + ($item->harga_beli * $item->laba) / 100 }}</td>
                                        </tr>
                                        <tr>
                                            <th>Supplier</th>
                                            <td>:</td>
                                            <td>{{ $item->supplier }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis</th>
                                            <td>:</td>
                                            <td>{{ $item->jenis }}</td>
                                        </tr>
                                    </table>
                                    <a class="btn btn-info"
                                        href="{{ url('master-items/form/edit') }}/{{ $item->id }}">Edit</a>
                                    <a class="btn btn-danger" href="{{ url('master-items/delete') }}/{{ $item->id }}"
                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
