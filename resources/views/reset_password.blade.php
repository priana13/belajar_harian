<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ubah Password</title>

    <!-- Panggil file CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Tambahkan CSS tambahan -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }

        .form-card h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
    </style>

</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @elseif ($isExpired == true)
                        <div class="alert alert-warning">
                            Token ubah password tidak ditemukan atau kadaluarsa.
                            Silahkan buat permintaan lupa password yang baru di aplikasi.
                        </div>
                    @else
                        <h1>Ubah Password</h1>
                        {{-- {{ dd($isExpired) }} --}}
                        <form method="POST" action="/reset_password/handleForm">
                            @csrf
                            <input class="form-control" id="token" name="email" value="{{ $email }}"
                                hidden>
                            <input class="form-control" id="email" name="token" value="{{ $token }}"
                                hidden>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <p id="password-error" style="display: none; color: red;">
                                    Password must be at least 8 characters long</p>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                                <div class="invalid-feedback">
                                    Passwords tidak sama
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" id="submit-btn" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Panggil file JavaScript Bootstrap 5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"
        integrity="sha512-jcEjphVxyw1kM2ZsFCd1IgFlAPJ7lP4Lf3lvjG0e/FoSlP/Kby34JzgZfI4mcSwblvj2QkMg4uc4i3jrNnFmEw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>

<script>
    $(document).ready(function() {

        $('#password').on('input', function() {
            var password = $(this).val();

            if (password.length >= 8) {
                $('#password-error').hide();
            } else {
                $('#submit-btn').prop('disabled', true);
                $('#password-error').show();
            }
        });

        var passwordInput = $('#password');
        var confirmPasswordInput = $('#confirm_password');
        var submitButton = $('form button[type="submit"]');

        submitButton.prop('disabled', true);

        // Add an event listener for the input event on the confirm password field
        confirmPasswordInput.on('input', function() {
            // Get the password and confirm password values
            var passwordValue = passwordInput.val();
            var confirmPasswordValue = confirmPasswordInput.val();
            // If the password and confirm password values match, remove any existing error messages

            if (passwordValue === confirmPasswordValue) {
                confirmPasswordInput.removeClass('is-invalid');
                confirmPasswordInput.addClass('is-valid');
                submitButton.prop('disabled', false);
            } else {
                // If the values don't match, add an error message to the confirm password field
                confirmPasswordInput.removeClass('is-valid');
                confirmPasswordInput.addClass('is-invalid');
                submitButton.prop('disabled', true);
            }
        });
    });
</script>
