<?php
//session_start();
if (!empty($_SESSION['username_alomani'])) {
    header('location:index.php');
}
?>
<!doctype html>
<html lang="en">

<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Alomani</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            min-height: 100vh;
            background: url('assets/images/login.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 10px;
            padding: 40px 30px 30px 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .register-title {
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 30px;
            font-family: 'Montserrat', Arial, sans-serif;
        }

        .form-control {
            background: #e0e0e0;
            border: none;
            border-radius: 0;
            margin-bottom: 15px;
            font-size: 1rem;
            padding: 12px 15px;
        }

        .btn-register-main {
            background: #814603;
            color: #fff;
            border: none;
            border-radius: 0;
            width: 100%;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 12px 0;
            letter-spacing: 1px;
        }

        .btn-register-main:hover {
            background: #663702;
        }

        .register-links {
            margin: 10px 0 18px 0;
            font-size: 1rem;
        }

        .register-links a {
            color: #1a73e8;
            text-decoration: none;
        }

        .register-links a:hover {
            text-decoration: underline;
        }

        .or-divider {
            margin: 18px 0 10px 0;
            font-weight: bold;
            color: #444;
            letter-spacing: 2px;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-bottom: 5px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            transition: box-shadow 0.2s;
            cursor: pointer;
        }

        .social-btn.google {
            color: #ea4335;
            border: 1.5px solid #ea4335;
        }

        .social-btn.facebook {
            color: #1877f3;
            border: 1.5px solid #1877f3;
        }

        .social-btn.x {
            color: #222;
            border: 1.5px solid #222;
        }

        .social-btn:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
        }

        @media (max-width: 500px) {
            .register-box {
                padding: 25px 8px 20px 8px;
            }

            .register-title {
                font-size: 1.3rem;
            }
        }
    </style>



    <main class="register-container">
        <div class="register-box">
            <form class="needs-validation" novalidate action="proses/proses_login.php" method="post">
                <div class="text-center mb-4">
                    <img src="assets/images/logo.png" alt="Batik Alomani Logo" style="max-width: 150px;">
                </div>
                <div class="register-title">Please Login</div>
                <div class="form-floating">
                    <input name="username" type="text" class="form-control" id="floatingInput"
                        placeholder="name@example.com" required>
                    <label for="floatingInput">Email atau No Telepon</label>
                    <div class="valid-feedback">
                        Masukan email atau No Hp
                    </div>
                </div>
                <div class="form-floating">
                    <input name="password" type="password" class="form-control" id="floatingPassword"
                        placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                    <div class="invalid-feedback">
                        Masukan password.
                    </div>
                </div>
                <div class="checkbox mb-3 text-center">
                    <label>
                        <input class="form-check-input" type="checkbox" value="remember-me">Remember me
                    </label>
                </div>
                <button type="submit" class="btn btn-register-main" name="submit_validate"
                    value="abc">Login</button>
                <div class="register-links text-center">
                    Belum Punya Akun ? <a href="register.php">Daftar</a> 
                </div>
                <p class="mt-5 mb-3 text-muted text-center">&copy; Batik Alomani -<?php echo date("Y") ?></p>
            </form>
        </div>
    </main>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    </body>

</html>