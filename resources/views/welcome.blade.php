<x-guest-layout>
    <div class="container">
        <!-- Hero Section -->
        <div class="row align-items-center py-5">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-primary mb-3">Banking Registration <span class="text-dark">Made Simple</span></h1>
                <p class="lead mb-4">Register for FNBB services online with our secure identity verification system. No need to visit a branch.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-md-2">Get Started</a>
                    <a href="#how-it-works" class="btn btn-outline-secondary btn-lg px-4">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/hero-image.jpg') }}" alt="FNBB Digital Banking" class="img-fluid rounded shadow-lg">
            </div>
        </div>

        <!-- Features Section -->
        <div class="row py-5">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Why Choose InstantOn?</h2>
                <p class="lead text-muted">Our cutting-edge verification technology makes registration fast and secure</p>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                            <i class="fas fa-bolt fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold">Quick Registration</h4>
                        <p class="text-muted">Complete your registration in minutes, not days. No need to visit a branch.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                            <i class="fas fa-shield-alt fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold">Secure Verification</h4>
                        <p class="text-muted">State-of-the-art facial recognition technology ensures your identity is protected.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold">Official Integration</h4>
                        <p class="text-muted">Direct integration with Botswana National ID (Omang) verification system.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div id="how-it-works" class="py-5">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">How It Works</h2>
                    <p class="lead text-muted">Our simple 4-step process gets you registered quickly and securely</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span class="text-white fw-bold">1</span>
                            </div>
                            <h5 class="fw-bold">Create Account</h5>
                            <p class="text-muted small">Sign up with your email and create a secure password</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span class="text-white fw-bold">2</span>
                            </div>
                            <h5 class="fw-bold">Verify Omang</h5>
                            <p class="text-muted small">Enter your Botswana National ID details for verification</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span class="text-white fw-bold">3</span>
                            </div>
                            <h5 class="fw-bold">Facial Verification</h5>
                            <p class="text-muted small">Use your camera for a quick facial comparison with your ID photo</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span class="text-white fw-bold">4</span>
                            </div>
                            <h5 class="fw-bold">Complete Profile</h5>
                            <p class="text-muted small">Provide additional details and submit for bank officer review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="py-5 bg-light rounded-3 my-5">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">What Our Customers Say</h2>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="mb-3">"I was amazed at how quick and easy the registration process was. The facial verification was seamless, and I didn't have to visit a branch at all!"</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    KM
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Kagiso Moitse</h6>
                                    <small class="text-muted">Gaborone</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="mb-3">"As someone who lives far from a branch, InstantOn was a game-changer. I completed my registration from home, and the process was secure and straightforward."</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    TN
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Tebogo Nkwe</h6>
                                    <small class="text-muted">Francistown</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row py-5">
            <div class="col-12">
                <div class="bg-primary text-white p-5 rounded-3 text-center">
                    <h2 class="fw-bold mb-3">Ready to get started?</h2>
                    <p class="lead mb-4">Join thousands of Batswana who have registered for FNBB services online</p>
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>