@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'System Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">System Settings</h2>
        <p class="text-muted">Configure system preferences and options.</p>
    </div>
    <div>
        <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary">
            <i class="fas fa-history me-2"></i> View System Logs
        </a>
    </div>
</div>

<!-- Settings Tabs -->
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                    <i class="fas fa-cogs me-2"></i> General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                    <i class="fas fa-envelope me-2"></i> Email
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification" type="button" role="tab" aria-controls="verification" aria-selected="false">
                    <i class="fas fa-id-card me-2"></i> Verification
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-controls="appearance" aria-selected="false">
                    <i class="fas fa-paint-brush me-2"></i> Appearance
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                    <i class="fas fa-bell me-2"></i> Notifications
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="settingsTabContent">
            <!-- General Settings Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="FNBB Registration Portal">
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="support@fnbb.co.bw">
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="+267 364 2800">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="timezone" class="form-label">Default Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="Africa/Gaborone" selected>Africa/Gaborone</option>
                                    <option value="UTC">UTC</option>
                                    <option value="Africa/Johannesburg">Africa/Johannesburg</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select" id="date_format" name="date_format">
                                    <option value="d/m/Y" selected>DD/MM/YYYY (31/12/2023)</option>
                                    <option value="m/d/Y">MM/DD/YYYY (12/31/2023)</option>
                                    <option value="Y-m-d">YYYY-MM-DD (2023-12-31)</option>
                                    <option value="d M, Y">DD Month, YYYY (31 Dec, 2023)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="maintenance_mode" class="form-label">Maintenance Mode</label>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                        <label class="form-check-label" for="maintenance_mode">Enable maintenance mode</label>
                                    </div>
                                </div>
                                <div class="form-text">When enabled, the site will be inaccessible to users except administrators.</div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="footer_text" class="form-label">Footer Text</label>
                                <textarea class="form-control" id="footer_text" name="footer_text" rows="2">© {{ date('Y') }} First National Bank of Botswana. All rights reserved.</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save General Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Email Settings Tab -->
            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_driver" class="form-label">Mail Driver</label>
                                <select class="form-select" id="mail_driver" name="mail_driver">
                                    <option value="smtp" selected>SMTP</option>
                                    <option value="sendmail">Sendmail</option>
                                    <option value="mailgun">Mailgun</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_host" class="form-label">SMTP Host</label>
                                <input type="text" class="form-control" id="mail_host" name="mail_host" value="smtp.fnbb.co.bw">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_port" class="form-label">SMTP Port</label>
                                <input type="text" class="form-control" id="mail_port" name="mail_port" value="587">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_encryption" class="form-label">Encryption</label>
                                <select class="form-select" id="mail_encryption" name="mail_encryption">
                                    <option value="tls" selected>TLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_username" class="form-label">SMTP Username</label>
                                <input type="text" class="form-control" id="mail_username" name="mail_username" value="noreply@fnbb.co.bw">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_password" class="form-label">SMTP Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="mail_password" name="mail_password" value="••••••••••••">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleMailPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_from_address" class="form-label">From Address</label>
                                <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" value="noreply@fnbb.co.bw">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mail_from_name" class="form-label">From Name</label>
                                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" value="FNBB Registration">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-outline-primary me-3" id="testEmailConnection">
                                    <i class="fas fa-paper-plane me-2"></i> Test Connection
                                </button>
                                <div id="testEmailResult"></div>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Email Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Verification Settings Tab -->
            <div class="tab-pane fade" id="verification" role="tabpanel" aria-labelledby="verification-tab">
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">Verification Requirements</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_omang" name="require_omang" checked>
                                            <label class="form-check-label" for="require_omang">Require Omang ID</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_address_proof" name="require_address_proof" checked>
                                            <label class="form-check-label" for="require_address_proof">Require Proof of Address</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_facial_verification" name="require_facial_verification" checked>
                                            <label class="form-check-label" for="require_facial_verification">Require Facial Verification</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_phone_verification" name="require_phone_verification" checked>
                                            <label class="form-check-label" for="require_phone_verification">Require Phone Verification</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_email_verification" name="require_email_verification" checked>
                                            <label class="form-check-label" for="require_email_verification">Require Email Verification</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">Facial Verification Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="face_match_threshold" class="form-label">Face Match Threshold (%)</label>
                                        <input type="number" class="form-control" id="face_match_threshold" name="face_match_threshold" value="80" min="50" max="100">
                                        <div class="form-text">Minimum similarity percentage required for verification.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="face_verification_attempts" class="form-label">Maximum Verification Attempts</label>
                                        <input type="number" class="form-control" id="face_verification_attempts" name="face_verification_attempts" value="3" min="1" max="10">
                                        <div class="form-text">Number of attempts allowed before locking the account.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="enable_liveness_detection" name="enable_liveness_detection" checked>
                                            <label class="form-check-label" for="enable_liveness_detection">Enable Liveness Detection</label>
                                        </div>
                                        <div class="form-text">Detect if the person is physically present during verification.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="auto_approve_high_confidence" name="auto_approve_high_confidence">
                                            <label class="form-check-label" for="auto_approve_high_confidence">Auto-approve High Confidence Matches</label>
                                        </div>
                                        <div class="form-text">Automatically approve verifications with high confidence scores.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">Document Verification</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="allowed_document_types" class="form-label">Allowed Document Types</label>
                                                <select class="form-select" id="allowed_document_types" name="allowed_document_types[]" multiple>
                                                    <option value="jpg" selected>JPG/JPEG</option>
                                                    <option value="png" selected>PNG</option>
                                                    <option value="pdf" selected>PDF</option>
                                                    <option value="doc">DOC/DOCX</option>
                                                </select>
                                                <div class="form-text">Hold Ctrl/Cmd to select multiple types.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="max_file_size" class="form-label">Maximum File Size (MB)</label>
                                                <input type="number" class="form-control" id="max_file_size" name="max_file_size" value="5" min="1" max="20">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="document_retention_period" class="form-label">Document Retention Period (days)</label>
                                                <input type="number" class="form-control" id="document_retention_period" name="document_retention_period" value="365" min="30">
                                                <div class="form-text">Days to keep documents after account closure.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enable_document_encryption" name="enable_document_encryption" checked>
                                                    <label class="form-check-label" for="enable_document_encryption">Enable Document Encryption</label>
                                                </div>
                                                <div class="form-text">Encrypt stored documents for enhanced security.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Verification Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Appearance Settings Tab -->
            <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Logo</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset('images/fnbb-logo.png') }}" alt="Site Logo" class="img-thumbnail me-3" style="height: 60px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-upload me-2"></i> Change Logo
                                    </button>
                                </div>
                                <input type="file" class="form-control d-none" id="logo_file" name="logo_file" accept="image/*">
                                <div class="form-text">Recommended size: 200x60 pixels, PNG with transparency.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Favicon</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset('images/favicon.png') }}" alt="Favicon" class="img-thumbnail me-3" style="height: 32px; width: 32px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-upload me-2"></i> Change Favicon
                                    </button>
                                </div>
                                <input type="file" class="form-control d-none" id="favicon_file" name="favicon_file" accept="image/png,image/x-icon">
                                <div class="form-text">Recommended size: 32x32 pixels, PNG or ICO format.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="#025C7A">
                                    <input type="text" class="form-control" value="#025C7A" id="primary_color_text">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="#FF8200">
                                    <input type="text" class="form-control" value="#FF8200" id="secondary_color_text">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="font_family" class="form-label">Font Family</label>
                                <select class="form-select" id="font_family" name="font_family">
                                    <option value="Inter" selected>Inter</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Open Sans">Open Sans</option>
                                    <option value="Poppins">Poppins</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">Login Page</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="login_title" class="form-label">Login Page Title</label>
                                                <input type="text" class="form-control" id="login_title" name="login_title" value="Welcome to FNBB Registration Portal">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="login_subtitle" class="form-label">Login Page Subtitle</label>
                                                <input type="text" class="form-control" id="login_subtitle" name="login_subtitle" value="Sign in to your account or register as a new customer">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Background Image</label>
                                                <div class="d-flex align-items-start mb-2">
                                                    <img src="{{ asset('images/login-bg.jpg') }}" alt="Login Background" class="img-thumbnail me-3" style="height: 80px;">
                                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-upload me-2"></i> Change Background
                                                    </button>
                                                </div>
                                                <input type="file" class="form-control d-none" id="login_bg_file" name="login_bg_file" accept="image/*">
                                                <div class="form-text">Recommended size: 1920x1080 pixels, JPG or PNG format.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Appearance Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Notifications Settings Tab -->
            <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">Email Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_new_registration" name="notify_new_registration" checked>
                                            <label class="form-check-label" for="notify_new_registration">New Registration</label>
                                        </div>
                                        <div class="form-text">Send notification when a new customer registers.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_verification_complete" name="notify_verification_complete" checked>
                                            <label class="form-check-label" for="notify_verification_complete">Verification Complete</label>
                                        </div>
                                        <div class="form-text">Send notification when a verification is completed.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_verification_rejected" name="notify_verification_rejected" checked>
                                            <label class="form-check-label" for="notify_verification_rejected">Verification Rejected</label>
                                        </div>
                                        <div class="form-text">Send notification when a verification is rejected.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_document_uploaded" name="notify_document_uploaded" checked>
                                            <label class="form-check-label" for="notify_document_uploaded">Document Uploaded</label>
                                        </div>
                                        <div class="form-text">Send notification when documents are uploaded.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notification_recipients" class="form-label">Notification Recipients</label>
                                        <input type="text" class="form-control" id="notification_recipients" name="notification_recipients" value="admin@fnbb.co.bw,operations@fnbb.co.bw">
                                        <div class="form-text">Comma-separated list of email addresses.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">System Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_system_error" name="notify_system_error" checked>
                                            <label class="form-check-label" for="notify_system_error">System Errors</label>
                                        </div>
                                        <div class="form-text">Send notification for system errors and exceptions.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_login_attempt" name="notify_login_attempt">
                                            <label class="form-check-label" for="notify_login_attempt">Login Attempts</label>
                                        </div>
                                        <div class="form-text">Send notification for all login attempts.</div>
                                    </div>

                                    div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_failed_login" name="notify_failed_login" checked>
                                            <label class="form-check-label" for="notify_failed_login">Failed Logins</label>
                                        </div>
                                        <div class="form-text">Send notification for failed login attempts.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="notify_user_created" name="notify_user_created" checked>
                                            <label class="form-check-label" for="notify_user_created">User Created</label>
                                        </div>
                                        <div class="form-text">Send notification when a new user is created.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notification_level" class="form-label">Notification Level</label>
                                        <select class="form-select" id="notification_level" name="notification_level">
                                            <option value="all">All (Information, Warnings, Errors)</option>
                                            <option value="warnings_errors" selected>Warnings & Errors Only</option>
                                            <option value="errors_only">Errors Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header py-3">
                                    <h5 class="card-title mb-0">SMS Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="enable_sms" name="enable_sms" checked>
                                                    <label class="form-check-label" for="enable_sms">Enable SMS Notifications</label>
                                                </div>
                                                <div class="form-text">Send SMS notifications to customers.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="sms_provider" class="form-label">SMS Provider</label>
                                                <select class="form-select" id="sms_provider" name="sms_provider">
                                                    <option value="twilio" selected>Twilio</option>
                                                    <option value="africas_talking">Africa's Talking</option>
                                                    <option value="custom">Custom API</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sms_api_key" class="form-label">API Key</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="sms_api_key" name="sms_api_key" value="••••••••••••••••••••••••••••••">
                                                    <button class="btn btn-outline-secondary" type="button" id="toggleSmsApiKey">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="sms_sender_id" class="form-label">Sender ID</label>
                                                <input type="text" class="form-control" id="sms_sender_id" name="sms_sender_id" value="FNBB">
                                                <div class="form-text">The sender name that appears on SMS messages.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Notification Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleMailPassword = document.getElementById('toggleMailPassword');
    const mailPassword = document.getElementById('mail_password');
    
    toggleMailPassword?.addEventListener('click', function() {
        const type = mailPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        mailPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Toggle SMS API Key visibility
    const toggleSmsApiKey = document.getElementById('toggleSmsApiKey');
    const smsApiKey = document.getElementById('sms_api_key');
    
    toggleSmsApiKey?.addEventListener('click', function() {
        const type = smsApiKey.getAttribute('type') === 'password' ? 'text' : 'password';
        smsApiKey.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Sync color input with text
    const primaryColor = document.getElementById('primary_color');
    const primaryColorText = document.getElementById('primary_color_text');
    
    primaryColor?.addEventListener('input', function() {
        primaryColorText.value = this.value;
    });
    
    primaryColorText?.addEventListener('input', function() {
        primaryColor.value = this.value;
    });
    
    const secondaryColor = document.getElementById('secondary_color');
    const secondaryColorText = document.getElementById('secondary_color_text');
    
    secondaryColor?.addEventListener('input', function() {
        secondaryColorText.value = this.value;
    });
    
    secondaryColorText?.addEventListener('input', function() {
        secondaryColor.value = this.value;
    });
    
    // File upload buttons
    document.querySelectorAll('.btn-outline-primary').forEach(button => {
        button.addEventListener('click', function() {
            const fileInput = this.parentNode.nextElementSibling;
            if (fileInput && fileInput.type === 'file') {
                fileInput.click();
            }
        });
    });
    
    // Test email connection
    document.getElementById('testEmailConnection')?.addEventListener('click', function() {
        const resultDiv = document.getElementById('testEmailResult');
        resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Testing connection...';
        
        // Simulate testing - replace with actual AJAX call
        setTimeout(function() {
            resultDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-2"></i> Connection successful!</span>';
        }, 2000);
    });
    
    // Tab memory using URL hash
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`#settingsTabs button[data-bs-target="${hash}"]`);
        if (tab) {
            new bootstrap.Tab(tab).show();
        }
    }
    
    // Update URL hash when tabs change
    const tabEls = document.querySelectorAll('#settingsTabs button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            window.location.hash = event.target.dataset.bsTarget;
        });
    });
});
</script>
@endpush
@endsection