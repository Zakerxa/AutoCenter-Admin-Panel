<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            .pageLoading{
                display: flex;
                align-items: center;
                padding: 0;
                margin: 0;
                height:100vh;
                width:100vw;
            }
        </style>
    </head>
    <body class="antialiased">
         <!-- Show Loading if Data not ready yet -->
        <div id="pageLoading" class="pageLoading">
            <div class="text-center p-0">
                <h3 class="fw-bold" id="loadingName"></h3>
            </div>
        </div>
        <div id="app"></div>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="./adminlte/dist/js/adminlte.min.js"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
    <script>
       document.getElementById('loadingName').innerText = '<ThidaAutoCenter/>';
       document.addEventListener('DOMContentLoaded',()=>{
         document.getElementById('pageLoading').style.display = 'none';
       });
    </script>
</html>
