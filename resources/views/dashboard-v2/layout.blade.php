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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
         var notyf = new Notyf({
           duration: 2000,
           position: {
             x: 'rigth',
             y: 'top'
           }
         });
         
         function showToast(status, mensagem, refresh = true) {
            var notyf = new Notyf({
               duration: 2000,
               position: {
                  x: 'rigth',
                  y: 'top'
               }
            });

           if (status == 'error' || status == 'warning') {
             notyf.error(mensagem);
           } else {
             notyf.success(mensagem);
           }
         
           if (refresh) {
             setTimeout(() => {
               console.log("Delayed for 1 second.");
               window.location.href = window.location.href;
             }, "1000");
           }
         
         }
    </script>
</body>
</html>