<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $project->title ?? 'Proyecto de pruebas' ?></title>

    <!-- bootstrap 5.2.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"  integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="icon" type="image/svg+xml" href="<?= \App\Helpers\Assets::load('images/favicon.svg') ?>"/>
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/project.css') ?>">

    <!-- icons -->
    <link rel="stylesheet" href="<?= \App\Helpers\Assets::load('css/extra/icons/bootstrap-icons.css') ?>">

    <!-- Alertas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.10.3/dist/cdn.min.js"></script>
    <script src="<?= \App\Helpers\Assets::load('js/project.js') ?>" type="module"></script>
</head>
<body>
    <textarea style="display: none;" id="project-data"><?= json_encode($project) ?></textarea>
    
    <!-- Loader primera carga -->
    <?php require 'partials/loader-start.php'; ?>
    <!-- Loader pequeño, principalmente para cargas menores -->
    <?php require 'partials/loader.php'; ?>

    <main class="vw-100 vh-100 d-flex p-0 bg-buttons overflow-auto">
        <?php require "partials/project/buttons.php" ?>

        <?php require "partials/project/main.php" ?>
    </main>

    <?php require "partials/project/child/modal.php" ?>
    <?php require "partials/add-observations.php"; ?>
    <?php require "partials/project/upload-attachment.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <script>
      async function expand( l ) {
        let timeout = 0;
        const i = document.getElementById(`stlist-${l}`);
        const x = document.getElementById(`expand-${l}`);

        if ( i.style.height == ""  ) { i.style.height = i.scrollHeight+'px'; timeout = 50 } 
                
        setTimeout(() => {
          if (i.style.height == "0px") {
            i.classList.remove('d-none')
            i.style.height = i.scrollHeight+'px';
            x.style.transform = "rotate(180deg)"
          } else {
            i.style.height = "0px"
            x.style.transform = "rotate(0deg)"
            setTimeout( () => i.classList.add('d-none'), 201)
          }
          timeout = 0
        }, timeout);
      }

      <?= $script ?? '' ?>
    </script>
</body>
</html>