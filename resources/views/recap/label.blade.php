@extends('layouts.letterMaster')

@section('title')
    Label | {{ $asset->asset_name }}
@endsection

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #title {
            display: none;
        }
        .asset-card {
            border: 1px solid #000;
            border-radius: 10px;
            padding: 5px;
            width: 75mm;
            height: 37mm; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .qr-code {
            width: 40%;
            text-align: center;
            padding: 5px;
            box-sizing: border-box;
        }
        .asset-info {
            width: 55%;
            text-align: center;
            box-sizing: border-box;
        }
        .asset-info h4, .asset-info h5 {
            margin: 2px 0;
            font-size: 10pt;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 5px;
        }
        .logo img {
            width: 20px;
            height: 20px;
        }
        .logo h5 {
            margin: 0;
            margin-left: 5px;
            font-size: 8pt;
        }
        .asset-code {
            background-color: #b3d7ff;
            padding: 2px;
            margin-top: 5px;
            font-size: 8pt;
        }

        @media print {
            h4 {
                font-size: 20pt;
            }
            h5 {
                font-size: 9pt;
            }
        }
    </style>

    @section('content')
    <div class="container">
    @if(auth()->user()->group_id == 1)
        <div class="row justify-content-center">
            @foreach($codes as $code)
            <div class="col-auto mb-2">
                <div class="asset-card">
                    <!-- QR Code -->
                    <div class="qr-code">
                        {!! $qrcode !!}
                    </div>

                    <!-- Informasi -->
                    <div class="asset-info">
                        <h4><b>{{ $asset->asset_name }} <br>({{ $asset->asset_type }})</b></h4>
                        <h5 class="asset-code"> {{ $code }} </h5>
                        <div class="logo">
                            <img src="{{ asset('img/logo_utb.png') }}" alt="Logo">
                            <h5>Aset UTB</h5>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <div class="container mt-5 mb-5">
                <div class="alert alert-danger" role="alert">
                    Hanya Koordinator Aset yang dapat melabeli aset.
                </div>
            </div>
        @endif
    </div>
@endsection
