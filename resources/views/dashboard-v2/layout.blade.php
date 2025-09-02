<!DOCTYPE html>
<html data-bs-theme="light" lang="pt-BR" dir="ltr">
@include('dashboard-v2.components.header')

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
     <style>
        @media (min-width: 1200px) and (max-width: 1939px) {
            .container-xl, .container-lg, .container-md, .container-sm, .container {
                max-width: 100%!important;
            }
        }

        @media (min-width: 1940px) {
            .container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container {
                max-width: 1880px!important;
            }
        }
     </style>
    <main class="main" id="top">
        <div class="container" data-layout="container">
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>
            @include('dashboard-v2.components.sidebar')
            <div class="content">
                @include('dashboard-v2.components.navbar')
                @yield('content')
                @include('dashboard-v2.components.footer')
            </div>
            @include('dashboard-v2.components.scripts')
        </div>
    </main>
</body>
</html>