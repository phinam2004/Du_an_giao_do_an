@extends('layouts.admin.app')

@section('title', translate('QR code'))

@push('css_or_js')
<style>
    .qr-wrapper{
        background: url('{{ asset('public/assets/admin/img/qr-bg.png') }}') no-repeat scroll 0% 0% / 100% 100% !important;
    }

    @media print {
       html, body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .qr-wrapper{
            background: url('{{ asset('public/assets/admin/img/qr-bg.png') }}') no-repeat scroll 0% 0% / 100% 100% !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-exact: exact !important;
        }
       img {
            display: block !important;
            visibility: visible !important;
            max-width: 100% !important;
            height: auto !important;
        }
    }
</style>
@endpush


@section('content')
    <div class="content container-fluid">
        <section class="qr-code-section">
            <div class="card">
                <div class="card-body">
                    <div class="qr-area">

                        <div class="left-side pr-xl-4">
                            <div class="col-md-12 mb-3">
                                <div class="text-center">
                                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                                           value="{{translate('Print')}}"/>
                                    <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{translate('Back')}}</a>
                                </div>
                            </div>
                            @php($restaurantLogo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()?->value)
                            <div class="" id="printableArea">

                            
                            <div class="qr-wrapper">
                                <div class="d-flex justify-content-center py-3">
                                    <a href="" class="qr-logo ratio-1" style="max-width: 100px; min-width: 70px;">
                                        <img src="{{asset('storage/app/public/qrcode/'.$data['logo'])}}" class="w-100 h-100 object-cover"
                                             onerror="this.src='{{asset('public/assets/admin/img/logo2.png')}}'" alt="">
                                    </a>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="view-menu" style="border-top: 1px solid #f7c446; border-bottom: 1px solid #f7c446; padding-bottom: .25rem; margin-top: 1rem;">
                                        {{ isset($data) ? $data['title'] : translate('title') }}
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{asset('public/assets/admin/img/scan-me.png')}}" class="mw-100" alt="">
                                    </div>
                                    <div class="my-3">
                                        {!! $code !!}
                                    </div>
                                </div>
                                <div class="subtext">
                                    <span>
                                        {{ isset($data) ? $data['description'] : translate('description') }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="open-time" style="background: #dc373f; padding: 8px 16px; margin-top: 20px; border-radius: 5px; color: #fff">
                                        <div>{{ translate('OPEN DAILY') }}</div>
                                        <div>{{ isset($data) ? $data['opening_time'] : '09:00 AM' }} - {{ isset($data) ? $data['closing_time'] : '09:00 PM' }}</div>
                                    </div>
                                </div>
                                <div class="phone-number text-center mt-3" style="border-bottom: 1px solid #ff6767; padding-bottom: .5rem;">
                                    {{ translate('PHONE NUMBER') }} : {{ isset($data) ? $data['phone'] : '+00 123 4567890' }}
                                </div>
                                <div class="row g-0 text-center bottom-txt">
                                    <div class="col-6 border-right py-3 px-2" style="border-right: 1px solid #ff6767;">
                                        {{ isset($data) ? $data['website'] : 'www.website.com' }}
                                    </div>
                                    <div class="col-6 py-3">
                                        {{ isset($data) ? $data['social_media'] : translate('@social-media-name') }}
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="right-side">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')
    {{-- <script>
        function printDiv(divName) {

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

        }
    </script> --}}
    <script>
        function printDiv(divName) {
            var content = document.getElementById(divName).cloneNode(true);
            var printWindow = window.open('', '_blank');
            var headContent = '';
            document.querySelectorAll('link[rel="stylesheet"], style').forEach(node => {
                headContent += node.outerHTML;
            });

            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print</title>
                        ${headContent}
                        <style>
                            @media print {
                                html, body {
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }
                                .qr-wrapper {
                                    background: url('{{ url('public/assets/admin/img/qr-bg.png') }}') no-repeat scroll 0% 0% / 100% 100% !important;
                                }
                                img {
                                    display: block !important;
                                    visibility: visible !important;
                                    max-width: 100% !important;
                                    height: auto !important;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${content.outerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();

            printWindow.onload = () => {
                const images = Array.from(printWindow.document.images);
                Promise.all(
                    images.map(img =>
                        img.complete ? Promise.resolve() : new Promise(res => {
                            img.onload = img.onerror = res;
                        })
                    )
                ).then(() => {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                });
            };
        }
    </script>


@endpush



