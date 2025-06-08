<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fnbb:create-test-users {--force : Force creation even in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users for development and testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if we're in production
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('This command should not be run in production without the --force flag.');
            return 1;
        }

        $this->info('Creating test users...');

        // Create roles if they don't exist
        $this->createRoles();

        // Create test users
        $this->createAdminUser();
        $this->createBankOfficerUser();
        $this->createCustomerUsers();

        $this->info('Test users created successfully!');
        $this->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@example.com', 'Password123!'],
                ['Bank Officer', 'officer@example.com', 'Password123!'],
                ['Customer', 'customer@example.com', 'Password123!'],
                ['Customer (Verified)', 'verified@example.com', 'Password123!'],
                ['Customer (Rejected)', 'rejected@example.com', 'Password123!'],
            ]
        );

        return 0;
    }

    /**
     * Create roles and permissions
     */
    private function createRoles()
    {
        $this->info('Setting up roles and permissions...');

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $officerRole = Role::firstOrCreate(['name' => 'bank_officer']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create permissions
        $permissions = [
            'view_dashboard',
            'manage_users',
            'review_registrations',
            'view_reports',
            'approve_customers',
            'reject_customers',
            'request_additional_info',
            'register_account',
            'upload_documents',
            'update_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::all());
        
        $officerRole->syncPermissions([
            'view_dashboard',
            'review_registrations',
            'view_reports',
            'approve_customers',
            'reject_customers',
            'request_additional_info',
        ]);
        
        $customerRole->syncPermissions([
            'register_account',
            'upload_documents',
            'update_profile',
        ]);

        $this->info('Roles and permissions created successfully.');
    }

    /**
     * Create admin user
     */
    private function createAdminUser()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $admin->assignRole('admin');
        
        $this->info('Admin user created: admin@example.com');
    }

    /**
     * Create bank officer user
     */
    private function createBankOfficerUser()
    {
        $officer = User::firstOrCreate(
            ['email' => 'officer@example.com'],
            [
                'name' => 'Bank Officer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'bank_officer',
                'status' => 'active',
            ]
        );

        $officer->assignRole('bank_officer');
        
        $this->info('Bank officer created: officer@example.com');
    }

    /**
     * Create test customer users
     */
    private function createCustomerUsers()
    {
        // Regular customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'phone_number' => '26771234567',
                'role' => 'customer',
                'status' => 'pending',
            ]
        );

        $customer->assignRole('customer');

        // Create customer profile
        if (!$customer->customerProfile) {
            CustomerProfile::create([
                'user_id' => $customer->id,
                'omang_number' => '123456789',
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'verification_status' => 'pending',
            ]);
        }
        
        $this->info('Customer created: customer@example.com');

        // Verified customer
        $verifiedCustomer = User::firstOrCreate(
            ['email' => 'verified@example.com'],
            [
                'name' => 'Verified Customer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'phone_number' => '26772345678',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        $verifiedCustomer->assignRole('customer');

        // Create verified customer profile
        if (!$verifiedCustomer->customerProfile) {
            CustomerProfile::create([
                'user_id' => $verifiedCustomer->id,
                'omang_number' => '987654321',
                'first_name' => 'Verified',
                'last_name' => 'Customer',
                'date_of_birth' => '1985-05-15',
                'gender' => 'female',
                'nationality' => 'Botswana',
                'address' => '123 Main Street, Gaborone',
                'postal_code' => '00000',
                'city' => 'Gaborone',
                'district' => 'South-East',
                'occupation' => 'Software Engineer',
                'employer' => 'Tech Company',
                'income_range' => '25001_to_50000',
                'verification_status' => 'verified',
            ]);
        }
        
        $this->info('Verified customer created: verified@example.com');

        // Rejected customer
        $rejectedCustomer = User::firstOrCreate(
            ['email' => 'rejected@example.com'],
            [
                'name' => 'Rejected Customer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'phone_number' => '26773456789',
                'role' => 'customer',
                'status' => 'rejected',
            ]
        );

        $rejectedCustomer->assignRole('customer');

        // Create rejected customer profile
        if (!$rejectedCustomer->customerProfile) {
            CustomerProfile::create([
                'user_id' => $rejectedCustomer->id,
                'omang_number' => '555555555',
                'first_name' => 'Rejected',
                'last_name' => 'Customer',
                'date_of_birth' => '1978-10-22',
                'gender' => 'male',
                'verification_status' => 'rejected',
                'rejection_reason' => 'Identity verification failed. The provided Omang information could not be verified.',
            ]);
        }
        
        $this->info('Rejected customer created: rejected@example.com');
    }
}