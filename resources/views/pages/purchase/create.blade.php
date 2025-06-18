@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@push('css')
@endpush

@section('content')
<form action="{{ route('purchase.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <a href="{{ route('purchase') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Supplier</label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($supplier as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Tanggal Pembelian</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ date('Y-m-d') }}">
                        @error('date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <a href="javascript:void(0)" id="addRow" class="btn btn-success btn-md mb-3">Tambah Product</a>

            <div class="table-responsive">
                <table class="table table-bordered text-center" id="productTable">
                    <thead>
                        <tr>
                            <th style="width: 30%">Product</th>
                            <th style="width: 20%">Jumlah</th>
                            <th style="width: 30%">Harga</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="product_id[]" class="form-control" required>
                                    <option value="">Pilih</option>
                                    @foreach ($product as $prd)
                                        <option value="{{ $prd->id }}">{{ $prd->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="qty[]" class="form-control" required value="1" min="1">
                            </td>
                            <td>
                                <input type="number" class="form-control price" readonly>
                                <input type="hidden" class="real-price">
                            </td>
                            <td>-</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2" id="totalHarga">Rp 0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="reset" class="btn btn-secondary float-start">Reset</button>
            <button type="submit" class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
</form>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        const BASE = "{{ route('purchase') }}";

        function updateTotalHarga() {
            let total = 0;
            $('#productTable tbody tr').each(function () {
                const qty = parseInt($(this).find('input[name="qty[]"]').val()) || 0;
                const harga = parseFloat($(this).find('.real-price').val()) || 0;
                total += qty * harga;
            });
            $('#totalHarga').text("Rp " + total.toLocaleString('id-ID'));
        }

        $('#addRow').on('click', function () {
            let row = `
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control" required>
                            <option value="">Pilih</option>
                            @foreach ($product as $prd)
                                <option value="{{ $prd->id }}">{{ $prd->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control" required value="1" min="1">
                    </td>
                    <td>
                        <input type="number" class="form-control price" readonly>
                        <input type="hidden" class="real-price">
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="btn btn-danger btn-md removeRow">Hapus</a>
                    </td>
                </tr>
            `;
            $('#productTable tbody').append(row);
        });

        $(document).on('click', '.removeRow', function () {
            $(this).closest('tr').remove();
            updateTotalHarga();
        });

        $(document).on("change", 'select[name="product_id[]"]', function () {
            const row = $(this).closest('tr');
            const product_id = $(this).val();

            if (!product_id) return;

            $.ajax({
                url: BASE + '/getProduct',
                method: "POST",
                dataType: "json",
                data: {
                    product_id: product_id,
                },
                success: function (response) {
                    const harga = response.data.harga_jual || 0;
                    row.find('.price').val(harga);
                    row.find('.real-price').val(harga);
                    updateTotalHarga();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });

        $(document).on("input", 'input[name="qty[]"]', function () {
            updateTotalHarga();
        });

        updateTotalHarga();
    });
</script>
@endpush
