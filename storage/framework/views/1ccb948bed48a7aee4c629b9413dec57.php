<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In &mdash; សាលារៀនប្រទីបបញ្ញា (Bratib Panha School)</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter for Latin + Kantumruy Pro & Moul for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Moul&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-sans: 'Inter', 'Kantumruy Pro', system-ui, -apple-system, sans-serif;
            --font-moul: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif;
            --primary-navy: #0f172a;
            --secondary-navy: #1e293b;
            --accent-gold: #f59e0b;
            --accent-gold-hover: #d97706;
            --bg-canvas: #0f172a;
        }

        html, body {
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            overflow-y: auto;
            margin: 0;
            padding: 0;
            user-select: none;
        }

        body {
            font-family: var(--font-sans);
            font-size: 1rem;
            line-height: 1.5;
            background: #f4d334 url('<?php echo e(asset('images/login-bg.png')); ?>?v=<?php echo e(file_exists(public_path('images/login-bg.png')) ? filemtime(public_path('images/login-bg.png')) : 2); ?>') center center / cover no-repeat fixed;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            position: relative;
        }

        /* Ambient overlay scrim */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.04);
            pointer-events: none;
            z-index: 0;
        }

        input, button, .form-control, .form-label, .btn {
            font-family: var(--font-sans);
        }

        /* Compact & Narrow Form Size */
        .login-container {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        /* Transparent Glassmorphism Card (រាងថ្លា ច្បាស់ មិនព្រិល) */
        .login-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 1.15rem;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.55), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.22);
            overflow: hidden;
        }

        /* Glass Header */
        .brand-header {
            background: rgba(0, 0, 0, 0.22);
            padding: 0.75rem 1rem 0.5rem;
            text-align: center;
            color: #ffffff;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-icon-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            display: inline-block;
            margin-bottom: 0.25rem;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.5));
            transition: transform 0.25s ease;
        }

        .brand-icon-img:hover {
            transform: scale(1.04);
        }

        .brand-title {
            font-family: var(--font-moul), serif;
            font-size: 1.1rem;
            font-weight: 400;
            letter-spacing: 0.02em;
            line-height: 1.3;
            margin-bottom: 0.15rem;
            color: #ffffff;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.75);
        }

        .brand-subtitle {
            font-size: 0.82rem;
            color: #f59e0b;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin-bottom: 0.15rem;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.8);
        }

        .brand-motto {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 0.72rem;
            color: #e2e8f0;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
        }

        /* Form Body */
        .form-body {
            padding: 0.85rem 1.15rem;
        }

        /* Form Labels & Controls */
        .form-label-custom {
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
            margin-bottom: 0.2rem;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #334155;
            padding: 0.42rem 0.65rem;
            font-size: 0.92rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #0f172a;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.42rem 0.65rem;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.4);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: #0f172a;
            font-size: 0.98rem;
            font-weight: 700;
            padding: 0.55rem 1.1rem;
            border-radius: 0.55rem;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
            transition: all 0.2s ease-in-out;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #fbbf24 0%, #b45309 100%);
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
        }

        .form-check-label {
            color: #f1f5f9;
            font-size: 0.86rem;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
        }

        /* Demo Box */
        .demo-wrapper {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.55rem;
            padding: 0.45rem;
            margin-top: 0.6rem;
        }

        .demo-chip {
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            font-size: 0.76rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 0.25rem 0.5rem;
        }

        .demo-chip:hover {
            background-color: #ffffff !important;
            border-color: var(--accent-gold) !important;
            transform: translateY(-1px);
        }

        .copyright-footer {
            font-size: 0.78rem;
            text-shadow: 0 1px 4px rgba(0,0,0,0.85);
            margin-top: 0.4rem;
        }

        /* Responsive Auto-Scaling for short laptop & tablet screens */
        @media (max-height: 750px) {
            body {
                padding: 0.35rem;
            }
            .brand-header {
                padding: 0.5rem 0.75rem 0.35rem;
            }
            .brand-icon-img {
                width: 56px;
                height: 56px;
                margin-bottom: 0.15rem;
            }
            .brand-title {
                font-size: 0.98rem;
                margin-bottom: 0.1rem;
            }
            .brand-subtitle {
                font-size: 0.74rem;
                margin-bottom: 0.1rem;
            }
            .brand-motto {
                font-size: 0.66rem;
            }
            .form-body {
                padding: 0.6rem 0.9rem;
            }
            .form-label-custom {
                font-size: 0.82rem;
                margin-bottom: 0.1rem;
            }
            .form-control, .input-group-text {
                padding: 0.35rem 0.55rem;
                font-size: 0.86rem;
            }
            .btn-primary-custom {
                padding: 0.48rem 1rem;
                font-size: 0.92rem;
            }
            .demo-wrapper {
                margin-top: 0.45rem;
                padding: 0.35rem;
            }
            .demo-chip {
                font-size: 0.72rem;
                padding: 0.2rem 0.4rem;
            }
            .copyright-footer {
                margin-top: 0.25rem;
                font-size: 0.72rem;
            }
        }

        @media (max-height: 620px) {
            .brand-header {
                padding: 0.4rem 0.5rem 0.25rem;
            }
            .brand-icon-img {
                width: 46px;
                height: 46px;
            }
            .brand-title {
                font-size: 0.9rem;
            }
            .brand-motto {
                display: none;
            }
            .form-body {
                padding: 0.5rem 0.75rem;
            }
            .demo-wrapper {
                margin-top: 0.35rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <!-- Brand Header -->
        <div class="brand-header">
            <?php
                $loginSchool = \App\Models\Core\School::first();
                $loginLogo = ($loginSchool && $loginSchool->logo_path && file_exists(public_path($loginSchool->logo_path)))
                    ? asset($loginSchool->logo_path)
                    : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));
            ?>
            <img src="<?php echo e($loginLogo); ?>" alt="School Logo" class="brand-icon-img">
            <h3 class="brand-title">សាលារៀន ប្រទីបបញ្ញា</h3>
            <p class="brand-subtitle">BRATIB PANHA SCHOOL</p>
            <div class="brand-motto">« វិន័យ &bull; សីលធម៌ &bull; ចំណេះដឹង &bull; គុណភាព »</div>
        </div>

        <!-- Form Body -->
        <div class="form-body">
            <?php if(session('status')): ?>
                <div class="alert alert-info alert-dismissible fade show py-2 px-3 small mb-2" role="alert">
                    <i class="bi bi-info-circle me-1"></i><?php echo e(session('status')); ?>

                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small mb-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <?php echo e($errors->first()); ?>

                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <!-- Email Input -->
                <div class="mb-2">
                    <label for="email" class="form-label form-label-custom">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="<?php echo e(old('email', 'sophal.chan@school.edu')); ?>" 
                               required 
                               autofocus 
                               class="form-control border-start-0 ps-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="name@school.edu">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label form-label-custom mb-0">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               value="password123" 
                               required 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Enter your password">
                        <button class="btn btn-outline-light border bg-white text-muted py-1 px-2" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label" for="remember">
                            Remember my session
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-custom w-100 mb-1">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Portal
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="demo-wrapper">
                <div class="text-white-50 small fw-bold text-center text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em; text-shadow: 0 1px 2px rgba(0,0,0,0.6);">
                    Quick Demo Credentials
                </div>
                <div class="d-flex gap-2">
                    <div class="demo-chip py-1 px-2 rounded d-flex align-items-center justify-content-center flex-fill" 
                         onclick="fillCredentials('sophal.chan@school.edu', 'password123')">
                        <span class="badge bg-primary text-white me-1" style="font-size: 0.7rem;">Admin</span>
                        <span class="fw-bold text-dark" style="font-size: 0.78rem;">sophal.chan</span>
                    </div>
                    <div class="demo-chip py-1 px-2 rounded d-flex align-items-center justify-content-center flex-fill" 
                         onclick="fillCredentials('arthur.sterling@school.edu', 'password123')">
                        <span class="badge bg-success text-white me-1" style="font-size: 0.7rem;">Teacher</span>
                        <span class="fw-bold text-dark" style="font-size: 0.78rem;">arthur.sterling</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-white fw-semibold copyright-footer">
        &copy; <?php echo e(date('Y')); ?> សាលារៀនប្រទីបបញ្ញា (Bratib Panha School). All rights reserved.
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
</script>

</body>
</html>
<?php /**PATH D:\School\laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>