<div class="text-center mb-4">
    <h2>Applicant Login</h2>
    <p class="text-muted">Login to continue your application</p>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <form method="POST" action="/applicant/login" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group mb-3">
                <label for="login" class="form-label">Email, Phone, or JAMB Number</label>
                <input type="text" class="form-control" id="login" name="login" required>
                <div class="invalid-feedback">Please enter your login details.</div>
            </div>
            
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-feedback">Please enter your password.</div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
            <p class="mb-2">Don't have an account?</p>
            <a href="/apply/step/1" class="btn btn-outline-primary">
                <i class="fas fa-user-plus"></i> Start New Application
            </a>
        </div>
        
        <div class="text-center mt-3">
            <a href="/applicant/forgot-password" class="text-muted">
                <i class="fas fa-key"></i> Forgot Password?
            </a>
        </div>
    </div>
</div>