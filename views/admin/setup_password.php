<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Top Header Section -->
                <div class="bg-primary-law text-white text-center py-5 px-4">
                    <div class="mb-3 bg-white d-inline-block px-3 py-2 rounded">
                        <span class="text-primary-law fw-bold fs-4">Lawyerson</span><span class="text-gold fw-bold fs-4">&</span>
                    </div>
                    <h3 class="fw-bold mb-1">Initial Setup</h3>
                    <p class="mb-0 small opacity-75">Set your admin password to get started</p>
                </div>
                
                <!-- Bottom Form Section -->
                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-3" style="width:56px;height:56px;">
                            <i class="fas fa-shield-alt text-warning fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Create Admin Password</h4>
                        <p class="text-muted small">This is your first time here. Please create a secure admin password to protect your dashboard.</p>
                    </div>
                    
                    <?php if(isset($_SESSION['setup_error'])): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($_SESSION['setup_error']); unset($_SESSION['setup_error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="index.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="admin_setup_password">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="new_password" class="form-control border-start-0 ps-0" placeholder="Minimum 8 characters" required minlength="8">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-medium">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="confirm_password" class="form-control border-start-0 ps-0" placeholder="Re-enter your password" required minlength="8">
                            </div>
                        </div>

                        <div class="alert alert-info py-2 small mb-4">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Admin login email:</strong> admin@lawyerson.com<br>
                            Use this email along with the password you set here to sign in.
                        </div>
                        
                        <button type="submit" class="btn btn-primary-law w-100 py-2 fw-medium rounded-2 d-flex justify-content-center align-items-center">
                            <i class="fas fa-shield-alt me-2"></i> Set Password & Continue
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
