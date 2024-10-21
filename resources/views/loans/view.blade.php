@extends('layouts.master')

@section('titlePage', 'Informasi Pengajuan')
@section('title', 'Informasi Pengajuan')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />  

                <div class="card-body">
                    <!-- Informasi peminjaman -->
                    <h4><u>{{$loan->loan_name}}</u></h4>
                    <h5>Pengaju: {{$loan->applicant_name}} sebagai {{$loan->applicant_position}} [{{$loan->applicant_number_id}}]<a href="https://wa.me/{{ $loan->applicant_phone }}" class="d-inline-block mx-2">
                        <img src="{{ asset('img/whatsapp_icon.png') }}" alt="WhatsApp" style="width: 32px; height: 32px;">
                    </a></h5>
                    <p>Alasan peminjaman: {{$loan->loan_desc}}</p>
                    <hr>
                    <form action="{{ route('loans.approve', ['loan' => $loan->loan_id]) }}" method="POST" onsubmit="return approvevalidate()">
                        @csrf
                        @method('PUT')
                        
                        @foreach ($loans as $loan)
                            
                            <hr>
                            <p class="blockquote">Peminjaman <span class="text-white bg-info rounded pl-3 pr-3"> <b>{{ $loan->loan_asset_quantity }} buah {{ $loan->loan_asset_name }} </b></span>.{{ $loan->loan_desc }}. 
                                Mulai dari <mark><b>{{ $loan->translated_date ?? $loan->loan_date }}</b></mark> sampai <mark><b>{{ $loan->translated_length ?? $loan->loan_length}}</b></mark>.</p>
                                
                                @if (!$loan->is_full_approve && !$loan->is_using)
                                    <p><b>Status: </b>Menunggu semua staf terkait menyetujui</p>
                                @endif
                                @if ($loan->is_full_approve && !$loan->is_using)
                                    <p><b>Status: </b>Sudah disetujui penuh</p>
                                @endif
                                @if ($loan->is_using)
                                    <div class="col-md-5 bg-light">
                                        <p><b>Status: </b>Sudah mengambil alih</p>
                                        @if($using_evidence)
                                            <img class="img-fluid" src="{{ asset('/storage/' . $using_evidence) }}" alt="Bukti pengambilan {{ $loan->loan_asset_name }}">
                                        @else
                                            <p>Gambar tidak tersedia.</p>
                                        @endif
                                        <p><b>Keterangan: </b>{{ $using_text }}</p>
                                    </div>
                                @endif
                            @if (
                                (auth()->user()->group_id == 1 && is_null($loan->is_coordinator_approve)) ||
                                (auth()->user()->group_id == 2 && is_null($loan->is_academic_approve)) ||
                                (auth()->user()->group_id == 3 && is_null($loan->is_wr_approve)) ||
                                (auth()->user()->group_id == 4 && is_null($loan->is_pk_approve)) ||
                                (auth()->user()->group_id == 5 && is_null($loan->is_student_approve)) ||
                                ((auth()->user()->group_id == 10 || auth()->user()->group_id == 11) && is_null($loan->is_laboratory_approve))
                            )

                            <!-- Bagian tambahan -->
                            <div class="card p-1 bg-light">
                                <h5>Pengajuan yang bertabrakan</h5>
                                <p class="card p-1 bg-secondary">{{ $loanAssetName }} [ {{ $assetQuantity }} dan yang dipinjam  {{ $onLoan }}]</p>
                                @forelse ($on_request as $loan_request) 
                                    @if ($loan_request->is_full_approve == true)
                                        <p class="p-1 bg-success"><i class="pl-2 fa-solid fa-check-to-slot"></i> {{ $loan_request->loan_asset_quantity }} {{ $loanAssetName }} telah disetujui untuk {{ $loan_request->loan_date }} sampai {{ $loan_request->loan_length }}, untuk {{ $loan_request->applicant_name }}({{ $loan_request->applicant_position }})</p>
                                    @else
                                        <p class="p-1 bg-warning"><i class="pl-2 fa-solid fa-circle-exclamation"></i> Pada {{ $loan_request->loan_date }} sampai {{ $loan_request->loan_length }}, {{ $loan_request->applicant_name }}({{ $loan_request->applicant_position }}) ingin meminjam {{ $loan_request->loan_asset_quantity }} untuk {{ $loan_request->loan_desc }}</p>
                                    @endif
                                @empty
                                    <p><i class="pl-2 fa-solid fa-circle-exclamation"></i> Tidak ada peminjaman lain yang bertabrakan </p>
                                @endforelse
                            </div>

                            <!-- Bagian Persetujuan -->
                            <div class="action-button">
                                <label class="radio-inline">
                                    <input type="radio" name="approval_action[{{ $loan->loan_id }}]" value="approve"> <b class="text-white bg-success rounded pl-3 pr-3">Setuju</b>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="approval_action[{{ $loan->loan_id }}]" value="reject"> <b class="text-white bg-danger rounded pl-3 pr-3">Tolak</b>
                                </label>

                                <div class="form-group">
                                    <label for="loan_note_status">Tinggalkan Catatan</label>
                                    <input type="text" class="form-control @error('loan_note_status') is-invalid @enderror"
                                        name="loan_note_status[{{ $loan->loan_id }}]" value="{{ old('loan_note_status.' . $loan->loan_id) }}">
                                    @error('loan_note_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            @endif
                        @endforeach

                        @if(in_array(auth()->user()->group_id, [1, 2, 3, 4, 8, 9, 10, 11]) && !$loan->is_full_approve)
                            <button type="submit" class="btn btn-md btn-primary">
                                Tandai sebagai {{ $sign_group_name }}  <i class="fa-solid fa-pen-fancy"></i>
                            </button>
                        @endif
                    </form>
                    
                    <!-- surat balasan -->
                    <a href="{{ route('loans.letter', $loan->loan_id) }}" class="btn btn-info btn-md mb-3 float-right mt-2">
                        <i class="fa-solid fa-file"></i> Lihat Surat Balasan
                    </a> 
                </div>
            <hr>
            <h3>Riwayat Penilaian</h3>
            <!-- menampilkan semua penilaian -->
            <div class="approval">
            @php
                $groups = [
                    'is_coordinator_approve' => 1,
                    'is_academic_approve' => 2,
                    'is_wr_approve' => 3,
                    'is_student_approve' => 4,
                    'is_laboratory_approve' => [10, 11],
                ];

                $approvals = [];

                foreach($groups as $key => $groupIds) {
                    if (is_array($groupIds)) {
                        $group = DB::table('user_groups')
                            ->whereIn('group_id', $groupIds)
                            ->first();
                    } else {
                        $group = DB::table('user_groups')
                            ->where('group_id', $groupIds)
                            ->first();
                    }
                    
                    $groupName = $group ? $group->group_name : 'Tidak ditemukan';
                    $approvals[$key] = $groupName;
                }
            @endphp

            @foreach ($approvals as $approve => $admin)
                @if (!is_null($loan->$approve))
                    <p><i class="fa-solid fa-square-check"></i> Sudah ditandai {{ $loan->$approve }} selaku <span class="text-warning badge badge-secondary p-2">{{ $admin }}</span></p>
                @endif
            @endforeach 
            </div>
            <hr>
            

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    function approvevalidate() {
        // mengambil semua radio buttons yang terkait dengan approval
        const radios = document.querySelectorAll('input[name^="approval_action"]:checked');

        if (radios.length === 0) {
            // menampilkan peringatan jika tidak ada radio button yang dipilih
            Swal.fire({
                title: 'Tidak ada tindakan yang dipilih',
                text: 'Silakan pilih "Setuju" atau "Tolak" sebelum mengirim.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return false;
        }

        const radio = radios[0]; //yang dipilih
        const action = radio.value;

        let message = '';
        if (action === 'approve') {
            message = 'Apakah anda yakin ingin menyetujui pengajuan peminjaman ini?';
        } else if (action === 'reject') {
            message = 'Apakah anda yakin ingin menolak pengajuan peminjaman ini?';
        }

        // menampilkan dialog konfirmasi
        Swal.fire({
            title: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.value) {
                const form = document.querySelector('form');
                form.submit(); // kirim formulir setelah konfirmasi 'Yakin!'
            }
        });

        return false;
    }
 
</script>
@endsection
