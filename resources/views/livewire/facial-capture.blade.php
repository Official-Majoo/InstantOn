<!-- resources/views/livewire/facial-capture.blade.php -->
<div>
    @if ($verificationPassed)
        <!-- Verification Successful -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="verification-result shadow">
                    <div class="text-center mb-4">
                        <div class="verification-icon success mx-auto">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h2 class="h4 mt-3">Verification Successful!</h2>
                        <p class="text-muted">Your identity has been successfully verified.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <h3 class="h6 mb-3">Your Selfie</h3>
                            <img src="{{ $capturedImage }}" alt="Your Selfie" class="img-fluid rounded shadow-sm"
                                style="max-height: 200px;">
                        </div>
                        <div class="col-md-6 text-center">
                            <h3 class="h6 mb-3">Verification Score</h3>
                            <div
                                class="verification-score {{ $similarityScore >= 90 ? 'text-success' : ($similarityScore >= 70 ? 'text-primary' : 'text-warning') }}">
                                {{ number_format($similarityScore, 1) }}%
                            </div>
                            <div class="verification-status success">
                                <i class="fas fa-check-circle me-1"></i> Verified
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 col-md-8 mx-auto mt-4">
                        <a href="{{ route('verification.additional') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right me-2"></i> Continue to Next Step
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($processingStatus === 'error')
        <!-- Verification Error -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="verification-result shadow">
                    <div class="text-center mb-4">
                        <div class="verification-icon error mx-auto">
                            <i class="fas fa-exclamation-triangle fa-3x"></i>
                        </div>
                        <h2 class="h4 mt-3">Verification Error</h2>
                        <p class="text-danger">
                            @error('verification')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="d-grid gap-2 col-md-8 mx-auto mt-4">
                        <button type="button" class="btn btn-primary" wire:click="retry">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($processingStatus === 'processing')
        <!-- Processing Verification -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="verification-result shadow">
                    <div class="text-center mb-4">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h2 class="h4 mt-3">Processing Verification</h2>
                        <p class="text-muted">Please wait while we verify your identity...</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($processingStatus === 'completed' && !$verificationPassed)
        <!-- Verification Failed -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="verification-result shadow">
                    <div class="text-center mb-4">
                        <div class="verification-icon error mx-auto">
                            <i class="fas fa-times-circle fa-3x"></i>
                        </div>
                        <h2 class="h4 mt-3">Verification Failed</h2>
                        <p class="text-muted">The similarity score is below the required threshold.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <h3 class="h6 mb-3">Your Selfie</h3>
                            <img src="{{ $capturedImage }}" alt="Your Selfie" class="img-fluid rounded shadow-sm"
                                style="max-height: 200px;">
                        </div>
                        <div class="col-md-6 text-center">
                            <h3 class="h6 mb-3">Verification Score</h3>
                            <div class="verification-score text-danger">
                                {{ number_format($similarityScore, 1) }}%
                            </div>
                            <div class="verification-status failure">
                                <i class="fas fa-times-circle me-1"></i> Failed
                            </div>
                            <p class="text-muted small mt-2">Minimum required: 70%</p>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <h3 class="h6">Tips for a successful verification:</h3>
                        <ul class="mb-0 small">
                            <li>Ensure you're in a well-lit area</li>
                            <li>Remove glasses, hats, or face coverings</li>
                            <li>Look directly at the camera</li>
                            <li>Keep your face centered in the frame</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 col-md-8 mx-auto mt-4">
                        <button type="button" class="btn btn-primary" wire:click="retry">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($capturedImage)
        <!-- Review Captured Image -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-4 text-center">Review Your Selfie</h2>

                        <div class="text-center mb-4">
                            <img src="{{ $capturedImage }}" alt="Captured Selfie" class="img-fluid rounded shadow-sm"
                                style="max-height: 300px;">
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary" wire:click="retry">
                                <i class="fas fa-redo me-2"></i> Retake
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="processVerification">
                                <i class="fas fa-check-circle me-2"></i> Verify Identity
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($isCapturing)
        <!-- Camera Capture View -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-4 text-center">Take a Selfie</h2>

                        <div class="camera-container mb-4">
                            <!-- Camera status message will be added here by JavaScript -->
                            <video id="camera-feed" class="camera-feed w-100 rounded" autoplay playsinline></video>
                            <div id="capture-overlay"
                                class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="face-outline"></div>
                            </div>
                        </div>

                        <div class="camera-controls text-center">
                            <button type="button" class="btn btn-primary" id="capture-btn" disabled>
                                <i class="fas fa-camera me-2"></i> Capture Photo
                            </button>
                        </div>

                        <div class="alert alert-info mt-4 small">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tips:</strong> Center your face in the circle, ensure good lighting, and look
                            directly at the camera.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Initial State -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-camera fa-4x text-primary mb-4"></i>
                        <h2 class="h5 mb-3">Facial Verification</h2>
                        <p class="text-muted mb-4">We'll use your device's camera to verify your identity by comparing
                            your selfie with your Omang photo.</p>

                        <div class="d-grid gap-2 col-md-6 mx-auto">
                            <button type="button" class="btn btn-primary btn-lg" id="start-capture-btn"
                                wire:click="startCapture">
                                <i class="fas fa-camera me-2"></i> Start Verification
                            </button>
                        </div>

                        <p class="text-muted small mt-4 mb-0">Please ensure you're in a well-lit area and have your
                            face clearly visible.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Camera init script loaded - Livewire 3 compatible version');

            // Function to initialize camera
            function initializeCamera() {
                console.log('Initializing camera...');
                const video = document.getElementById('camera-feed');
                const captureBtn = document.getElementById('capture-btn');

                if (!video || !captureBtn) {
                    console.error('Required camera elements not found');
                    return;
                }

                // Set up capture overlay
                const captureOverlay = document.getElementById('capture-overlay');
                if (captureOverlay) {
                    captureOverlay.style.display = 'flex';
                }

                // Stop any existing stream
                if (video.srcObject) {
                    const tracks = video.srcObject.getTracks();
                    tracks.forEach(track => track.stop());
                    video.srcObject = null;
                }

                // Request camera access
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    console.log('Requesting camera...');
                    navigator.mediaDevices.getUserMedia({
                            video: true,
                            audio: false
                        })
                        .then(function(stream) {
                            console.log('Camera access granted');
                            video.srcObject = stream;
                            video.play()
                                .then(function() {
                                    console.log('Video playing');
                                    captureBtn.disabled = false;
                                })
                                .catch(function(err) {
                                    console.error('Error playing video:', err);
                                });
                        })
                        .catch(function(err) {
                            console.error('Camera access error:', err.name, err.message);
                            alert('Camera access error: ' + err.message);
                        });
                } else {
                    console.error('getUserMedia not supported');
                    alert('Your browser does not support camera access');
                }
            }

            // Listen for the start-capture-btn click
            document.addEventListener('click', function(e) {
                const startBtn = e.target.closest('#start-capture-btn');
                if (startBtn) {
                    console.log('Start capture button clicked');
                    // Let Livewire handle the state change before initializing the camera
                    setTimeout(initializeCamera, 500);
                }
            });

            // Listen for retry button clicks
            document.addEventListener('click', function(e) {
                const retryBtn = e.target.closest('button[wire\\:click="retry"]');
                if (retryBtn) {
                    console.log('Retry button clicked - will initialize camera after Livewire update');
                    // Wait for Livewire to update the DOM before initializing camera
                    setTimeout(() => {
                        console.log('Delayed camera initialization after retry');
                        // Check if we're in capturing mode after the update
                        const component = Livewire.first();
                        if (component && component.get('isCapturing')) {
                            initializeCamera();
                        }
                    }, 500); // 500ms delay to let Livewire update the DOM
                }
            });

            // Listen for both Livewire 2 and Livewire 3 events

            // For Livewire 3 dispatch events
            document.addEventListener('retry-capture', event => {
                console.log('Livewire 3 retry-capture event received', event.detail);
                setTimeout(() => {
                    console.log('Initializing camera after retry event');
                    initializeCamera();
                }, 300);
            });

            document.addEventListener('start-camera', event => {
                console.log('Livewire 3 start-camera event received');
                setTimeout(initializeCamera, 300);
            });

            // For Livewire 2 browser events
            window.addEventListener('retry-capture', event => {
                console.log('Livewire 2 retry-capture event received', event.detail);
                setTimeout(() => {
                    console.log('Initializing camera after retry event');
                    initializeCamera();
                }, 300);
            });

            window.addEventListener('start-camera', event => {
                console.log('Livewire 2 start-camera event received');
                setTimeout(initializeCamera, 300);
            });

            // For Livewire emit events
            if (typeof window.Livewire !== 'undefined') {
                // For Livewire 2
                if (window.Livewire.on) {
                    window.Livewire.on('retry-capture', (data) => {
                        console.log('Livewire emit retry-capture event received', data);
                        setTimeout(initializeCamera, 300);
                    });

                    window.Livewire.on('start-camera', () => {
                        console.log('Livewire emit start-camera event received');
                        setTimeout(initializeCamera, 300);
                    });
                }
            }

            // Listen for the capture-btn click
            document.addEventListener('click', function(e) {
                const captureBtn = e.target.closest('#capture-btn');
                if (captureBtn) {
                    console.log('Capture button clicked');
                    capturePhoto();
                }
            });

            // Handle Livewire updates
            document.addEventListener('livewire:initialized', function() {
                // Check if already in capturing mode
                const component = Livewire.first();
                if (component && component.get('isCapturing')) {
                    console.log('Already in capturing mode');
                    setTimeout(initializeCamera, 500);
                }

                // Listen for Livewire updates
                Livewire.hook('message.processed', (message, component) => {
                    const isCapturing = component.get('isCapturing');
                    console.log('Livewire message processed, isCapturing:', isCapturing);

                    if (isCapturing) {
                        console.log('Capturing mode detected in hook - initializing camera');
                        setTimeout(initializeCamera, 300);
                    }
                });
            });

            // Capture photo function
            function capturePhoto() {
                console.log('Capturing photo');
                const video = document.getElementById('camera-feed');

                if (!video || !video.srcObject) {
                    console.error('Camera not initialized or no stream available');
                    return;
                }

                try {
                    // Create canvas and draw video frame
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Get image data - PROPERLY FORMATTED for Intervention Image
                    const imageData = canvas.toDataURL('image/jpeg', 0.9);

                    // Stop camera stream
                    const tracks = video.srcObject.getTracks();
                    tracks.forEach(track => track.stop());

                    // Send to Livewire - using Livewire 3 API
                    console.log('Sending image to Livewire component');
                    const component = Livewire.first();
                    if (component) {
                        // Set the property directly
                        component.set('capturedImage', imageData);

                        // Call the capture method directly
                        component.capture(imageData);
                    } else {
                        console.error('Could not find Livewire component');
                    }
                } catch (error) {
                    console.error('Error capturing photo:', error);
                    alert('Error capturing photo: ' + error.message);
                }
            }

            // Check if we need to initialize the camera on page load
            setTimeout(function() {
                const component = Livewire.first();
                if (component && component.get('isCapturing')) {
                    console.log('Initializing camera on page load');
                    initializeCamera();
                }
            }, 500);
        });
    </script>
@endpush
