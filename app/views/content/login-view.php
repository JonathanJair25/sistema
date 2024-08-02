<div class="main-container">
    <style>
        .main-container {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000428;
            background: url('<?php echo APP_URL . "app/views/fondo/fondo.jpg"; ?>') no-repeat center center;
            background-size: cover;
        }

        .box {
            background: rgba(255, 255, 255, 0.8); /* Fondo blanco semitransparente */
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px; /* Ajusta el tamaño según tus necesidades */
            position: relative; /* Necesario para posicionar el ícono dentro de los campos */
        }

        .box .title {
            color: #333;
            margin-bottom: 1rem;
        }

        .box .field {
            margin-bottom: 1rem;
            position: relative; /* Necesario para posicionar el ícono dentro del campo */
        }

        .box .label {
            color: #555;
            font-weight: bold;
        }

        .box .input {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 0.5rem;
            font-size: 1rem;
            width: calc(100% - 40px); /* Ajusta el ancho para el ícono */
        }

        .box .button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.75rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .box .button:hover {
            background-color: #0056b3;
        }

        .box .has-text-centered {
            text-align: center;
        }

        .box .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.25rem;
            color: #007bff;
        }
    </style>
    <form class="box login" action="" method="POST" autocomplete="off">
        <br>
        <br>
        <br>
        <h5 class="title is-4 has-text-centered">Inicia sesión con tu cuenta</h5>
<br>
<br>
        <?php
            if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                $insLogin->iniciarSesionControlador();
            }
        ?>

        <div class="field">
            <label class="label"><i class="fas fa-user-circle"></i> &nbsp; Usuario</label>
            <div class="control">
                <input class="input" type="text" name="login_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
            </div>
        </div>

        <div class="field">
            <label class="label"><i class="fas fa-lock"></i> &nbsp; Clave</label>
            <div class="control">
                <input id="password" class="input" type="password" name="login_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                <i id="togglePassword" class="fas fa-eye eye-icon"></i>
            </div>
        </div>

        <p class="has-text-centered mb-4 mt-3">
            <button type="submit" class="button">INICIAR SESIÓN</button>
        </p>
		<br>
		<br>
    </form>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute using getAttribute and setAttribute methods
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the eye icon between eye and eye-slash
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</div>
