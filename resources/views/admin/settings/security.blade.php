@extends('layouts.admin')

@section('title', 'Security Settings')
@section('page_title', 'Security Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.settings') }}">Settings</a></li>
<li class="breadcrumb-item active" aria-current="page">Security</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Security Settings</h2>
        <p class="text-muted">Configure system security and access control parameters.</p>
    </div>
    <div>
        <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary">
            <i class="fas fa-history me-2"></i> View Security Logs
        </a>
    </div>
</div>

<!-- Security Overview Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="icon-circle icon-success mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="mb-1">Security Status</h5>
                    <div class="badge bg-success">Optimal</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="icon-circle icon-info mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-key"></i>
                    </div>
                    <h5 class="mb-1">Password Policy</h5>
                    <div class="badge bg-info">Strong</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="icon-circle icon-warning mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h5 class="mb-1">2FA Status</h5>
                    <div class="badge bg-warning text-dark">Partial</div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="icon-circle icon-primary mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h5 class="mb-1">SSL Certificate</h5>
                    <div class="badge bg-success">Valid</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Security Settings Form -->
<form action="#" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Password Policy Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Password Policy</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="min_password_length" class="form-label">Minimum Password Length</label>
                        <input type="number" class="form-control" id="min_password_length" name="min_password_length" value="8" min="6" max="32">
                        <div class="form-text">Minimum number of characters required in passwords.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_expiry_days" class="form-label">Password Expiry (days)</label>
                        <input type="number" class="form-control" id="password_expiry_days" name="password_expiry_days" value="90" min="0">
                        <div class="form-text">Days before password expires (0 = never expires).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_history_count" class="form-label">Password History</label>
                        <input type="number" class="form-control" id="password_history_count" name="password_history_count" value="5" min="0" max="20">
                        <div class="form-text">Number of previous passwords that cannot be reused.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_uppercase" name="require_uppercase" checked>
                            <label class="form-check-label" for="require_uppercase">Require Uppercase Letters</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_lowercase" name="require_lowercase" checked>
                            <label class="form-check-label" for="require_lowercase">Require Lowercase Letters</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_numbers" name="require_numbers" checked>
                            <label class="form-check-label" for="require_numbers">Require Numbers</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_special_chars" name="require_special_chars" checked>
                            <label class="form-check-label" for="require_special_chars">Require Special Characters</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="force_password_change" name="force_password_change" checked>
                            <label class="form-check-label" for="force_password_change">Force Password Change on First Login</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Login Security Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Login Security</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="login_attempts" class="form-label">Maximum Login Attempts</label>
                        <input type="number" class="form-control" id="login_attempts" name="login_attempts" value="5" min="1" max="10">
                        <div class="form-text">Number of failed attempts before account lockout.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lockout_time" class="form-label">Account Lockout Duration (minutes)</label>
                        <input type="number" class="form-control" id="lockout_time" name="lockout_time" value="30" min="5">
                        <div class="form-text">Time before a locked account can attempt login again.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                        <input type="number" class="form-control" id="session_timeout" name="session_timeout" value="60" min="5">
                        <div class="form-text">Inactive time before a user is automatically logged out.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_2fa_admins" name="enable_2fa_admins" checked>
                            <label class="form-check-label" for="enable_2fa_admins">Require 2FA for Administrators</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_2fa_officers" name="enable_2fa_officers" checked>
                            <label class="form-check-label" for="enable_2fa_officers">Require 2FA for Bank Officers</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_2fa_customers" name="enable_2fa_customers">
                            <label class="form-check-label" for="enable_2fa_customers">Require 2FA for Customers</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="log_login_attempts" name="log_login_attempts" checked>
                            <label class="form-check-label" for="log_login_attempts">Log All Login Attempts</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="restrict_multiple_sessions" name="restrict_multiple_sessions">
                            <label class="form-check-label" for="restrict_multiple_sessions">Restrict Multiple Simultaneous Sessions</label>
                        </div>
                        <div class="form-text">Prevent users from being logged in from multiple devices simultaneously.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Two-Factor Authentication Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Two-Factor Authentication</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="2fa_method" class="form-label">Default 2FA Method</label>
                        <select class="form-select" id="2fa_method" name="2fa_method">
                            <option value="app" selected>Authenticator App</option>
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                        </select>
                        <div class="form-text">Primary method for two-factor authentication.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="2fa_code_expiry" class="form-label">2FA Code Expiry (minutes)</label>
                        <input type="number" class="form-control" id="2fa_code_expiry" name="2fa_code_expiry" value="10" min="1" max="60">
                        <div class="form-text">Time before a 2FA code expires.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="allow_2fa_remember" name="allow_2fa_remember" checked>
                            <label class="form-check-label" for="allow_2fa_remember">Allow "Remember This Device" Option</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="2fa_remember_days" class="form-label">Remember Device Duration (days)</label>
                        <input type="number" class="form-control" id="2fa_remember_days" name="2fa_remember_days" value="30" min="1" max="365">
                        <div class="form-text">Days before requiring 2FA again on a remembered device.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_2fa_ip_change" name="require_2fa_ip_change" checked>
                            <label class="form-check-label" for="require_2fa_ip_change">Require 2FA on IP Address Change</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_2fa_after_password_reset" name="require_2fa_after_password_reset" checked>
                            <label class="form-check-label" for="require_2fa_after_password_reset">Require 2FA After Password Reset</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Measures Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Additional Security Measures</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_ip_filtering" name="enable_ip_filtering">
                            <label class="form-check-label" for="enable_ip_filtering">Enable IP Filtering</label>
                        </div>
                        <div class="form-text">Restrict access to specific IP addresses or ranges.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="allowed_ips" class="form-label">Allowed IP Addresses/Ranges</label>
                        <textarea class="form-control" id="allowed_ips" name="allowed_ips" rows="3" placeholder="e.g., 192.168.1.1, 10.0.0.0/24"></textarea>
                        <div class="form-text">Enter one IP address or CIDR range per line. Leave blank to allow all.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_recaptcha" name="enable_recaptcha" checked>
                            <label class="form-check-label" for="enable_recaptcha">Enable reCAPTCHA</label>
                        </div>
                        <div class="form-text">Protect forms from automated bots and spam.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="recaptcha_site_key" class="form-label">reCAPTCHA Site Key</label>
                        <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key" value="6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                    </div>
                    
                    <div class="mb-3">
                        <label for="recaptcha_secret_key" class="form-label">reCAPTCHA Secret Key</label>
                        <input type="password" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key" value="6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_csrf_protection" name="enable_csrf_protection" checked>
                            <label class="form-check-label" for="enable_csrf_protection">Enable CSRF Protection</label>
                        </div>
                        <div class="form-text">Protect against Cross-Site Request Forgery attacks.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Security Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Content Security</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="allowed_file_types" class="form-label">Allowed File Types</label>
                                <select class="form-select" id="allowed_file_types" name="allowed_file_types[]" multiple>
                                    <option value="jpg" selected>JPG/JPEG</option>
                                    <option value="png" selected>PNG</option>
                                    <option value="pdf" selected>PDF</option>
                                    <option value="doc" selected>DOC/DOCX</option>
                                    <option value="xls">XLS/XLSX</option>
                                    <option value="txt" selected>TXT</option>
                                    <option value="zip">ZIP</option>
                                </select>
                                <div class="form-text">File types that can be uploaded to the system.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_file_size_mb" class="form-label">Maximum File Size (MB)</label>
                                <input type="number" class="form-control" id="max_file_size_mb" name="max_file_size_mb" value="5" min="1" max="50">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="scan_uploads_for_malware" name="scan_uploads_for_malware" checked>
                                    <label class="form-check-label" for="scan_uploads_for_malware">Scan Uploads for Malware</label>
                                </div>
                                <div class="form-text">Scan uploaded files for viruses and malware.</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="strip_exif_data" name="strip_exif_data" checked>
                                    <label class="form-check-label" for="strip_exif_data">Strip EXIF Data from Images</label>
                                </div>
                                <div class="form-text">Remove metadata from uploaded images for privacy.</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="encrypt_uploads" name="encrypt_uploads" checked>
                                    <label class="form-check-label" for="encrypt_uploads">Encrypt Uploaded Files</label>
                                </div>
                                <div class="form-text">Encrypt sensitive documents when stored.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="d-flex justify-content-end mt-2">
                <button type="reset" class="btn btn-light me-2">Reset Changes</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Save Security Settings
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Security Audit Card -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">Security Audit</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Last Security Audit</h6>
                    <span class="text-muted">May 12, 2023</span>
                </div>
                
                <div class="mb-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="small text-success">Security Score: 85/100</span>
                        <span class="small text-muted">Good</span>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Security Recommendations</h6>
                            <ul class="mb-0 ps-3">
                                <li>Enable 2FA for all customer accounts</li>
                                <li>Update SSL certificate (expires in 45 days)</li>
                                <li>Implement IP filtering for admin access</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Recent Security Events</h6>
                    <a href="{{ route('admin.logs') }}" class="text-primary small">View All</a>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon warning"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">Failed Login Attempt</span>
                                <span class="timeline-time">Today, 14:22</span>
                            </div>
                            <p class="mb-0 small">Multiple failed login attempts for user admin@fnbb.co.bw from IP 192.168.1.45</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon success"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">Security Setting Updated</span>
                                <span class="timeline-time">Yesterday, 11:05</span>
                            </div>
                            <p class="mb-0 small">Password policy updated by user john.doe@fnbb.co.bw</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon danger"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">Suspicious File Upload</span>
                                <span class="timeline-time">May 15, 09:47</span>
                            </div>
                            <p class="mb-0 small">Potentially malicious file blocked during upload (invoice_details.pdf.exe)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-outline-primary">
                <i class="fas fa-shield-alt me-2"></i> Run Security Audit
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 8px;
        width: 2px;
        background-color: var(--fnbb-gray-200);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-icon {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid var(--fnbb-primary);
        z-index: 1;
    }
    
    .timeline-icon.success {
        border-color: var(--fnbb-success);
    }
    
    .timeline-icon.warning {
        border-color: var(--fnbb-warning);
    }
    
    .timeline-icon.danger {
        border-color: var(--fnbb-danger);
    }
    
    .timeline-icon.info {
        border-color: var(--fnbb-info);
    }
    
    .timeline-content {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 0.5rem;
    }
    
    .timeline-time {
        font-size: 0.75rem;
        color: var(--fnbb-gray-500);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable/disable related fields based on toggle states
    document.getElementById('enable_2fa_customers')?.addEventListener('change', function() {
        const twoFaFields = document.querySelectorAll('#2fa_method, #2fa_code_expiry, #allow_2fa_remember, #2fa_remember_days');
        twoFaFields.forEach(field => {
            field.disabled = !document.getElementById('enable_2fa_admins').checked && 
                            !document.getElementById('enable_2fa_officers').checked && 
                            !this.checked;
        });
    });
    
    document.getElementById('enable_ip_filtering')?.addEventListener('change', function() {
        document.getElementById('allowed_ips').disabled = !this.checked;
    });
    
    document.getElementById('enable_recaptcha')?.addEventListener('change', function() {
        document.getElementById('recaptcha_site_key').disabled = !this.checked;
        document.getElementById('recaptcha_secret_key').disabled = !this.checked;
    });
    
    document.getElementById('allow_2fa_remember')?.addEventListener('change', function() {
        document.getElementById('2fa_remember_days').disabled = !this.checked;
    });
    
    // Initialize disabled states
    const allowIps = document.getElementById('allowed_ips');
    if (allowIps) {
        allowIps.disabled = !document.getElementById('enable_ip_filtering').checked;
    }
    
    const recaptchaFields = document.querySelectorAll('#recaptcha_site_key, #recaptcha_secret_key');
    recaptchaFields.forEach(field => {
        if (field) {
            field.disabled = !document.getElementById('enable_recaptcha').checked;
        }
    });
    
    const rememberDays = document.getElementById('2fa_remember_days');
    if (rememberDays) {
        rememberDays.disabled = !document.getElementById('allow_2fa_remember').checked;
    }
});
</script>
@endpush
@endsection