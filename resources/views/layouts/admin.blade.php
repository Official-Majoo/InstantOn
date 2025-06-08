<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | FNBB Online Banking</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin Styles -->
    <style>
        :root {
            --fnbb-primary: #025C7A;
            --fnbb-primary-dark: #014B63;
            --fnbb-primary-light: #E6F4F9;
            --fnbb-secondary: #FF8200;
            --fnbb-secondary-light: #FFE7CC;
            --fnbb-gray-100: #f8f9fa;
            --fnbb-gray-200: #e9ecef;
            --fnbb-gray-300: #dee2e6;
            --fnbb-gray-400: #ced4da;
            --fnbb-gray-500: #adb5bd;
            --fnbb-gray-600: #6c757d;
            --fnbb-gray-700: #495057;
            --fnbb-gray-800: #343a40;
            --fnbb-gray-900: #212529;
            --fnbb-success: #0CAA68;
            --fnbb-success-light: #E8F8F3;
            --fnbb-warning: #FFC336;
            --fnbb-warning-light: #FFF8E6;
            --fnbb-danger: #E94E4D;
            --fnbb-danger-light: #FCEAEA;
            --fnbb-info: #0CAADB;
            --fnbb-info-light: #E6F8FD;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F5F8FA;
            color: var(--fnbb-gray-800);
            line-height: 1.5;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Layout Components */
        .app-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: var(--fnbb-primary-dark);
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            transform: translateX(-280px);
        }
        
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .content-wrapper {
            flex-grow: 1;
            padding: 1.5rem;
            padding-top: 80px;
        }
        
        /* Header & Navigation */
        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header .logo {
            height: 40px;
        }
        
        .sidebar-header .brand-text {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin-left: 1rem;
        }
        
        .sidebar-close {
            color: white;
            background: transparent;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.15s ease;
        }
        
        .sidebar-close:hover {
            opacity: 1;
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section-title {
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .nav-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.15s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: white;
            border-left-color: var(--fnbb-secondary);
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }
        
        .nav-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
            font-size: 1rem;
            text-align: center;
        }
        
        .nav-link .nav-text {
            flex-grow: 1;
        }
        
        .nav-link .badge {
            margin-left: 0.5rem;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
        }
        
        /* Top Bar */
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            height: 70px;
            background-color: white;
            z-index: 90;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .topbar.expanded {
            left: 0;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .menu-toggle {
            background: transparent;
            border: none;
            font-size: 1.25rem;
            color: var(--fnbb-gray-600);
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 0.5rem;
            border-radius: 0.25rem;
            display: none;
        }
        
        .menu-toggle:hover {
            background-color: var(--fnbb-gray-100);
            color: var(--fnbb-gray-900);
        }
        
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--fnbb-gray-800);
            margin: 0;
        }
        
        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
        }
        
        .breadcrumb-item a {
            color: var(--fnbb-gray-600);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: var(--fnbb-primary);
        }
        
        .breadcrumb-item.active {
            color: var(--fnbb-gray-800);
            font-weight: 500;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            background-color: var(--fnbb-gray-100);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            width: 300px;
            transition: all 0.3s ease;
        }
        
        .search-form:focus-within {
            background-color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .search-input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.875rem;
            color: var(--fnbb-gray-800);
            width: 100%;
            padding-right: 0.5rem;
        }
        
        .search-input::placeholder {
            color: var(--fnbb-gray-500);
        }
        
        .search-btn {
            background: transparent;
            border: none;
            color: var(--fnbb-gray-500);
            cursor: pointer;
            padding: 0;
            transition: color 0.15s ease;
        }
        
        .search-btn:hover {
            color: var(--fnbb-primary);
        }
        
        /* Top Icons */
        .topbar-icon {
            position: relative;
            color: var(--fnbb-gray-600);
            background: transparent;
            border: none;
            font-size: 1.25rem;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .topbar-icon:hover {
            background-color: var(--fnbb-gray-100);
            color: var(--fnbb-gray-900);
        }
        
        .topbar-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.65rem;
            border-radius: 50%;
            padding: 0.25rem;
            min-width: 1.25rem;
            min-height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .user-dropdown:hover {
            background-color: var(--fnbb-gray-100);
        }
        
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--fnbb-primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--fnbb-primary);
        }
        
        .user-info {
            display: none;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--fnbb-gray-800);
            margin: 0;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--fnbb-gray-600);
            margin: 0;
        }
        
        .dropdown-arrow {
            color: var(--fnbb-gray-500);
            transition: transform 0.15s ease;
        }
        
        .user-dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        /* Footer */
        .app-footer {
            background-color: white;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: var(--fnbb-gray-600);
            border-top: 1px solid var(--fnbb-gray-200);
            text-align: center;
        }
        
        /* Components */
        .dropdown-menu {
            padding: 0.5rem 0;
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: 0.375rem;
        }
        
        .dropdown-item {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
        }
        
        .dropdown-item i {
            margin-right: 0.75rem;
            width: 1rem;
            text-align: center;
            color: var(--fnbb-gray-500);
        }
        
        .dropdown-item:active {
            background-color: var(--fnbb-primary);
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
        }
        
        .dropdown-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--fnbb-gray-500);
        }
        
        .card {
            background-color: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--fnbb-gray-200);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--fnbb-gray-800);
        }
        
        .card-tools {
            display: flex;
            gap: 0.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            background-color: white;
            border-top: 1px solid var(--fnbb-gray-200);
            padding: 1rem 1.5rem;
        }
        
        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn i {
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background-color: var(--fnbb-primary);
            border-color: var(--fnbb-primary);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--fnbb-primary-dark);
            border-color: var(--fnbb-primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--fnbb-secondary);
            border-color: var(--fnbb-secondary);
            color: white;
        }
        
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: #E67600;
            border-color: #E67600;
            color: white;
        }
        
        .btn-outline-primary {
            color: var(--fnbb-primary);
            border-color: var(--fnbb-primary);
        }
        
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--fnbb-primary);
            color: white;
        }
        
        .btn-light {
            background-color: var(--fnbb-gray-100);
            border-color: var(--fnbb-gray-100);
            color: var(--fnbb-gray-800);
        }
        
        .btn-light:hover, .btn-light:focus {
            background-color: var(--fnbb-gray-200);
            border-color: var(--fnbb-gray-200);
            color: var(--fnbb-gray-900);
        }
        
        .btn-icon {
            width: 2.25rem;
            height: 2.25rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        /* Status Badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.01em;
            padding: 0.35em 0.65em;
            border-radius: 0.375rem;
        }
        
        .badge-primary {
            background-color: var(--fnbb-primary-light);
            color: var(--fnbb-primary);
        }
        
        .badge-secondary {
            background-color: var(--fnbb-secondary-light);
            color: var(--fnbb-secondary);
        }
        
        .badge-success {
            background-color: var(--fnbb-success-light);
            color: var(--fnbb-success);
        }
        
        .badge-warning {
            background-color: var(--fnbb-warning-light);
            color: #B38000;
        }
        
        .badge-danger {
            background-color: var(--fnbb-danger-light);
            color: var(--fnbb-danger);
        }
        
        .badge-info {
            background-color: var(--fnbb-info-light);
            color: var(--fnbb-info);
        }
        
        /* Stat Cards */
        .stat-card {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .icon-primary {
            background-color: var(--fnbb-primary-light);
            color: var(--fnbb-primary);
        }
        
        .icon-success {
            background-color: var(--fnbb-success-light);
            color: var(--fnbb-success);
        }
        
        .icon-warning {
            background-color: var(--fnbb-warning-light);
            color: #B38000;
        }
        
        .icon-danger {
            background-color: var(--fnbb-danger-light);
            color: var(--fnbb-danger);
        }
        
        .icon-info {
            background-color: var(--fnbb-info-light);
            color: var(--fnbb-info);
        }
        
        .icon-secondary {
            background-color: var(--fnbb-secondary-light);
            color: var(--fnbb-secondary);
        }
        
        .stat-content {
            flex-grow: 1;
        }
        
        .stat-label {
            color: var(--fnbb-gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--fnbb-gray-900);
            margin-bottom: 0.25rem;
        }
        
        .stat-change {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .change-positive {
            color: var(--fnbb-success);
        }
        
        .change-negative {
            color: var(--fnbb-danger);
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: var(--fnbb-gray-700);
            border-top: none;
            background-color: var(--fnbb-gray-50);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            color: var(--fnbb-gray-800);
            border-color: var(--fnbb-gray-200);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(2, 92, 122, 0.03);
        }
        
        /* Custom Table Styles */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            color: var(--fnbb-gray-700);
            background-color: #F9FAFB;
            border-bottom: 1px solid var(--fnbb-gray-200);
        }
        
        .data-table th:first-child {
            border-top-left-radius: 0.5rem;
        }
        
        .data-table th:last-child {
            border-top-right-radius: 0.5rem;
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--fnbb-gray-200);
        }
        
        .data-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }
        
        .data-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }
        
        .data-table tbody tr:hover {
            background-color: rgba(2, 92, 122, 0.03);
        }
        
        /* User Card */
        .user-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-card .avatar {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .user-card .user-info {
            display: block;
        }
        
        .user-card .user-name {
            font-size: 0.875rem;
            margin: 0;
        }
        
        .user-card .user-email {
            font-size: 0.75rem;
            color: var(--fnbb-gray-500);
            margin: 0;
        }
        
        /* Timeline Component */
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
        
        /* Form Styles */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--fnbb-gray-700);
        }
        
        .form-control {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid var(--fnbb-gray-300);
            transition: all 0.15s ease;
        }
        
        .form-control:focus {
            border-color: var(--fnbb-primary);
            box-shadow: 0 0 0 0.2rem rgba(2, 92, 122, 0.15);
        }
        
        .form-select {
            padding: 0.5rem 2.25rem 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid var(--fnbb-gray-300);
            transition: all 0.15s ease;
        }
        
        .form-select:focus {
            border-color: var(--fnbb-primary);
            box-shadow: 0 0 0 0.2rem rgba(2, 92, 122, 0.15);
        }
        
        .form-check-input:checked {
            background-color: var(--fnbb-primary);
            border-color: var(--fnbb-primary);
        }
        
        .input-group-text {
            background-color: var(--fnbb-gray-100);
            border: 1px solid var(--fnbb-gray-300);
        }
        
        /* Status Indicators */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-verified {
            background-color: var(--fnbb-success-light);
            color: var(--fnbb-success);
        }
        
        .status-pending {
            background-color: var(--fnbb-warning-light);
            color: #B38000;
        }
        
        .status-rejected {
            background-color: var(--fnbb-danger-light);
            color: var(--fnbb-danger);
        }
        
        .status-active {
            background-color: var(--fnbb-info-light);
            color: var(--fnbb-info);
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Responsive Styles */
        @media (min-width: 992px) {
            .user-info {
                display: block;
            }
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-280px);
            }
            
            .sidebar.expanded {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topbar {
                left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .search-form {
                width: 200px;
            }
        }
        
        @media (max-width: 767.98px) {
            .content-wrapper {
                padding: 1rem;
                padding-top: 80px;
            }
            
            .search-form {
                display: none;
            }
            
            .topbar {
                padding: 0 1rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-icon {
                width: 3rem;
                height: 3rem;
                font-size: 1.25rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table th, .table td {
                padding: 0.75rem;
            }
        }
        
        /* Additional Utilities */
        .bg-primary {
            background-color: var(--fnbb-primary) !important;
        }
        
        .bg-success {
            background-color: var(--fnbb-success) !important;
        }
        
        .bg-warning {
            background-color: var(--fnbb-warning) !important;
        }
        
        .bg-danger {
            background-color: var(--fnbb-danger) !important;
        }
        
        .bg-info {
            background-color: var(--fnbb-info) !important;
        }
        
        .bg-secondary {
            background-color: var(--fnbb-secondary) !important;
        }
        
        .text-primary {
            color: var(--fnbb-primary) !important;
        }
        
        .text-success {
            color: var(--fnbb-success) !important;
        }
        
        .text-warning {
            color: var(--fnbb-warning) !important;
        }
        
        .text-danger {
            color: var(--fnbb-danger) !important;
        }
        
        .text-info {
            color: var(--fnbb-info) !important;
        }
        
        .text-secondary {
            color: var(--fnbb-secondary) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/fnbb-logo-white.png') }}" alt="FNBB Logo" class="logo">
                    <span class="brand-text">Admin Portal</span>
                </a>
                <button class="sidebar-close d-lg-none" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-section">
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h6 class="nav-section-title">User Management</h6>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span class="nav-text">All Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.officers') }}" class="nav-link {{ request()->routeIs('admin.officers*') ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i>
                                <span class="nav-text">Bank Officers</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles') }}" class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield"></i>
                                <span class="nav-text">Roles & Permissions</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h6 class="nav-section-title">Registration</h6>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('admin.registrations') }}" class="nav-link {{ request()->routeIs('admin.registrations*') || request()->routeIs('admin.customer*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="nav-text">Customer Registrations</span>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="badge bg-warning rounded-pill">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h6 class="nav-section-title">Reporting</h6>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('admin.activity') }}" class="nav-link {{ request()->routeIs('admin.activity*') || request()->routeIs('admin.logs*') ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <span class="nav-text">Activity Logs</span>
                                @if(isset($newLogsCount) && $newLogsCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $newLogsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                <span class="nav-text">Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h6 class="nav-section-title">System</h6>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                <i class="fas fa-cogs"></i>
                                <span class="nav-text">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.security') }}" class="nav-link {{ request()->routeIs('admin.security*') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                <span class="nav-text">Security Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.api') }}" class="nav-link {{ request()->routeIs('admin.api*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i>
                                <span class="nav-text">API Configuration</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        
        <div class="main-content" id="mainContent">
            <!-- Top Navigation Bar -->
            <header class="topbar" id="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <h1 class="page-title d-none d-md-block">@yield('page_title', 'Dashboard')</h1>
                    
                    <nav class="ms-3 d-none d-lg-block" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>
                
                <div class="topbar-right">
                    <form class="search-form">
                        <input type="text" class="search-input" placeholder="Search..." aria-label="Search">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <button class="topbar-icon" id="notificationsDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(isset($newNotificationsCount) && $newNotificationsCount > 0)
                        <span class="badge bg-danger">{{ $newNotificationsCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="notificationsDropdownToggle" style="width: 320px;">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                        </div>
                        <div class="notifications-list p-2" style="max-height: 300px; overflow-y: auto;">
                            @forelse($notifications ?? [] as $notification)
                            <a href="#" class="dropdown-item p-2 border-bottom">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="icon-circle icon-{{ $notification->type ?? 'primary' }} me-2">
                                            <i class="fas fa-{{ $notification->icon ?? 'bell' }} fa-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0 text-dark">{{ $notification->message ?? 'Notification message' }}</p>
                                        <small class="text-muted">{{ $notification->time ?? 'Just now' }}</small>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No new notifications</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="p-2 border-top text-center">
                            <a href="#" class="text-primary small fw-medium">View all notifications</a>
                        </div>
                    </div>
                    
                    <div class="dropdown">
                        <div class="user-dropdown" id="userDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar">
                                @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                                @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="user-info">
                                <h6 class="user-name">{{ Auth::user()->name }}</h6>
                                <p class="user-role">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdownToggle">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user"></i> My Profile
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="content-wrapper">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="app-footer">
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <div>
                            &copy; {{ date('Y') }} First National Bank of Botswana. All rights reserved.
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <a href="#" class="text-muted me-3">Privacy Policy</a>
                            <a href="#" class="text-muted">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const topbar = document.getElementById('topbar');
            const menuToggle = document.getElementById('menuToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            
            // Toggle sidebar on mobile
            function toggleSidebar() {
                sidebar.classList.toggle('expanded');
            }
            
            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarClose) {
                sidebarClose.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickInsideToggle = menuToggle && menuToggle.contains(event.target);
                
                if (window.innerWidth < 992 && !isClickInsideSidebar && !isClickInsideToggle && sidebar.classList.contains('expanded')) {
                    sidebar.classList.remove('expanded');
                }
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Initialize all tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Initialize all popovers
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
            const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        });
    </script>
    
    @stack('scripts')
</body>
</html>