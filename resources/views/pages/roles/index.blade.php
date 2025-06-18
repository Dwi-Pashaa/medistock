@extends('layouts.app')

@section('title', 'Management Roles')

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" class="btn btn-primary">
                Tambah
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered datatable table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Roles</th>
                        <th>Aksess</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$item->name}}</td>
                            <td>
                                @forelse ($item->permissions as $permission)
                                    <span class="badge bg-primary text-white mb-2">
                                        {{$permission->name}}
                                    </span>
                                    <br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="text-center">
                                <a href="{{route('roles.permission', ['id' => $item->id])}}" class="btn btn-info btn-md">Aksess</a>
                                <a href="javascript:void(0)" onclick="return editModal('{{$item->id}}')" class="btn btn-warning btn-md">Edit</a>
                                <a href="javascript:void(0)" onclick="return deleteItem('{{$item->id}}')" class="btn btn-danger btn-md">Hapus</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span>{{ $roles->firstItem() }}</span> 
                to <span>{{ $roles->lastItem() }}</span> of
                <span>{{ $roles->total() }}</span> entries
            </p>
            <ul class="pagination m-0 ms-auto">
                {{ $roles->links() }}
            </ul>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Level</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span class="invalid-feedback error_name"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('roles') }}";

    let params = new URLSearchParams(window.location.search);
    $("#sort").change(function() {
        params.set('sort', $(this).val());
        window.location.href = BASE + '?' + params.toString();
    });

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    $("#addBtn").click(function() {
        $("#modal-simple").modal('show')
        $(".modal-title").html("Tambah Roles");
        $("#name").val("");
        $("#type").val("create");
        $("#id").val("");
    });

    $("#storeBtn").click(function() {
        let id = $("#id").val();
        let type = $("#type").val()
        let name = $("#name").val();

        let url;
        let method;

        if (type === 'create') {
            url = BASE + '/store';
            method = "POST";
        } else {
            url = BASE + `/${id}/update`
            method = "PUT";
        }
        
        $.ajax({
            url: url,
            method: method,
            data: {
                name: name
            },
        }).done(function(response) {
            if (response.errors) {
                $.each(response.errors, function(index, value) {
                    console.log(value);
                    
                    $("#name").addClass('is-invalid');
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#name").removeClass('is-invalid');
                        $(".error_" + index).html('');
                    }, 3000);
                })                
            } else {
                $("#modal-simple").modal('hide')
                Toast.fire({
                    icon: response.status,
                    title: response.message
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
        });
    });

    function editModal(id) {
        let url = BASE + `/${id}/show`
        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        }).done(function(response){
            $(".modal-title").html("Edit Roles");
            let data = response.data;
            $("#modal-simple").modal('show')

            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#type").val("update");
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
        });
    }

    function deleteItem(id) {
        Swal.fire({
            title: "Peringatan !",
            text: "Anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/' + id + '/destroy',
                    method: "DELETE",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: "error",
                            title: "Server Error"
                        });
                    }
                })
            }
        });
    }
</script>
@endpush