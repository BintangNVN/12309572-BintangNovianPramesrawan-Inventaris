<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventaris - SMK Wikrama</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        body {
            background-color: #f5f6f8;
            font-family: 'Poppins', sans-serif;
        }

        .hero {
            padding: 120px 0 60px;
        }

        .hero h1 {
            font-weight: 700;
        }

        .hero p {
            color: #6c757d;
        }

        .feature-card {
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .feature-box {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-dark-blue { background: #0d1b4c; }
        .bg-orange { background: #f4a300; }
        .bg-purple { background: #a9a6d8; }
        .bg-green { background: #66c2a5; }

        footer {
            font-size: 14px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
        <img src="{{ asset('images/wikrama-logo.png') }}" style="width:60px;" class="mb-3">
        <div class="ms-auto">
            <button onclick="openLoginModal()" class="btn btn-primary">Login</button>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-5">
            Inventaris Management of <br>
            <b>SMK Wikrama</b>
        </h1>

        <p class="mt-3">
            Management of incoming and outgoing items at SMK Wikrama Bogor.
        </p>

        <div class="mt-5">
            <img src="{{ asset('images/inventory.png') }}" class="img-fluid" style="max-height:300px;">
        </div>
    </div>
</section>

<!-- SYSTEM FLOW -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold">Our system flow</h2>
        <p class="text-muted mb-5">Our inventory system workflow</p>

        <div class="row g-4">

            <div class="col-md-3">
                <div class="feature-card shadow-sm">
                    <div class="feature-box bg-dark-blue">
                        <img src="{{ asset('images/items-dta-removebg-preview.png') }}" style="width:80px;">
                    </div>
                    <div class="p-3">
                        <p class="mb-0">Items Data</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card shadow-sm">
                    <div class="feature-box bg-orange">
                        <img src="{{ asset('images/management-technician-removebg-preview.png') }}" style="width:80px;">
                    </div>
                    <div class="p-3">
                        <p class="mb-0">Management Technician</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card shadow-sm">
                    <div class="feature-box bg-purple">
                        <img src="{{ asset('images/managed-lending-removebg-preview.png') }}" style="width:80px;">
                    </div>
                    <div class="p-3">
                        <p class="mb-0">Managed Lending</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card shadow-sm">
                    <div class="feature-box bg-green">
                        <img src="{{ asset('images/borrow-removebg-preview.png') }}" style="width:80px;">
                    </div>
                    <div class="p-3">
                        <p class="mb-0">All Can Borrow</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="mt-5 py-5">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <img src="{{ asset('images/wikrama-logo.png') }}" style="width:60px;" class="mb-3">
                <p class="mb-1">smkwikrama@sch.id</p>
                <p>001-7876-2876</p>
            </div>

            <div class="col-md-4 text-md-end">
                <h6 class="fw-bold">Our Guidelines</h6>
                <ul class="list-unstyled text-muted">
                    <li>Terms</li>
                    <li class="text-danger">Privacy policy</li>
                    <li>Cookie Policy</li>
                    <li>Discover</li>
                </ul>
            </div>

            <div class="col-md-4 text-md-end">
                <h6 class="fw-bold">Our address</h6>
                <p class="text-muted">
                    Jalan Wangun Tengah <br>
                    Sindangsari <br>
                    Jawa Barat
                </p>
            </div>

        </div>
    </div>
</footer>

<!-- LOGIN MODAL -->
<div id="loginModal" class="position-fixed top-0 start-0 w-100 h-100 d-none bg-dark bg-opacity-50" style="z-index:1050;">
    <div class="d-flex align-items-center justify-content-center h-100 px-3">
        <div class="card shadow-lg" style="max-width: 420px; width:100%;">
            <div class="card-body p-4">
                <h2 class="h4 text-center mb-4">Login</h2>

                <div id="loginError" class="alert alert-danger d-none"></div>

                <form id="loginForm" onsubmit="handleLogin(event)">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" id="email" class="form-control">
                        <div id="emailError" class="text-danger small d-none"></div>
                    </div>

                    <div class="mb-4">
                        <label>Password</label>
                        <input type="password" id="password" class="form-control">
                        <div id="passwordError" class="text-danger small d-none"></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="closeLoginModal()">Close</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('d-none');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('d-none');
}

function setFieldError(id, message) {
    const el = document.getElementById(id + 'Error');
    el.textContent = message;
    el.classList.remove('d-none');
}

function setLoginError(message) {
    const el = document.getElementById('loginError');
    el.innerHTML = message;
    el.classList.remove('d-none');
}

function handleLogin(e) {
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        setLoginError('Email dan Password wajib diisi');
        return;
    }

    fetch('/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email, password })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/dashboard';
        } else {
            setLoginError(data.message || 'Login gagal');
        }
    })
    .catch(() => {
        setLoginError('Terjadi kesalahan');
    });
}
</script>

</body>
</html>
