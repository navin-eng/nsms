<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Error 404 | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
        <meta content="Coderthemes" name="author">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
        <link href="{{ asset('backend/assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">

    </head>

    <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <div class="row" style="display: flex; justify-content:center; align-items:center;">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        </div>
                        <h4 class="page-title" style="text-align: center;">404 Error You Are not allowed to see this Page</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="text-center">
                        <img src="{{ asset('backend/assets/images/file-searching.svg') }}" height="90" alt="File not found Image">

                        <h1 class="text-error mt-4">404</h1>
                        <h4 class="text-uppercase text-danger mt-3">Page Not Found</h4>
                        <p class="text-muted mt-3">It's looking like you may have taken a wrong turn. Don't worry... it
                            happens to the best of us. Here's a
                            little tip that might help you get back on track.</p>

                        <a class="btn btn-info mt-3" href="/admin/dashboard"><i class="mdi mdi-reply"></i> Return Home</a>
                    </div> <!-- end /.text-center-->
                </div> <!-- end col-->
            </div>
            <!-- end row -->
        </div>


        <!-- bundle -->
        <script src="{{ asset('backend/assets/js/vendor.min.js') }}"></script>
        <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>

    </body>
</html>
