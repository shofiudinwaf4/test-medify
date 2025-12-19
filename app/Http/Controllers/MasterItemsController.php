<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\MasterItem;
use App\Exports\ItemExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = null;
        } else {
            $item = MasterItem::with('kategori')->findOrFail($id);
        }
        $kategori = Kategori::all();
        $data['kategori'] = $kategori;
        $data['item'] = $item;
        $data['method'] = $method;
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategori')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem;
            $kode = str_pad(MasterItem::count() + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $item = MasterItem::findOrFail($id);
            $kode = $item->kode;
        }

        // SIMPAN ITEM DULU
        $item->nama        = $request->nama;
        $item->harga_beli  = $request->harga_beli;
        $item->laba        = $request->laba;
        $item->supplier    = $request->supplier;
        $item->jenis       = $request->jenis;
        $item->kode        = $kode;
        $item->save(); // ⬅️ WAJIB

        // SIMPAN RELASI PIVOT
        $item->kategori()->sync($request->kategori);

        return redirect('master-items');
    }

    public function exportExcel()
    {
        return Excel::download(
            new ItemExport,
            'data-item-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function delete($id)
    {
        $item = MasterItem::findOrFail($id);

        // hapus relasi pivot dulu (opsional karena cascade)
        $item->kategori()->detach();

        // hapus item
        $item->delete();

        return redirect('master-items')->with('success', 'Item berhasil dihapus');
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}
