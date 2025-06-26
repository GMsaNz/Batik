<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Batik Alomani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <form class="needs-validation" novalidate action="proses/proses_register.php" method="POST">
                <div class="register-title">Register</div>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                <input type="email" class="form-control" id="username" name="username" placeholder="Email">
                <input type="number" class="form-control" id="nohp" name="nohp" placeholder="No Telepon">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <input type="password" class="form-control" id="password2" placeholder="Validasi Password">
                <div class="form-floating">
                    <textarea class="form-control" id="" style="height: 100px" name="alamat"></textarea>
                    <label for="floatinginput">Alamat</label>
                </div>
                <button type="submit" class="btn btn-register-main" name="input_user_validate"
                    value="1234">Register</button>
            </form>
            <div class="register-links">
                Sudah Punya Akun ? <a href="login.php">Login</a>
            </div>
            <div class="or-divider">OR</div>
            <div class="social-login">
                <button class="social-btn google" onclick="registerWithGoogle()"><i class="bi bi-google"></i></button>
                <button class="social-btn facebook" onclick="registerWithFacebook()"><i
                        class="bi bi-facebook"></i></button>
                <button class="social-btn x" onclick="registerWithX()"><i class="bi bi-x"></i></button>
            </div>
        </div>
    </div>
    <script>
        document.querySelector("form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            const password2 = document.getElementById("password2").value;

            if (password !== password2) {
                e.preventDefault(); // hentikan form submit
                alert("Password Harus Sama!");
            }
        });
    </script>

</body>

</html>