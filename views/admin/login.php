<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Top Header Section -->
                <div class="bg-primary-law text-white text-center py-5 px-4">
                    <div class="mb-3 bg-white d-inline-block px-3 py-2 rounded">
                        <span class="text-primary-law fw-bold fs-4">Lawyerson</span><span class="text-gold fw-bold fs-4">&</span>
                    </div>
                    <h3 class="fw-bold mb-1">Admin Dashboard</h3>
                    <p class="mb-0 small opacity-75">Access your administrative controls</p>
                </div>
                
                <!-- Bottom Form Section -->
                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-1">Sign In</h4>
                        <p class="text-muted small">Enter your credentials to access your account</p>
                    </div>
                    
                    <?php if(isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="index.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="admin_login">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="your.email@example.com" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label text-muted small fw-medium">Password</label>
                                <a href="#" class="text-decoration-none small text-primary-law">Forgot password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0 ps-0" placeholder="........" required>
                                <span class="input-group-text bg-transparent border-start-0 text-muted cursor-pointer" id="togglePassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                            <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-law w-100 py-2 fw-medium rounded-2 d-flex justify-content-center align-items-center">
                            Sign in <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="small text-muted">&copy; <?= date('Y') ?> Lawyerson & Partners. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('passwordInput');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>
