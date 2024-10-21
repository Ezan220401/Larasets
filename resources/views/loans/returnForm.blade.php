@extends('layouts.formMaster')

@section('title', 'Pengembalian Aset')

@section('content')
                    <div class="card-body">
                        @if ($loan->is_using == true && $loan->is_returned == false)
                        
                        <form action="{{ route('returning.evidence', $loan->loan_id) }}" method="POST" enctype="multipart/form-data" onsubmit="return inputValidate()">
                            @csrf
                            <div class="form-group">
                                <input type="number" class="form-control @error('document_number') is-invalid @enderror"
                                    name="document_number" value="{{ $loan->loan_id ?? old('document_number') }}" required readonly>
                                @error('document_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <hr>
                            
                            <div class="form-group">
                                <label for="person_name"><b>Nama Pengembali</b></label>
                                <input type="text" class="form-control @error('person_name') is-invalid @enderror"
                                    name="person_name" value="{{ $userName ?? old('person_name') }}" required readonly>

                                <!-- error message untuk name -->
                                @error('person_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="witness_name"><b>Nama Saksi</b></label>
                                <input type="text" class="form-control @error('witness_name') is-invalid @enderror"
                                    name="witness_name" value="{{ old('witness_name') }}" required>

                                <!-- error message untuk name -->
                                @error('witness_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="witness_group"><b>Jabatan Saksi</b></label>
                                <select name="witness_group" class="form-control" required>
                                    @foreach ($groups as $group)
                                        @if(!in_array($group->group_id, [5, 6, 7]))
                                            <option value="{{ $group->group_id }}">{{ $group->group_name }}</option>
                                        @endif                                     
                                    @endforeach                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="asset_name"><b>Nama Aset yang dikembalikan</b></label>
                                <input type="text" class="form-control @error('asset_name') is-invalid @enderror"
                                    name="asset_name" value="{{ old('asset_name', $loan->loan_asset_name) }}" required readonly>

                                <!-- error message untuk name -->
                                @error('asset_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_quantity"><b>Jumlah Aset yang Dikembalikan</b></label>
                                <input type="text" class="form-control @error('asset_quantity') is-invalid @enderror"
                                    name="asset_quantity" value="{{ old('asset_quantity') }}" required>

                                <!-- error message untuk name -->
                                @error('asset_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="return_desc"><b>Deskripsi Pengembalian</b></label>
                                <input type="text" class="form-control @error('return_desc') is-invalid @enderror"
                                    placeholder="Misal: Kondisi aset saat ini" name="return_desc" value="{{ old('return_desc') }}" required>

                                <!-- error message untuk name -->
                                @error('return_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="return_date"><b>Tanggal Pengembalian</b></label>
                                <input type="datetime-local" class="form-control @error('return_date') is-invalid @enderror"
                                    name="return_date" id="return_date" value="{{ old('return_date') }}" required>

                                @error('return_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label for="evidence_image" class="form-label @error('evidence_image') is-invalid @enderror"><b>Kondisi Akhir</b></label>
                                <img class="img-preview img-fluid mb-3 col-sm-5" id="preview" style="display: none; max-width:25%">
                                <input class="form-control" type="file" id="evidence_image" name="evidence_image"
                                placeholder="Harap masukkan gambar sebagai bukti pengembalian" required>
                                @error('evidence_image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="hidden" class="form-control" name="updated_by" value="{{ $userName }} sebagai {{ $userGroup->group_name }}" readonly>
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">KIRIM</button>
                            <a href="{{ route('loans.index') }}" class="btn btn-md btn-secondary">BACK</a>
                        </form>
                        @else
                        <h3>Selesaikan Pengambilan terlebih dahulu</h3>
                        @endif
                        
                    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- include summernote js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        window.onload = function(){
            //  Format datetime-local sebagai YYYY-MM-DDTHH:MM
            const now = new Date();
            now.setDate(now.getDate()); 
            const dateNow = now.getFullYear() + "-" +
                            ("0" + (now.getMonth() + 1)).slice(-2) + "-" +  // Januari = 00
                            ("0" + now.getDate()).slice(-2) + "T" + 
                            ("0" + now.getHours()).slice(-2) + ":" + 
                            ("0" + now.getMinutes()).slice(-2);

            document.getElementById("return_date").min = dateNow;
                
        };
    </script>
    <script>
        $(document).ready(function() {
            $('#asset_image').change(function() {
                previewImage(this);
            });
        });

        function previewImage(input) {
            var preview = $('#preview')[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
    function inputValidate() {
        const returnDateInput = document.getElementById('return_date').value;
        const returnDate = new Date(returnDateInput);
        const dateNow = new Date();
        
        dateNow.setMinutes(0, 0, 0);
        if (returnDate < dateNow ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Tidak boleh memakai tanggal yang sudah lewat.',
                });
                return false;
            }
        // Tampilkan dialog konfirmasi
        Swal.fire({
            title: 'Apakah anda yakin seluruh data sudah benar?',
            text: '(Karena data akan sulit diubah)',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.value) {
                const form = document.querySelector('form');
                form.submit(); // Kirim formulir setelah konfirmasi 'Yakin!'
            }
        });
        return false;
    }

    $(document).ready(function() {
        $('#evidence_image').change(function() {
            previewImage(this);
        });
    });

    function previewImage(input) {
        var preview = $('#preview')[0];
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection