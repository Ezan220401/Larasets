@extends('layouts.formMaster')

@section('title', 'Edit Peminjaman')

@section('content')
                @if(Auth::user()->user_number_id == $userNumberId)
                    <div class="card-body">
                    <form action="{{ route('loans.update', $loan->loan_id) }}" method="POST" enctype="multipart/form-data" onsubmit="return dateValidate()">
                        @csrf
                        @method('PUT')
                            <div class="form-group">
                                    <label for="loan_name" class="form-label"><b>Nama Peminjaman</b></label>
                                    <select class="form-control @error('loan_name') is-invalid @enderror" id="loan_name" name="loan_name" aria-label="Default select example" readonly>
                                        <option value="Peminjaman Alat" {{ $loan->loan_name ==  'Peminjaman Alat' ? 'selected' : '' }}>Peminjaman Alat</option>
                                        <option value="Peminjaman Barang" {{ $loan->loan_name ==  'Peminjaman Barang' ? 'selected' : '' }}>Peminjaman Barang</option>
                                        <option value="Peminjaman Kendaraan" {{ $loan->loan_name ==  'Peminjaman Kendaraan' ? 'selected' : '' }}>Peminjaman Kendaraan</option>
                                        <option value="Peminjaman Ruangan" {{ $loan->loan_name ==  'Peminjaman Ruangan' ? 'selected' : '' }}>Peminjaman Ruangan</option>
                                        <option value="Peminjaman Laboratorium" {{ $loan->loan_name ==  'Peminjaman Laboratorium' ? 'selected' : '' }}>Peminjaman Laboratorium</option>
                                    </select>
                                <!-- error message-->
                                @error('loan_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_asset_name" class="form-label"><b>Nama Aset</b></label>
                                <input type="text" class="form-control @error('loan_asset_name') is-invalid @enderror"
                                    name="loan_asset_name" value="{{ old('loan_asset_name', $loan->loan_asset_name) }}" required readonly>

                                <!-- error message-->
                                @error('loan_asset_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_asset_quantity" class="form-label"><b>Jumlah Aset</b></label> <p class="text-warning badge badge-secondary p-2">Maximal {{ $assetQuantity }}</p>
                                <input type="number" class="form-control @error('loan_asset_quantity') is-invalid @enderror"
                                    name="loan_asset_quantity" max="{{ $assetQuantity }}" value="{{ old('loan_asset_quantity', $loan->loan_asset_quantity) }}" required>

                                <!-- error message-->
                                @error('loan_asset_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_desc" class="form-label"><b>Alasan Peminjaman</b></label>
                                <input type="text" class="form-control @error('loan_desc') is-invalid @enderror"
                                    name="loan_desc" value="{{ $loanDesc }}" required>

                                <!-- error message-->
                                @error('loan_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="number_of_users" class="form-label"><b>Dipakai Oleh Berapa Orang?</b></label>
                                <p class="text-warning badge badge-secondary p-2">Dapat berpengaruh dalam peminjaman Kendaraan, Ruangan dan Laboratorium</p>
                                <input type="number" class="form-control @error('number_of_users') is-invalid @enderror"
                                    name="number_of_users" value="{{ old('number_of_users', $loan->number_of_users) }}" required>

                                <!-- error message-->
                                @error('number_of_users')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_position" class="form-label"><b>Dipakai di</b></label>
                                <input type="text" class="form-control @error('loan_position') is-invalid @enderror"
                                    name="loan_position" value="{{ old('loan_position', $loan->loan_position) }}" id="loan_position"
                                    @if ($loan->loan_position == 'Ditempat') readonly @endif required>

                                <!-- error message-->
                                @error('loan_position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_date" class="form-label"><b><b>Rencana Dipakai Pada</b></b></label>
                                <p class="text-warning badge badge-secondary p-2">Minimal 3 hari sebelum pemakaian</p>
                                <input type="datetime-local" class="form-control @error('loan_date') is-invalid @enderror" 
                                    name="loan_date" id="loan_date" value="{{ old('loan_date', $loan->loan_date) }}" required>

                                @error('loan_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_length" class="form-label"><b>Rencana Dikembalikan Pada</b></label>
                                <p class="text-warning badge badge-secondary p-2">Minimal 7 hari pemakaian</p>
                                <input type="datetime-local" class="form-control @error('loan_length') is-invalid @enderror" 
                                    name="loan_length" id="loan_length" value="{{ old('loan_length', $loan->loan_length) }}" required>

                                @error('loan_length')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <button type="submit" class="btn btn-md btn-primary">UPDATE</button>
                            <a href="{{ route('loans.index') }}" class="btn btn-md btn-secondary">BACK</a>
                        </form>
                    </div>
                    @else
                        <div class="container mt-5 mb-5">
                            <div class="alert alert-danger" role="alert">
                                Hanya Pengaju yang dapat mengedit pengajuan.
                            </div>
                        </div>
                    @endif
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- include summernote js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        function dateValidate() {
            const loanDateInput = document.getElementById('loan_date').value;
            const loanLengthInput = document.getElementById('loan_length').value;

            if (!loanDateInput || !loanLengthInput) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan lengkapi tanggal peminjaman dan pengembalian',
                });
                return false;
            }

            const loanDate = new Date(loanDateInput);
            const loanLength = new Date(loanLengthInput);
            const dateNow = new Date();

            if (loanDate < dateNow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tanggal mulai peminjaman tidak boleh di masa lalu',
                });
                return false;
            }

            const minLeadTime = 2; 
            const maxLoanPeriod = 7; 

            const timeDifference = (loanDate - dateNow) / (1000 * 3600 * 24);
            const pastTime = (loanDate + dateNow) / (1000 * 3600 * 24);

            if (timeDifference < minLeadTime) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Peminjaman harus diajukan minimal 3 hari sebelum tanggal mulai.',
                });
                return false;
            }

            if (loanDate <= dateNow || loanLength <= dateNow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Tidak boleh memakai tanggal yang sudah lewat.',
                });
                return false;
            }

            const loanPeriod = (loanLength - loanDate) / (1000 * 3600 * 24);

            if (loanPeriod >= maxLoanPeriod) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Masa peminjaman maksimal adalah 7 hari.',
            });
            return false;
        }

        // Tampilkan dialog konfirmasi
        Swal.fire({
            title: 'Apakah anda yakin seluruh data sudah benar?',
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

        return false
    }
    </script>
</body>

</html>
