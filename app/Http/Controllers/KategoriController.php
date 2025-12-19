<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori.index.index');
    }
    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (!empty($kode)) $data_search = $data_search->where('kode_kategori', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama_kategori', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('kode_kategori', 'nama_kategori')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (!empty($kode)) {
            $data_search->where('kode_kategori', $kode);
        }

        if (!empty($nama)) {
            $data_search->where('nama_kategori', 'LIKE', '%' . $nama . '%');
        }

        $data_search = $data_search
            ->orderBy('id', 'desc')
            ->get(['kode_kategori', 'nama_kategori']);

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }
    public function singleView($kode)
    {
        $data['data'] = Kategori::with('items')->where('kode_kategori', $kode)->first();
        return view('kategori.single.index', $data);
    }
    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Kategori::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori.form.index', $data);
    }
    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new Kategori;
            $kode = Kategori::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = Kategori::find($id);
            $kode = $data_item->kode_kategori;
        }

        $data_item->kode_kategori = $kode;
        $data_item->nama_kategori = $request->nama_kategori;
        $data_item->save();

        return redirect('kategori');
    }

    public function printItems($id)
    {
        $kategori = Kategori::with('items')->findOrFail($id);

        $pdf = Pdf::loadView('kategori.print.items', [
            'kategori' => $kategori
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('items-kategori-' . $kategori->kode_kategori . '.pdf');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Kategori::find($id)->delete();
        return redirect('kategori');
    }
}
