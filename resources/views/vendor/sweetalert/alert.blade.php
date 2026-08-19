@if (config('sweetalert.alwaysLoadJS') === true && config('sweetalert.neverLoadJS') === false )
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js')  }}"></script>
@endif
@if (Session::has('alert.config'))
    @if(config('sweetalert.animation.enable'))
        <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
    @endif
    @if (config('sweetalert.alwaysLoadJS') === false && config('sweetalert.neverLoadJS') === false)
        <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js')  }}"></script>
    @endif
    <style>
        .swal2-popup.codex-toast-popup {
            width: min(420px, calc(100vw - 24px)) !important;
            padding: 16px 18px !important;
            border-radius: 18px !important;
            background: rgba(255, 255, 255, 0.98) !important;
            border: 1px solid rgba(21, 115, 71, 0.10) !important;
            box-shadow: 0 18px 50px rgba(19, 34, 56, 0.16) !important;
        }

        .swal2-popup.codex-toast-popup .swal2-title.codex-toast-title {
            margin: 0 !important;
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #17324d !important;
        }

        .swal2-popup.codex-toast-popup .swal2-html-container.codex-toast-content {
            margin: 6px 0 0 !important;
            font-size: 13px !important;
            line-height: 1.6 !important;
            color: #57718f !important;
        }

        .swal2-popup.codex-toast-popup .swal2-icon {
            margin: 0 12px 0 0 !important;
            transform: scale(.72);
        }

        .swal2-popup.codex-toast-popup .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #198754 0%, #52b788 100%) !important;
        }
    </style>
    <script>
        (() => {
            const swalConfig = {!! Session::pull('alert.config') !!};
            const simpleFeedbackIcons = ['success', 'error', 'info', 'warning', 'question'];
            const isSimpleFeedback =
                !swalConfig.toast &&
                simpleFeedbackIcons.includes(swalConfig.icon) &&
                !swalConfig.input &&
                !swalConfig.showCancelButton &&
                !swalConfig.showDenyButton &&
                !swalConfig.footer;

            if (isSimpleFeedback) {
                Object.assign(swalConfig, {
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: swalConfig.timer || 2200,
                    timerProgressBar: true,
                    backdrop: false,
                    customClass: Object.assign({}, swalConfig.customClass || {}, {
                        popup: [swalConfig.customClass?.popup, 'codex-toast-popup'].filter(Boolean).join(' '),
                        title: [swalConfig.customClass?.title, 'codex-toast-title'].filter(Boolean).join(' '),
                        htmlContainer: [swalConfig.customClass?.htmlContainer, 'codex-toast-content'].filter(Boolean).join(' ')
                    })
                });
            }

            Swal.fire(swalConfig);
        })();
    </script>
@endif
