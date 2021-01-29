<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR EUNICODIN</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.css">
</head>
<body>
    <main id="main-login">
        <article>
            <form id="formulario_ingreso">
                <!-- <div id="img-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/Code.svg/1200px-Code.svg.png" alt="">
                </div> -->
                <h1 style="text-align:center;font-size:2.3rem;margin-bottom:30px">EUNICODIN</h1>
                <div class="notify-box" id="notification_fail"></div>
                <label for="user">Nombre de usuario</label>
                <div class="input-icon">
                    <i id="user-icon" class="fas fa-user"></i>
                    <input class="inputs" id="username" type="text" name="username" placeholder="Username">
                </div>

                <label for="pass">Contraseña</label>
                <div class="input-icon">
                    <i id="unlock-icon" class="fas fa-unlock"></i>
                    <input class="inputs" id="password" type="password" name="password" placeholder="********">
                </div>

                <button id="btn-login">Ingresar</button>
            </form>
            <div id="redirecting">
                <p>Bienvenido, <?= $_SESSION['user']; ?></p>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </article>
    </main>
    <footer id="footer">
        <p>&copy; Laura Arciniega | Diciembre 2020</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/all.js"></script> <!--Esto es para los iconos -->
</body>
</html>