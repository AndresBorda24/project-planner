<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asotrauma | Error</title>
    
    <!-- Bootstrap -->
    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?>"/>
    <style>
        .d-flex { display: flex; }
        .bg-dark { background-color: #292929; }
        .justify-content-center { justify-content: center; }
        .align-items-center { align-items: center; }
        .text-secondary { color: darkgrey }
        .vh-100 { min-height: 100vh; }
        .p-4 { padding: 1rem; }
        .text-center { text-align: center; }
        .h-100 { height: 100%; }
        .w-100 { width: 100%; }
        .d-block { display: block; }
        .overflow-auto { overflow: auto; }
        pre {
            display: block;
            margin-top: 0;
            margin-bottom: 1rem;
            overflow: auto;
            font-size: .875em;
            white-space: pre;
        }
    </style>
</head>
<body class="bg-dark">
    <div class="d-flex justify-content-center align-items-center text-secondary vh-100">
        <div class="p-4" style="max-width: 50wv; min-width: 350px;">
            <h3 class="text-center">Ha ocurrido un error...</h3>
            <div style="height: 100px;">
                <img
                class="h-100 w-100"
                src="<?= \App\Helpers\Assets::load('images/perro_error.gif') ?>"
                style="object-fit: contain; object-position: center">
            </div>
            <pre id="error" class="d-block overflow-auto" style="max-height: 380px; font-size: .8em; line-height: 1.8; color: #ff8585"></pre>
        </div>
    </div>
    
    <script>
        window.addEventListener('load', () => {
            const code = document.getElementById("error");
            code.innerText = JSON.stringify(
                <?= json_encode( App\App::config('error')['show'] ? $error : App\App::config('error')['default_message']) ?>, 
                null, 
                3 
            );

            if (! <?= intval( App\App::config('error')['show']) ?> ) {
                document.querySelector('pre').style.whiteSpace = "break-spaces";
            }
        });
    </script>
</body>
</html>