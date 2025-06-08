<!-- Camera Verification Component for Step 4 -->
<div class="step-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Facial Verification</h3>
    
    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Errors:</strong>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
        <p class="text-sm text-blue-800">
            <strong>Important:</strong> Your face will be compared with your Omang photo for verification. Please ensure you are in a well-lit environment and looking directly at the camera.
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h4 class="text-md font-medium mb-2">ID Photo Reference</h4>
            <div class="bg-gray-100 rounded-lg p-2 h-64 flex items-center justify-center">
                <!-- This would show the photo from the Omang API -->
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">ID Photo from Omang</p>
                </div>
            </div>
        </div>
        
        <div>
            <h4 class="text-md font-medium mb-2">Camera Capture</h4>
            <div class="camera-container">
                <div id="camera-feed" class="camera-placeholder">
                    <div class="text-center" id="camera-placeholder-text">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Click below to activate camera</p>
                    </div>
                    <video id="video-stream" autoplay playsinline style="display: none; width: 100%; height: 100%; object-fit: cover;"></video>
                    <canvas id="capture-canvas" style="display: none;"></canvas>
                </div>
                
                <div class="camera-controls">
                    <button type="button" id="start-camera" class="btn btn-primary">
                        Start Camera
                    </button>
                    <button type="button" id="capture-photo" class="btn btn-secondary" style="display: none;">
                        Capture Photo
                    </button>
                    <button type="button" id="retake-photo" class="btn btn-outline" style="display: none;">
                        Retake
                    </button>
                </div>
                
                <input type="hidden" id="captured-photo" wire:model.defer="facial_capture">
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <div class="flex items-center justify-center">
            <span id="verification-status" class="py-2 px-4 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                Ready for verification
            </span>
        </div>
    </div>
    
    <!-- JavaScript for camera functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startButton = document.getElementById('start-camera');
            const captureButton = document.getElementById('capture-photo');
            const retakeButton = document.getElementById('retake-photo');
            const videoElement = document.getElementById('video-stream');
            const cameraPlaceholder = document.getElementById('camera-placeholder-text');
            const canvas = document.getElementById('capture-canvas');
            const capturedPhotoInput = document.getElementById('captured-photo');
            const verificationStatus = document.getElementById('verification-status');
            
            let stream = null;
            
            startButton.addEventListener('click', async function() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        } 
                    });
                    
                    videoElement.srcObject = stream;
                    videoElement.style.display = 'block';
                    cameraPlaceholder.style.display = 'none';
                    startButton.style.display = 'none';
                    captureButton.style.display = 'inline-block';
                } catch (err) {
                    console.error('Error accessing camera:', err);
                    alert('Unable to access camera. Please make sure your camera is connected and you have given permission to use it.');
                }
            });
            
            captureButton.addEventListener('click', function() {
                // Set canvas dimensions to match video
                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;
                
                // Draw the current video frame to the canvas
                const ctx = canvas.getContext('2d');
                ctx.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                
                // Convert canvas to base64 image
                const photoData = canvas.toDataURL('image/jpeg');
                capturedPhotoInput.value = photoData;
                
                // Stop the camera stream
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                
                // Show the captured image
                videoElement.style.display = 'none';
                canvas.style.display = 'block';
                captureButton.style.display = 'none';
                retakeButton.style.display = 'inline-block';
                
                // Simulate verification process
                verificationStatus.textContent = 'Verifying...';
                verificationStatus.classList.remove('bg-gray-200', 'text-gray-800');
                verificationStatus.classList.add('bg-blue-200', 'text-blue-800');
                
                // Trigger the Livewire event to notify that capture is complete
                Livewire.emit('photoCapture', photoData);
                
                // Simulate verification process (would be handled by the server in production)
                setTimeout(() => {
                    verificationStatus.textContent = 'Verification Successful';
                    verificationStatus.classList.remove('bg-blue-200', 'text-blue-800');
                    verificationStatus.classList.add('bg-green-200', 'text-green-800');
                }, 2000);
            });
            
            retakeButton.addEventListener('click', async function() {
                // Reset UI
                canvas.style.display = 'none';
                retakeButton.style.display = 'none';
                
                // Restart camera
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        } 
                    });
                    
                    videoElement.srcObject = stream;
                    videoElement.style.display = 'block';
                    captureButton.style.display = 'inline-block';
                    
                    // Reset verification status
                    verificationStatus.textContent = 'Ready for verification';
                    verificationStatus.classList.remove('bg-green-200', 'text-green-800', 'bg-blue-200', 'text-blue-800');
                    verificationStatus.classList.add('bg-gray-200', 'text-gray-800');
                } catch (err) {
                    console.error('Error restarting camera:', err);
                }
            });
        });
    </script>
</div>