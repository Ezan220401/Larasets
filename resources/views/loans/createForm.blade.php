@extends('layouts.formMaster')
<style>
    table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  min-width: 250px;
  padding: 8px;
}

tr:nth-child(even) {background-color: #f2f2f2;}

@media (max-width: 576px) {
    #itemsTable th:nth-child(1),
    #itemsTable td:nth-child(1) {
        display: none;
    }
}
</style>



@section('title', 'Pengajuan Peminjaman')
@section('content')
                    <div class="card-body">
                        <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return inputValidate()">
                            @csrf

                            <div class="form-group">
                                <label for="applicant_name"><b>Nama Peminjam</b></label>
                                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" 
                                    name="applicant_name" id="applicant_name" value="{{ $userName ?? old('applicant_name') }}" readonly>

                                @error('applicant_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="applicant_number_id"><b>NPM atau ID Pegawai</b></label>
                                <input type="number" class="form-control @error('applicant_number_id') is-invalid @enderror" 
                                    name="applicant_number_id" id="applicant_number_id" value="{{ $userID ?? old('applicant_number_id') }}" readonly>

                                @error('applicant_number_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="applicant_position"><b>Jabatan dan Organisasi</b></label>
                                <input type="text" class="form-control @error('applicant_position') is-invalid @enderror" 
                                    name="applicant_position" id="applicant_position" minlength="8" maxlength="50" placeholder="Misal: Bendahara BEM" value="{{ old('applicant_position') }}" required>

                                @error('applicant_position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="applicant_phone"><b>Nomor Telepon Peminjam</b></label>
                                <input type="text" class="form-control @error('applicant_phone') is-invalid @enderror" 
                                    name="applicant_phone" id="applicant_phone" minlength="10" maxlength="15"
                                    placeholder="Harap mulai dengan kode negara bukan 0 (misal: 6289....)" value="{{ old('applicant_phone') ??$userPhone }}">

                                @error('applicant_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_asset_name"><b>Pilih Aset:</b></label>
                                <input type="text" class="form-control" id="loan_asset_name" placeholder="Silahkan ketik nama aset">
                                <input type="hidden" name="asset_id" id="asset_id">
                            </div>

                            <button type="button" class="btn btn-primary" onclick="addNewRow()"><i class="fas fa-plus"></i> Tambahkan ke daftar</button>
                            <div class="form-group mt-3">
                                <div class="table-responsive">
                                    <table id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Nama</th>
                                                <th>Jumlah</th>
                                                <th>Akan dipakai di:</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Bagian ini ditambahkan oleh innerHTML --> 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_desc"><b>Deskripsi Peminjaman</b></label>
                                <input type="text" class="form-control @error('loan_desc') is-invalid @enderror" minlength="10" maxlength="200"
                                    name="loan_desc" id="loan_desc" value="{{ old('loan_desc') }}"  required>

                                @error('loan_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="number_of_users"><b>Dipakai oleh berapa orang?</b></label>
                                <input type="number" class="form-control @error('number_of_users') is-invalid @enderror"
                                    name="number_of_users" value="{{ old('number_of_users') }}" required min="1">
>
                                @error('number_of_users')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_date"><b>Tanggal Mulai Pemakaian</b></label>
                                <p class="text-warning badge badge-secondary p-2">Minimal 3 hari sebelum pemakaian</p>
                                <input type="datetime-local" class="form-control @error('loan_date') is-invalid @enderror" 
                                    name="loan_date" id="loan_date" value="{{ old('loan_date') }}" required>

                                @error('loan_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="loan_length"><b>Tanggal Mulai Pengembalian</b></label>
                                <p class="text-warning badge badge-secondary p-2">Maksimal pemakaian adalah 7 hari</p>
                                <input type="datetime-local" class="form-control @error('loan_length') is-invalid @enderror" 
                                    name="loan_length" id="loan_length" value="{{ old('loan_length') }}" required>

                                @error('loan_length')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>                         

                            <button type="submit" class="btn btn-md btn-primary">SAVE</button>
                            <a href="{{ route('asset.index') }}" class="btn btn-md btn-secondary">BACK</a>

                        </form>
                    </div>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        <p class="mb-0 font-small">Hubungi bagian akademik bila butuh bantuan ;)</p>
                    </div>
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        window.onload = function(){
            //  Format datetime-local sebagai YYYY-MM-DDTHH:MM
            const now = new Date();
            now.setDate(now.getDate() + 3); 
            const dateNow = now.getFullYear() + "-" +
                            ("0" + (now.getMonth() + 1)).slice(-2) + "-" +  // Januari = 00
                            ("0" + now.getDate()).slice(-2) + "T" + 
                            ("0" + now.getHours()).slice(-2) + ":" + 
                            ("0" + now.getMinutes()).slice(-2);

            document.getElementById("loan_date").min = dateNow;
            document.getElementById("loan_length").min = dateNow;

            // Batas 7 hari setelah memilih loan_date
            document.getElementById("loan_date").addEventListener('change', function() {
                const loanDate = new Date(this.value);
                loanDate.setDate(loanDate.getDate() + 7); // Menambahkan 7 hari

                const loanLengthMax = loanDate.getFullYear() + "-" +
                                    ("0" + (loanDate.getMonth() + 1)).slice(-2) + "-" +
                                    ("0" + loanDate.getDate()).slice(-2) + "T" + 
                                    ("0" + loanDate.getHours()).slice(-2) + ":" + 
                                    ("0" + loanDate.getMinutes()).slice(-2);

                // Minimal loan_length ke tanggal loan_date yang dipilih
                const loanDateMin = this.value;

                document.getElementById("loan_length").min = loanDateMin;
                document.getElementById("loan_length").max = loanLengthMax;
            });
        };
    </script>
    <script>

        function inputValidate() {
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

            const minLeadTime = 2; 
            const maxLoanPeriod = 8; 

            const timeDifference = (loanDate - dateNow) / (1000 * 3600 * 24);
            const pastTime = (loanDate + dateNow) / (1000 * 3600 * 24);

            if (loanDate <= dateNow || loanLength <= dateNow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Tidak boleh memakai tanggal yang sudah lewat.',
                });
                return false;
            }

            if (rowCount==0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Nampaknya anda belum memasukkan pengajuan apapun kedalam daftar.',
                });
                return false;
            }

            if (loanDate == loanLength || loanDate >= loanLength) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Nampaknya ada yang salah dalam waktu pemakaian dan pengembalian.',
                });
                return false;
            }
            
            if (timeDifference < minLeadTime) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Peminjaman harus diajukan minimal 3 hari sebelum tanggal mulai.',
                });
                return false;
            }

            if (loanDate > loanLength) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Tidak boleh mengembalikan sebelum memakai.',
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

        $(function() {
            /* Tidak akan berjalan bila ada kategori aset yang hilang (harus ada:
                -Ruangan dengan id 1
                -Alat Pendingin dengan id 2
                -Barang Elektronik degan id 3
                -Alat Kelas dengan id 4
                -Alat Partisi dan Instalasi dengan id 5
                -Alat RTK dengan id 6
                -Kendaraan dengan id 7        
            // ) **/
            var availableAssets = [
                @foreach($assets as $asset)
                    {
                        label: "{{ $asset->category->category_name }}: {{ $asset->asset_name }} {{ $asset->asset_type }} tersedia {{ $asset->asset_quantity }}",
                        value: "{{ $asset->asset_id }}"
                    },
                @endforeach
            ];
            $("#loan_asset_name").autocomplete({
                source: availableAssets,
                select: function(event, ui) {
                    $("#loan_asset_name").val(ui.item.label);
                    $("#asset_id").val(ui.item.value);
                    return false;
                }
            });
        });

        let rowCount = 0; 
 
        function addNewRow() {
            let table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
            let newRow = table.insertRow();

            let assetName = document.getElementById('loan_asset_name').value;
            let assetId = document.getElementById('asset_id').value;

            if (!assetName || !assetId) {
                Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Tolong pilih item yang tersedia.'
                });
            }

            let parts = assetName.split(':');
            let categoryPart = parts[0].trim();
            let typePart = categoryPart.split(' ')[0];
            let assetType = "Peminjaman " + typePart;
            let nameAndQuantity = parts[1].trim();
            let [assetNameOnly, assetQuantity] = nameAndQuantity.split(' tersedia ').map(part => part.trim());

            // Kondisi untuk menentukan assetType jika assetNameOnly termasuk dalam daftar tertentu
            if (['Lab', 'Laboratorium', 'lab', 'laboratorium'].some(word => assetNameOnly.toLowerCase().includes(word.toLowerCase()))) {
                assetType = "Peminjaman Laboratorium";
            }

            if (rowCount < 10) {
                // input tipe peminjaman
                let cellType = newRow.insertCell(0);
                let typeInput = document.createElement("input");
                typeInput.type = "text";
                typeInput.className = "form-control loan_type";
                typeInput.name = "itemLoanTypes[]";
                typeInput.value = assetType;
                typeInput.readOnly = true;
                cellType.appendChild(typeInput);

                // input nama aset
                let cellName = newRow.insertCell(1);
                let nameInput = document.createElement("input");
                nameInput.type = "text";
                nameInput.className = "form-control";
                nameInput.name = "itemNames[]";
                nameInput.value = assetNameOnly;
                nameInput.readOnly = true;
                cellName.appendChild(nameInput);

                // input jumlah aset
                let cellQuantity = newRow.insertCell(2);
                let quantityInput = document.createElement("input");
                quantityInput.type = "number";
                quantityInput.className = "form-control loan_asset_quantity";
                quantityInput.name = "itemQuantities[]";
                quantityInput.max = assetQuantity;
                quantityInput.value = 1;
                cellQuantity.appendChild(quantityInput);

                // input lokasi peminjaman
                let cellLoanPosition = newRow.insertCell(3);
                let loanPositionInput = document.createElement("input");
                loanPositionInput.type = "text";
                loanPositionInput.className = "form-control";
                loanPositionInput.name = "itemLoanPositions[]";
                if (assetType === 'Peminjaman Ruangan' || assetType === 'Peminjaman Laboratorium' || ['Lab', 'Laboratorium', 'lab', 'Laboratorium'].includes(assetNameOnly)) {
                    loanPositionInput.value = "Ditempat";
                    loanPositionInput.readOnly = true;
                } else {
                    loanPositionInput.value = "";
                    loanPositionInput.readOnly = false;
                    loanPositionInput.required = true;
                }
                cellLoanPosition.appendChild(loanPositionInput);

                // penghapus baris
                let cellAction = newRow.insertCell(4);
                let deleteButton = document.createElement("button");
                deleteButton.type = "button";
                deleteButton.className = "btn btn-danger";
                deleteButton.textContent = "Hapus";
                deleteButton.onclick = function() {
                    newRow.remove();
                    rowCount--;
                };
                cellAction.appendChild(deleteButton);

                document.getElementById('loan_asset_name').value = '';
                document.getElementById('asset_id').value = '';

                rowCount++;
            } else {
                alert("Anda hanya dapat mengajukkan 10 peminjaman per sesi.");
            }
        }
    </script>
    @if (session('success'))
    <script>
        
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: "{{ session('success') }}",
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
        });
    </script>
    @endif
    
</body>

</html>
