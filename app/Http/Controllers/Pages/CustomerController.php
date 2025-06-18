<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $customer = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('contact', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.customer.index", compact("customer"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.customer.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|string",
            "contact" => "required|string",
            "address" => "required|string",
            "status" => "required|string",
        ]);

        $post = $request->all();

        Customer::create($post);

        return redirect()->route('customer')->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::find($id);
        return view("pages.customer.edit", compact("customer"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Customer::find($id);

        $request->validate([
            "name" => "required|string",
            "email" => "required|string",
            "contact" => "required|string",
            "address" => "required|string",
            "status" => "required|string",
        ]);

        $put = $request->all();

        $data->update($put);

        return redirect()->route('customer')->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data Not Found.']);
        }

        $customer->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
