@extends('layouts.admin')

@section('title', 'API Configuration')
@section('page_title', 'API Configuration')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.settings') }}">Settings</a></li>
<li class="breadcrumb-item active" aria-current="page">API Configuration</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">API Configuration</h2>
        <p class="text-muted">Manage external API integrations and settings for the FNBB registration system</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApiKeyModal">
            <i class="fas fa-key me-2"></i> Generate New API Key
        </button>
    </div>
</div>

<!-- API Status Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-cloud"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">API Status</div>
                <div class="stat-value">
                    <span class="badge bg-success px-3 py-2">Online</span>
                </div>
                <div class="stat-change">
                    <span class="text-muted">100% uptime in last 7 days</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-key"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Keys</div>
                <div class="stat-value">4</div>
                <div class="stat-change">
                    <a href="#keys" class="text-success">
                        <i class="fas fa-list-ul"></i> Manage API keys
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Requests Today</div>
                <div class="stat-value">1,254</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 8.5% <span class="text-muted">vs yesterday</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Failed Requests</div>
                <div class="stat-value">12</div>
                <div class="stat-change">
                    <a href="#logs" class="text-warning">
                        <i class="fas fa-search"></i> View error logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- API Request Traffic Chart -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">API Traffic Overview</h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary active" data-time-range="day">Day</button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-time-range="week">Week</button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-time-range="month">Month</button>
        </div>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 300px;">
            <canvas id="apiTrafficChart"></canvas>
        </div>
    </div>
</div>

<!-- Tabs for API Configuration -->
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs card-header-tabs" id="apiConfigTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="keys-tab" data-bs-toggle="tab" data-bs-target="#keys" type="button" role="tab" aria-controls="keys" aria-selected="true">
                    <i class="fas fa-key me-2"></i> API Keys
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="integrations-tab" data-bs-toggle="tab" data-bs-target="#integrations" type="button" role="tab" aria-controls="integrations" aria-selected="false">
                    <i class="fas fa-plug me-2"></i> Integrations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="webhooks-tab" data-bs-toggle="tab" data-bs-target="#webhooks" type="button" role="tab" aria-controls="webhooks" aria-selected="false">
                    <i class="fas fa-bell me-2"></i> Webhooks
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab" aria-controls="logs" aria-selected="false">
                    <i class="fas fa-history me-2"></i> API Logs
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="apiConfigTabContent">
            <!-- API Keys Tab -->
            <div class="tab-pane fade show active" id="keys" role="tabpanel" aria-labelledby="keys-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">API Keys</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshKeys">
                            <i class="fas fa-sync-alt me-2"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table w-100">
                        <thead>
                            <tr>
                                <th>API Key</th>
                                <th>Name/Description</th>
                                <th>Created</th>
                                <th>Expires</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" value="fnbb_api_XYZ123abc456def789ghi" readonly>
                                        <button class="btn btn-outline-secondary btn-sm toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm copy-key" type="button" data-key="fnbb_api_XYZ123abc456def789ghi">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>FNBB Mobile App</td>
                                <td>15 Jan 2023</td>
                                <td>15 Jan 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#regenerateKeyModal" data-key-id="1" data-key-name="FNBB Mobile App">
                                            <i class="fas fa-sync-alt" data-bs-toggle="tooltip" title="Regenerate Key"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revokeKeyModal" data-key-id="1" data-key-name="FNBB Mobile App">
                                            <i class="fas fa-ban" data-bs-toggle="tooltip" title="Revoke Key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" value="fnbb_api_ABC987zyx654wvu321tsr" readonly>
                                        <button class="btn btn-outline-secondary btn-sm toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm copy-key" type="button" data-key="fnbb_api_ABC987zyx654wvu321tsr">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>Customer Portal</td>
                                <td>22 Mar 2023</td>
                                <td>22 Mar 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#regenerateKeyModal" data-key-id="2" data-key-name="Customer Portal">
                                            <i class="fas fa-sync-alt" data-bs-toggle="tooltip" title="Regenerate Key"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revokeKeyModal" data-key-id="2" data-key-name="Customer Portal">
                                            <i class="fas fa-ban" data-bs-toggle="tooltip" title="Revoke Key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" value="fnbb_api_DEF456ghi789jkl012mno" readonly>
                                        <button class="btn btn-outline-secondary btn-sm toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm copy-key" type="button" data-key="fnbb_api_DEF456ghi789jkl012mno">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>Verification Service</td>
                                <td>05 Apr 2023</td>
                                <td>05 Apr 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#regenerateKeyModal" data-key-id="3" data-key-name="Verification Service">
                                            <i class="fas fa-sync-alt" data-bs-toggle="tooltip" title="Regenerate Key"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revokeKeyModal" data-key-id="3" data-key-name="Verification Service">
                                            <i class="fas fa-ban" data-bs-toggle="tooltip" title="Revoke Key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" value="fnbb_api_GHI789jkl012mno345pqr" readonly>
                                        <button class="btn btn-outline-secondary btn-sm toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm copy-key" type="button" data-key="fnbb_api_GHI789jkl012mno345pqr">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>Test Environment</td>
                                <td>18 May 2023</td>
                                <td>18 Aug 2023</td>
                                <td><span class="badge bg-danger">Expired</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#regenerateKeyModal" data-key-id="4" data-key-name="Test Environment">
                                            <i class="fas fa-sync-alt" data-bs-toggle="tooltip" title="Regenerate Key"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revokeKeyModal" data-key-id="4" data-key-name="Test Environment">
                                            <i class="fas fa-ban" data-bs-toggle="tooltip" title="Revoke Key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header py-3">
                        <h5 class="card-title mb-0">API Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="api_key_expiry" class="form-label">Default API Key Expiry (days)</label>
                                        <input type="number" class="form-control" id="api_key_expiry" name="api_key_expiry" value="365" min="1">
                                        <div class="form-text">Number of days before API keys expire by default.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="enable_api_rate_limiting" name="enable_api_rate_limiting" checked>
                                            <label class="form-check-label" for="enable_api_rate_limiting">Enable API Rate Limiting</label>
                                        </div>
                                        <div class="form-text">Limit the number of API requests per client to prevent abuse.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="api_rate_limit" class="form-label">Rate Limit (requests per minute)</label>
                                        <input type="number" class="form-control" id="api_rate_limit" name="api_rate_limit" value="60" min="1">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="api_require_https" name="api_require_https" checked>
                                            <label class="form-check-label" for="api_require_https">Require HTTPS for API Requests</label>
                                        </div>
                                        <div class="form-text">Only accept API requests over secure HTTPS connections.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="api_cors_enabled" name="api_cors_enabled" checked>
                                            <label class="form-check-label" for="api_cors_enabled">Enable CORS</label>
                                        </div>
                                        <div class="form-text">Allow cross-origin requests from specified domains.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="api_allowed_origins" class="form-label">Allowed Origins</label>
                                        <textarea class="form-control" id="api_allowed_origins" name="api_allowed_origins" rows="3">https://app.fnbb.co.bw
https://customer.fnbb.co.bw
https://mobile.fnbb.co.bw</textarea>
                                        <div class="form-text">Enter one origin per line. Use * for wildcard.</div>
                                    </div>
                                </div>
                                
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save API Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Integrations Tab -->
            <div class="tab-pane fade" id="integrations" role="tabpanel" aria-labelledby="integrations-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">External Integrations</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addIntegrationModal">
                            <i class="fas fa-plus me-2"></i> Add Integration
                        </button>
                    </div>
                </div>
                
                <div class="row g-4">
                    <!-- Omang Verification Integration -->
                    <div class="col-md-6">
                        <div class="card h-100 border-left-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle icon-primary me-3" style="width: 48px; height: 48px;">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Omang Verification API</h5>
                                            <p class="text-muted mb-0 small">National ID Verification</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="omang_api_enabled" checked>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="omang_api_url" class="form-label">API Endpoint</label>
                                    <input type="text" class="form-control" id="omang_api_url" value="https://api.gov.bw/omang/verify" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="omang_api_key" class="form-label">API Key</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="omang_api_key" value="gov_api_123456789abcdef" readonly>
                                        <button class="btn btn-outline-secondary toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success me-2">Connected</span>
                                        <span class="small text-muted">Last check: 10 minutes ago</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-cog me-2"></i> Configure
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Face Recognition Integration -->
                    <div class="col-md-6">
                        <div class="card h-100 border-left-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle icon-info me-3" style="width: 48px; height: 48px;">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Face Recognition API</h5>
                                            <p class="text-muted mb-0 small">Biometric Verification</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="face_api_enabled" checked>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="face_api_url" class="form-label">API Endpoint</label>
                                    <input type="text" class="form-control" id="face_api_url" value="https://api.fnbb.co.bw/facial/verify" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="face_api_key" class="form-label">API Key</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="face_api_key" value="face_api_abc123xyz789" readonly>
                                        <button class="btn btn-outline-secondary toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success me-2">Connected</span>
                                        <span class="small text-muted">Last check: 5 minutes ago</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-cog me-2"></i> Configure
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SMS Gateway Integration -->
                    <div class="col-md-6">
                        <div class="card h-100 border-left-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle icon-warning me-3" style="width: 48px; height: 48px;">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">SMS Gateway API</h5>
                                            <p class="text-muted mb-0 small">SMS Notifications</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="sms_api_enabled" checked>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sms_api_url" class="form-label">API Endpoint</label>
                                    <input type="text" class="form-control" id="sms_api_url" value="https://api.messaging.com/sms/send" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sms_api_key" class="form-label">API Key</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="sms_api_key" value="sms_api_456def789ghi" readonly>
                                        <button class="btn btn-outline-secondary toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success me-2">Connected</span>
                                        <span class="small text-muted">Last check: 15 minutes ago</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-cog me-2"></i> Configure
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email Service Integration -->
                    <div class="col-md-6">
                        <div class="card h-100 border-left-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle icon-success me-3" style="width: 48px; height: 48px;">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Email Service API</h5>
                                            <p class="text-muted mb-0 small">Email Notifications</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_api_enabled" checked>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email_api_url" class="form-label">API Endpoint</label>
                                    <input type="text" class="form-control" id="email_api_url" value="https://api.mailservice.com/send" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email_api_key" class="form-label">API Key</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="email_api_key" value="email_api_789ghi012jkl" readonly>
                                        <button class="btn btn-outline-secondary toggle-key" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success me-2">Connected</span>
                                        <span class="small text-muted">Last check: 20 minutes ago</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-cog me-2"></i> Configure
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Webhooks Tab -->
            <div class="tab-pane fade" id="webhooks" role="tabpanel" aria-labelledby="webhooks-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Webhooks</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWebhookModal">
                            <i class="fas fa-plus me-2"></i> Add Webhook
                        </button>
                    </div>
                </div>
                
                <p class="text-muted mb-4">Webhooks allow external applications to receive real-time updates when specific events occur in the FNBB registration system.</p>
                
                <div class="table-responsive">
                    <table class="data-table w-100">
                        <thead>
                            <tr>
                                <th>Webhook URL</th>
                                <th>Description</th>
                                <th>Events</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>https://notifications.fnbb.co.bw/webhook/registrations</td>
                                <td>Registration Notifications</td>
                                <td>
                                    <span class="badge bg-primary me-1">user.registered</span>
                                    <span class="badge bg-primary me-1">profile.updated</span>
                                </td>
                                <td>01 Mar 2023</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editWebhookModal" data-webhook-id="1">
                                            <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testWebhookModal" data-webhook-id="1">
                                            <i class="fas fa-paper-plane" data-bs-toggle="tooltip" title="Test Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWebhookModal" data-webhook-id="1">
                                            <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Webhook"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>https://integrations.fnbb.co.bw/webhook/verifications</td>
                                <td>Verification Status Updates</td>
                                <td>
                                    <span class="badge bg-primary me-1">verification.started</span>
                                    <span class="badge bg-primary me-1">verification.completed</span>
                                    <span class="badge bg-primary me-1">verification.failed</span>
                                </td>
                                <td>15 Mar 2023</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editWebhookModal" data-webhook-id="2">
                                            <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testWebhookModal" data-webhook-id="2">
                                            <i class="fas fa-paper-plane" data-bs-toggle="tooltip" title="Test Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWebhookModal" data-webhook-id="2">
                                            <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Webhook"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>https://crm.example.com/api/webhook/fnbb</td>
                                <td>CRM Integration</td>
                                <td>
                                    <span class="badge bg-primary me-1">user.registered</span>
                                    <span class="badge bg-primary me-1">user.verified</span>
                                </td>
                                <td>20 Apr 2023</td>
                                <td><span class="badge bg-danger">Failed</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editWebhookModal" data-webhook-id="3">
                                            <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testWebhookModal" data-webhook-id="3">
                                            <i class="fas fa-paper-plane" data-bs-toggle="tooltip" title="Test Webhook"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWebhookModal" data-webhook-id="3">
                                            <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Webhook"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header py-3">
                        <h5 class="card-title mb-0">Webhook Events</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">These are the events that can trigger webhooks in the FNBB registration system.</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">User Events</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>user.registered</strong>
                                                <p class="text-muted mb-0 small">Triggered when a new user registers</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>user.verified</strong>
                                                <p class="text-muted mb-0 small">Triggered when a user is verified</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>user.rejected</strong>
                                                <p class="text-muted mb-0 small">Triggered when a user verification is rejected</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>profile.updated</strong>
                                                <p class="text-muted mb-0 small">Triggered when a user profile is updated</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card h-100 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Verification Events</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>verification.started</strong>
                                                <p class="text-muted mb-0 small">Triggered when a verification process begins</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>verification.completed</strong>
                                                <p class="text-muted mb-0 small">Triggered when a verification process completes</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>verification.failed</strong>
                                                <p class="text-muted mb-0 small">Triggered when a verification process fails</p>
                                            </li>
                                            <li class="list-group-item bg-transparent px-0">
                                                <strong>document.uploaded</strong>
                                                <p class="text-muted mb-0 small">Triggered when a document is uploaded</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- API Logs Tab -->
            <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">API Logs</h5>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="logFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="logFilterDropdown">
                                <li><a class="dropdown-item" href="#" data-log-filter="all">All Logs</a></li>
                                <li><a class="dropdown-item" href="#" data-log-filter="error">Errors Only</a></li>
                                <li><a class="dropdown-item" href="#" data-log-filter="success">Successful Requests</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-log-filter="today">Today</a></li>
                                <li><a class="dropdown-item" href="#" data-log-filter="week">This Week</a></li>
                                <li><a class="dropdown-item" href="#" data-log-filter="month">This Month</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshLogs">
                            <i class="fas fa-sync-alt me-2"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="exportLogs">
                            <i class="fas fa-download me-2"></i> Export
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table w-100">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>API Key</th>
                                <th>IP Address</th>
                                <th>Endpoint</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Response Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2023-06-08 14:45:22</td>
                                <td>fnbb_api_XYZ123abc456def789ghi</td>
                                <td>192.168.1.100</td>
                                <td>/api/users/verify</td>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td><span class="badge bg-success">200 OK</span></td>
                                <td>245 ms</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewLogDetailsModal" data-log-id="1">
                                        <i class="fas fa-eye" data-bs-toggle="tooltip" title="View Details"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-06-08 14:42:15</td>
                                <td>fnbb_api_ABC987zyx654wvu321tsr</td>
                                <td>192.168.1.105</td>
                                <td>/api/users/profile</td>
                                <td><span class="badge bg-info">GET</span></td>
                                <td><span class="badge bg-success">200 OK</span></td>
                                <td>98 ms</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewLogDetailsModal" data-log-id="2">
                                        <i class="fas fa-eye" data-bs-toggle="tooltip" title="View Details"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-06-08 14:40:57</td>
                                <td>fnbb_api_DEF456ghi789jkl012mno</td>
                                <td>192.168.1.110</td>
                                <td>/api/verification/status</td>
                                <td><span class="badge bg-warning text-dark">PUT</span></td>
                                <td><span class="badge bg-danger">400 Bad Request</span></td>
                                <td>156 ms</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewLogDetailsModal" data-log-id="3">
                                        <i class="fas fa-eye" data-bs-toggle="tooltip" title="View Details"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-06-08 14:38:42</td>
                                <td>fnbb_api_XYZ123abc456def789ghi</td>
                                <td>192.168.1.115</td>
                                <td>/api/documents/upload</td>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td><span class="badge bg-success">201 Created</span></td>
                                <td>345 ms</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewLogDetailsModal" data-log-id="4">
                                        <i class="fas fa-eye" data-bs-toggle="tooltip" title="View Details"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-06-08 14:35:18</td>
                                <td>fnbb_api_ABC987zyx654wvu321tsr</td>
                                <td>192.168.1.120</td>
                                <td>/api/notifications/send</td>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td><span class="badge bg-danger">500 Server Error</span></td>
                                <td>867 ms</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewLogDetailsModal" data-log-id="5">
                                        <i class="fas fa-eye" data-bs-toggle="tooltip" title="View Details"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">Showing 5 of 243 logs</div>
                    <nav aria-label="API logs pagination">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Add API Key Modal -->
<div class="modal fade" id="addApiKeyModal" tabindex="-1" aria-labelledby="addApiKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addApiKeyModalLabel">Generate New API Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addApiKeyForm" action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="api_key_name" class="form-label">Name/Description</label>
                        <input type="text" class="form-control" id="api_key_name" name="api_key_name" placeholder="e.g., Mobile App Integration" required>
                        <div class="form-text">Provide a descriptive name for this API key.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="api_key_expiry_days" class="form-label">Expiry (days)</label>
                        <input type="number" class="form-control" id="api_key_expiry_days" name="api_key_expiry_days" value="365" min="1" max="730" required>
                        <div class="form-text">Number of days before this key expires.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="api_key_permissions" class="form-label">Permissions</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm_read" name="permissions[]" value="read" checked>
                            <label class="form-check-label" for="perm_read">Read</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm_write" name="permissions[]" value="write" checked>
                            <label class="form-check-label" for="perm_write">Write</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm_delete" name="permissions[]" value="delete">
                            <label class="form-check-label" for="perm_delete">Delete</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="api_key_rate_limit" class="form-label">Rate Limit (requests per minute)</label>
                        <input type="number" class="form-control" id="api_key_rate_limit" name="api_key_rate_limit" value="60" min="1" max="1000" required>
                        <div class="form-text">Maximum number of requests per minute for this key.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addApiKeyForm" class="btn btn-primary">Generate Key</button>
            </div>
        </div>
    </div>
</div>

<!-- Regenerate API Key Modal -->
<div class="modal fade" id="regenerateKeyModal" tabindex="-1" aria-labelledby="regenerateKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="regenerateKeyModalLabel">Regenerate API Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Regenerating this API key will immediately invalidate the current key. Any services using this key will need to be updated.
                </div>
                <p>Are you sure you want to regenerate the API key for <strong id="regenerateKeyName">API Key</strong>?</p>
                <form id="regenerateKeyForm" action="#" method="POST">
                    @csrf
                    <input type="hidden" id="regenerate_key_id" name="key_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="regenerateKeyForm" class="btn btn-warning">Regenerate Key</button>
            </div>
        </div>
    </div>
</div>

<!-- Revoke API Key Modal -->
<div class="modal fade" id="revokeKeyModal" tabindex="-1" aria-labelledby="revokeKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revokeKeyModalLabel">Revoke API Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Revoking this API key will immediately invalidate it. Any services using this key will stop working.
                </div>
                <p>Are you sure you want to revoke the API key for <strong id="revokeKeyName">API Key</strong>?</p>
                <form id="revokeKeyForm" action="#" method="POST">
                    @csrf
                    <input type="hidden" id="revoke_key_id" name="key_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="revokeKeyForm" class="btn btn-danger">Revoke Key</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Integration Modal -->
<div class="modal fade" id="addIntegrationModal" tabindex="-1" aria-labelledby="addIntegrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIntegrationModalLabel">Add New Integration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addIntegrationForm" action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="integration_name" class="form-label">Integration Name</label>
                        <input type="text" class="form-control" id="integration_name" name="integration_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_type" class="form-label">Integration Type</label>
                        <select class="form-select" id="integration_type" name="integration_type" required>
                            <option value="" selected disabled>Select Integration Type</option>
                            <option value="verification">ID Verification</option>
                            <option value="facial">Facial Recognition</option>
                            <option value="sms">SMS Gateway</option>
                            <option value="email">Email Service</option>
                            <option value="payment">Payment Gateway</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_url" class="form-label">API Endpoint URL</label>
                        <input type="url" class="form-control" id="integration_url" name="integration_url" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_key" class="form-label">API Key/Secret</label>
                        <input type="text" class="form-control" id="integration_key" name="integration_key" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="integration_enabled" name="integration_enabled" checked>
                            <label class="form-check-label" for="integration_enabled">Enable Integration</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addIntegrationForm" class="btn btn-primary">Add Integration</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Webhook Modal -->
<div class="modal fade" id="addWebhookModal" tabindex="-1" aria-labelledby="addWebhookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWebhookModalLabel">Add New Webhook</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addWebhookForm" action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="webhook_url" class="form-label">Webhook URL</label>
                        <input type="url" class="form-control" id="webhook_url" name="webhook_url" placeholder="https://example.com/webhook" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="webhook_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="webhook_description" name="webhook_description" placeholder="Describe the purpose of this webhook" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Events to Subscribe</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_user_registered" name="events[]" value="user.registered">
                                    <label class="form-check-label" for="event_user_registered">user.registered</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_user_verified" name="events[]" value="user.verified">
                                    <label class="form-check-label" for="event_user_verified">user.verified</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_user_rejected" name="events[]" value="user.rejected">
                                    <label class="form-check-label" for="event_user_rejected">user.rejected</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_profile_updated" name="events[]" value="profile.updated">
                                    <label class="form-check-label" for="event_profile_updated">profile.updated</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_verification_started" name="events[]" value="verification.started">
                                    <label class="form-check-label" for="event_verification_started">verification.started</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_verification_completed" name="events[]" value="verification.completed">
                                    <label class="form-check-label" for="event_verification_completed">verification.completed</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_verification_failed" name="events[]" value="verification.failed">
                                    <label class="form-check-label" for="event_verification_failed">verification.failed</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="event_document_uploaded" name="events[]" value="document.uploaded">
                                    <label class="form-check-label" for="event_document_uploaded">document.uploaded</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="webhook_secret" class="form-label">Secret Key</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="webhook_secret" name="webhook_secret" placeholder="Leave blank to auto-generate">
                            <button class="btn btn-outline-secondary" type="button" id="generateSecretBtn">
                                Generate
                            </button>
                        </div>
                        <div class="form-text">Used to verify webhook requests are coming from FNBB.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="webhook_active" name="webhook_active" checked>
                            <label class="form-check-label" for="webhook_active">Active</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addWebhookForm" class="btn btn-primary">Add Webhook</button>
            </div>
        </div>
    </div>
</div>

<!-- View Log Details Modal -->
<div class="modal fade" id="viewLogDetailsModal" tabindex="-1" aria-labelledby="viewLogDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLogDetailsModalLabel">API Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Basic Information</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%">Timestamp</th>
                                        <td>2023-06-08 14:45:22</td>
                                    </tr>
                                    <tr>
                                        <th>API Key</th>
                                        <td>fnbb_api_XYZ123abc456def789ghi</td>
                                    </tr>
                                    <tr>
                                        <th>Client IP</th>
                                        <td>192.168.1.100</td>
                                    </tr>
                                    <tr>
                                        <th>User Agent</th>
                                        <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Request Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%">Method</th>
                                        <td><span class="badge bg-primary">POST</span></td>
                                    </tr>
                                    <tr>
                                        <th>Endpoint</th>
                                        <td>/api/users/verify</td>
                                    </tr>
                                    <tr>
                                        <th>Status Code</th>
                                        <td><span class="badge bg-success">200 OK</span></td>
                                    </tr>
                                    <tr>
                                        <th>Response Time</th>
                                        <td>245 ms</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Request Headers</h6>
                        <div class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            <pre class="mb-0"><code>Content-Type: application/json
Authorization: Bearer fnbb_api_XYZ123abc456def789ghi
Accept: application/json
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)
X-Request-ID: a1b2c3d4-e5f6-7890-abcd-ef1234567890</code></pre>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Request Body</h6>
                        <div class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            <pre class="mb-0"><code>{
  "user_id": 12345,
  "verification_type": "omang",
  "verification_data": {
    "omang_number": "98765432",
    "full_name": "John Doe",
    "date_of_birth": "1990-01-15"
  }
}</code></pre>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Response Body</h6>
                        <div class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            <pre class="mb-0"><code>{
  "status": "success",
  "message": "Verification successful",
  "data": {
    "verification_id": "ver_a1b2c3d4e5f6",
    "user_id": 12345,
    "verification_status": "verified",
    "timestamp": "2023-06-08T14:45:22Z"
  }
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copyLogDetails">
                    <i class="fas fa-copy me-2"></i> Copy Details
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Toggle API key visibility
    document.querySelectorAll('.toggle-key').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
    
    // Copy API key to clipboard
    document.querySelectorAll('.copy-key').forEach(button => {
        button.addEventListener('click', function() {
            const key = this.dataset.key;
            navigator.clipboard.writeText(key).then(() => {
                // Show success tooltip
                const tooltip = new bootstrap.Tooltip(this, {
                    title: 'Copied!',
                    trigger: 'manual',
                    placement: 'top'
                });
                tooltip.show();
                setTimeout(() => tooltip.hide(), 2000);
            });
        });
    });
    
    // Handle regenerate key modal
    const regenerateKeyModal = document.getElementById('regenerateKeyModal');
    if (regenerateKeyModal) {
        regenerateKeyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const keyId = button.dataset.keyId;
            const keyName = button.dataset.keyName;
            
            document.getElementById('regenerate_key_id').value = keyId;
            document.getElementById('regenerateKeyName').textContent = keyName;
        });
    }
    
    // Handle revoke key modal
    const revokeKeyModal = document.getElementById('revokeKeyModal');
    if (revokeKeyModal) {
        revokeKeyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const keyId = button.dataset.keyId;
            const keyName = button.dataset.keyName;
            
            document.getElementById('revoke_key_id').value = keyId;
            document.getElementById('revokeKeyName').textContent = keyName;
        });
    }
    
    // Generate webhook secret
    document.getElementById('generateSecretBtn')?.addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let secret = '';
        for (let i = 0; i < 32; i++) {
            secret += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('webhook_secret').value = secret;
    });
    
    // API Traffic Chart
    const apiTrafficCtx = document.getElementById('apiTrafficChart');
    if (apiTrafficCtx) {
        const apiTrafficChart = new Chart(apiTrafficCtx, {
            type: 'line',
            data: {
                labels: ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'],
                datasets: [
                    {
                        label: 'Successful Requests',
                        data: [32, 25, 20, 18, 29, 45, 60, 78, 85, 92, 76, 65],
                        backgroundColor: 'rgba(12, 170, 104, 0.1)',
                        borderColor: '#0CAA68',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Failed Requests',
                        data: [3, 2, 1, 0, 1, 2, 4, 3, 5, 4, 3, 2],
                        backgroundColor: 'rgba(233, 78, 77, 0.1)',
                        borderColor: '#E94E4D',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                }
            }
        });
        
        // Handle time range buttons
        document.querySelectorAll('[data-time-range]').forEach(button => {
            button.addEventListener('click', function() {
                const timeRange = this.dataset.timeRange;
                let labels, successData, failedData;
                
                // Remove active class from all buttons
                document.querySelectorAll('[data-time-range]').forEach(btn => {
                    btn.classList.remove('active');
                });
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update chart data based on time range
                if (timeRange === 'day') {
                    labels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
                    successData = [32, 25, 20, 18, 29, 45, 60, 78, 85, 92, 76, 65];
                    failedData = [3, 2, 1, 0, 1, 2, 4, 3, 5, 4, 3, 2];
                } else if (timeRange === 'week') {
                    labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    successData = [320, 350, 400, 380, 420, 280, 250];
                    failedData = [15, 18, 22, 17, 20, 12, 10];
                } else if (timeRange === 'month') {
                    labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    successData = [1200, 1350, 1420, 1380];
                    failedData = [65, 72, 80, 68];
                }
                
                // Update chart data
                apiTrafficChart.data.labels = labels;
                apiTrafficChart.data.datasets[0].data = successData;
                apiTrafficChart.data.datasets[1].data = failedData;
                apiTrafficChart.update();
            });
        });
    }
    
    // Copy log details
    document.getElementById('copyLogDetails')?.addEventListener('click', function() {
        const logDetails = document.querySelector('#viewLogDetailsModal .modal-body').innerText;
        navigator.clipboard.writeText(logDetails).then(() => {
            // Show success alert
            const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> Log details copied to clipboard.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            document.querySelector('#viewLogDetailsModal .modal-body').insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-dismiss after 2 seconds
            setTimeout(() => {
                const alert = document.querySelector('#viewLogDetailsModal .alert');
                if (alert) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection