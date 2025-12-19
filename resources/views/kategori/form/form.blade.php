<form method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method == 'edit')
        <div class="form-group">
            <label>Kode Kategori</label>
            <input type="text" class="form-control" name="kode_kategori" required readonly
                value="{{ $item->kode_kategori ?? '' }}">
        </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama_kategori" required value="{{ $item->nama_kategori ?? '' }}">
    </div>
    <button class="btn btn-primary mt-3">Submit</button>

</form>
