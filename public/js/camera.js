document.addEventListener('DOMContentLoaded', function() {
    // Debug flag - set to true for detailed console logging
    const DEBUG = true;
    
    function debugLog(...args) {
        if (DEBUG) {
            console.log(...args);
        }
    }
    
    // Log browser capabilities
    debugLog('Browser capabilities check:');
    debugLog('navigator.mediaDevices available:', !!navigator.mediaDevices);
    debugLog('getUserMedia available:', !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia));
    
    // Track camera initialization state to prevent duplicate initializations
    let cameraInitializing = false;
    let cameraInitialized = false;
    
    // Handle Start Verification button click
    document.addEventListener('click', function(event) {
        const startCaptureBtn = event.target.closest('#start-capture-btn');
        if (startCaptureBtn) {
            debugLog('Start capture button clicked');
            const livewireEl = document.querySelector('[wire\\:id]');
            if (livewireEl) {
                try {
                    const component = Livewire.find(livewireEl.getAttribute('wire:id'));
                    component.startCapture();
                    debugLog('Livewire startCapture() method called');
                } catch (error) {
                    console.error('LIVEWIRE ERROR: Could not call startCapture method', error);
                }
            }
        }
    });
    
    // Main camera initialization function
    function initializeCamera() {
        // Prevent multiple simultaneous initialization attempts
        if (cameraInitializing) {
            debugLog('CAMERA: Already initializing, skipping duplicate call');
            return;
        }
        
        cameraInitializing = true;
        debugLog('CAMERA INIT: Starting camera initialization...');
        
        // Check if video element exists, if not, wait for it to be created
        const video = document.getElementById('camera-feed');
        if (!video) {
            debugLog('CAMERA: Video element not found, waiting for DOM update');
            cameraInitializing = false;
            setTimeout(checkForCameraElements, 300);
            return;
        }
        
        const captureBtn = document.getElementById('capture-btn');
        if (!captureBtn) {
            debugLog('CAMERA: Capture button not found, waiting for DOM update');
            cameraInitializing = false;
            setTimeout(checkForCameraElements, 300);
            return;
        }
        
        // Create or update status message
        let statusDiv = document.getElementById('camera-status-message');
        if (!statusDiv) {
            statusDiv = document.createElement('div');
            statusDiv.id = 'camera-status-message';
            statusDiv.className = 'alert alert-info mt-2';
            video.parentNode.insertBefore(statusDiv, video.nextSibling);
        }
        statusDiv.textContent = 'Requesting camera access...';
        
        // Make sure video element is visible and properly styled
        video.style.display = 'block';
        video.style.width = '100%';
        video.style.background = '#000';
        video.style.minHeight = '240px';
        
        // Add a message overlay to the video element
        const messageOverlay = document.createElement('div');
        messageOverlay.id = 'camera-message-overlay';
        messageOverlay.style.position = 'absolute';
        messageOverlay.style.top = '50%';
        messageOverlay.style.left = '50%';
        messageOverlay.style.transform = 'translate(-50%, -50%)';
        messageOverlay.style.color = 'white';
        messageOverlay.style.background = 'rgba(0,0,0,0.5)';
        messageOverlay.style.padding = '10px';
        messageOverlay.style.borderRadius = '5px';
        messageOverlay.style.zIndex = '1000';
        messageOverlay.textContent = 'Initializing camera...';
        
        if (video.parentNode.style.position !== 'relative') {
            video.parentNode.style.position = 'relative';
        }
        video.parentNode.appendChild(messageOverlay);
        
        // Clear any existing stream
        if (video.srcObject) {
            debugLog('CAMERA: Clearing existing stream');
            const tracks = video.srcObject.getTracks();
            tracks.forEach(track => track.stop());
            video.srcObject = null;
        }
        
        // Try accessing camera with fallbacks
        tryGetUserMedia();
        
        // Main getUserMedia attempt with fallbacks
        function tryGetUserMedia() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                console.error('CAMERA ERROR: getUserMedia not supported');
                showError('Your browser does not support camera access. Please try a different browser.');
                cameraInitializing = false;
                return;
            }
            
            // Show a permission prompt to the user
            const messageOverlay = document.getElementById('camera-message-overlay');
            if (messageOverlay) {
                messageOverlay.innerHTML = 'Please allow camera access when prompted by your browser.<br>If no prompt appears, check your browser settings.';
            }
            
            console.log('ATTEMPTING CAMERA ACCESS - Check for browser permission prompts!');
            
            // First try with minimal constraints to maximize compatibility
            debugLog('CAMERA: Trying simple constraints first');
            navigator.mediaDevices.getUserMedia({ 
                video: true, 
                audio: false 
            })
            .then(handleSuccess)
            .catch(function(error) {
                console.error('CAMERA ERROR: Simple constraints failed', error);
                
                // Show the error to help with debugging
                if (messageOverlay) {
                    messageOverlay.innerHTML = `Camera error: ${error.name}<br>${error.message}`;
                }
                
                // If it's a permission error, provide clear guidance
                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    showError('Camera access denied. Please allow camera access in your browser settings and reload the page.');
                    // Try to detect if we're in incognito/private mode
                    checkIncognitoMode();
                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                    showError('No camera detected on your device. Please connect a camera and reload the page.');
                } else {
                    // Try with specific device IDs as last resort
                    trySpecificDevices();
                }
            });
        }
        
        // Try with specific device IDs
        function trySpecificDevices() {
            debugLog('CAMERA: Trying to enumerate devices');
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    debugLog('CAMERA: Available video devices:', videoDevices);
                    
                    if (videoDevices.length === 0) {
                        showError('No camera detected. Please check your device.');
                        cameraInitializing = false;
                        return;
                    }
                    
                    // Try each video device until one works
                    tryNextDevice(videoDevices, 0);
                })
                .catch(function(error) {
                    console.error('CAMERA ERROR: Could not enumerate devices', error);
                    showError('Failed to access camera. Please check permissions and try again.');
                    cameraInitializing = false;
                });
        }
        
        // Try devices one by one
        function tryNextDevice(devices, index) {
            if (index >= devices.length) {
                showError('Could not access any camera. Please check permissions and try again.');
                cameraInitializing = false;
                return;
            }
            
            debugLog(`CAMERA: Trying device ${index + 1} of ${devices.length}`);
            navigator.mediaDevices.getUserMedia({
                video: { deviceId: { exact: devices[index].deviceId } },
                audio: false
            })
            .then(handleSuccess)
            .catch(function(error) {
                debugLog(`CAMERA: Device ${index + 1} failed, trying next`, error);
                tryNextDevice(devices, index + 1);
            });
        }
        
        // Handle successful camera access
        function handleSuccess(stream) {
            debugLog('CAMERA SUCCESS: Camera accessed successfully');
            
            // Remove the message overlay
            const messageOverlay = document.getElementById('camera-message-overlay');
            if (messageOverlay) {
                messageOverlay.remove();
            }
            
            // Explicitly set the srcObject
            try {
                video.srcObject = stream;
            } catch (error) {
                // Fallback for older browsers
                console.warn('Error setting srcObject, trying URL.createObjectURL', error);
                try {
                    video.src = window.URL.createObjectURL(stream);
                } catch (error2) {
                    console.error('Both srcObject and createObjectURL failed', error2);
                    showError('Your browser cannot display the camera feed. Please try a different browser.');
                    cameraInitializing = false;
                    return;
                }
            }
            
            // Force the video to be visible
            video.style.display = 'block';
            
            // Sometimes video.play() can throw errors
            try {
                // Play might return a promise
                const playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise
                        .then(() => {
                            debugLog('CAMERA: Video playing');
                            captureBtn.disabled = false;
                            statusDiv.className = 'alert alert-success mt-2';
                            statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> Camera ready. Position your face in the circle and click "Capture Photo".';
                            cameraInitialized = true;
                            cameraInitializing = false;
                        })
                        .catch(error => {
                            console.error('CAMERA ERROR: Video play failed', error);
                            showError('Video play failed. Please reload the page and try again.');
                            cameraInitializing = false;
                        });
                } else {
                    debugLog('CAMERA: Video play returned undefined');
                    captureBtn.disabled = false;
                    statusDiv.className = 'alert alert-success mt-2';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> Camera ready. Position your face in the circle and click "Capture Photo".';
                    cameraInitialized = true;
                    cameraInitializing = false;
                }
            } catch (error) {
                console.error('CAMERA ERROR: Exception during play()', error);
                showError('Video play failed. Please reload the page and try again.');
                cameraInitializing = false;
            }
            
            // Set up capture button - using addEventListener only if it doesn't already have the listener
            if (!captureBtn.hasClickListener) {
                captureBtn.addEventListener('click', capturePhoto);
                captureBtn.hasClickListener = true;
            }
        }
        
        // Capture photo function
        function capturePhoto() {
            debugLog('CAMERA: Capturing photo');
            try {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                // Set canvas dimensions to match video
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                
                // Draw the current video frame to the canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Convert canvas to base64 image
                const imageData = canvas.toDataURL('image/jpeg', 0.9);
                
                // Stop all video tracks to release camera
                if (video.srcObject) {
                    const tracks = video.srcObject.getTracks();
                    tracks.forEach(track => track.stop());
                    video.srcObject = null;
                }
                
                // Log success
                debugLog('CAMERA: Photo captured successfully');
                
                // Send the captured image to the Livewire component
                const livewireEl = document.querySelector('[wire\\:id]');
                if (livewireEl) {
                    try {
                        const component = Livewire.find(livewireEl.getAttribute('wire:id'));
                        component.set('capturedImage', imageData);
                        component.capture(imageData);
                        debugLog('LIVEWIRE: capture method called with image data');
                    } catch (error) {
                        console.error('LIVEWIRE ERROR: Could not call capture method', error);
                        showError('Error sending image to server. Please try again.');
                    }
                } else {
                    console.error('LIVEWIRE ERROR: Component element not found');
                    showError('Application error. Please reload the page.');
                }
            } catch (error) {
                console.error('CAMERA ERROR: Error capturing photo', error);
                showError('Error capturing photo. Please try again.');
            }
        }
        
        // Show error message
        function showError(message) {
            if (statusDiv) {
                statusDiv.className = 'alert alert-danger mt-2';
                statusDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
            }
            
            // Update the message overlay if it exists
            const messageOverlay = document.getElementById('camera-message-overlay');
            if (messageOverlay) {
                messageOverlay.style.background = 'rgba(220, 53, 69, 0.8)';
                messageOverlay.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
            }
            
            // Create troubleshooting section
            let troubleshootingDiv = document.getElementById('camera-troubleshooting');
            if (!troubleshootingDiv) {
                troubleshootingDiv = document.createElement('div');
                troubleshootingDiv.id = 'camera-troubleshooting';
                troubleshootingDiv.className = 'mt-3 small';
                if (video) {
                    video.parentNode.insertBefore(troubleshootingDiv, statusDiv.nextSibling);
                }
            }
            
            troubleshootingDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h5>Camera Troubleshooting</h5>
                    <ol>
                        <li>Make sure you've allowed camera access in your browser permissions</li>
                        <li>Check that no other application is using your camera</li>
                        <li>Try using Chrome or Firefox for best compatibility</li>
                        <li>Try reloading the page</li>
                        <li>On mobile, ensure you're using https:// and not http://</li>
                        <li>If using private/incognito browsing, try regular browsing mode</li>
                        <li>Try checking your camera settings in your device settings</li>
                    </ol>
                    <button id="retry-camera-btn" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-redo"></i> Retry Camera Access
                    </button>
                </div>
            `;
            
            // Add retry button handler
            setTimeout(() => {
                const retryBtn = document.getElementById('retry-camera-btn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', function() {
                        cameraInitialized = false;
                        cameraInitializing = false;
                        checkForCameraElements();
                    });
                }
            }, 100);
        }
        
        // Check if browser is in incognito/private mode
        function checkIncognitoMode() {
            // Try to detect if running in incognito/private mode
            const fs = window.RequestFileSystem || window.webkitRequestFileSystem;
            if (!fs) return;
            
            fs(window.TEMPORARY, 100, 
                function() {
                    // Not in incognito mode
                }, 
                function() {
                    // In incognito mode - show specific message
                    const troubleshootingDiv = document.getElementById('camera-troubleshooting');
                    if (troubleshootingDiv) {
                        const incognitoWarning = document.createElement('div');
                        incognitoWarning.className = 'alert alert-warning mt-2';
                        incognitoWarning.innerHTML = `
                            <strong>Private/Incognito Mode Detected:</strong> 
                            Browser privacy settings in private/incognito mode may block camera access.
                            Please try using normal browsing mode.
                        `;
                        troubleshootingDiv.prepend(incognitoWarning);
                    }
                }
            );
        }
    }
    
    // Check for necessary DOM elements before initializing camera
    function checkForCameraElements() {
        const video = document.getElementById('camera-feed');
        const captureBtn = document.getElementById('capture-btn');
        
        if (video && captureBtn) {
            debugLog('CAMERA: Required elements found, initializing camera');
            initializeCamera();
        } else {
            debugLog('CAMERA: Required elements not yet in DOM, will check again');
            setTimeout(checkForCameraElements, 300);
        }
    }
    
    // Multiple ways to detect when camera should be initialized
    
    // 1. Listen for Livewire property changes - most reliable method
    window.addEventListener('livewire:load', function() {
        Livewire.on('propertyUpdated', function(name, value) {
            debugLog(`Livewire property updated: ${name} = ${value}`);
            if (name === 'isCapturing' && value === true && !cameraInitialized) {
                debugLog('propertyUpdated event: isCapturing set to true');
                setTimeout(checkForCameraElements, 300);
            }
        });
        
        // Also listen for verification events
        Livewire.on('verificationPassed', function(sessionId) {
            debugLog('Verification passed event received', sessionId);
        });
        
        Livewire.on('verificationFailed', function(score) {
            debugLog('Verification failed event received', score);
        });
    });
    
    // 2. Use Livewire hook for message processing
    Livewire.hook('message.processed', (message, component) => {
        debugLog('Livewire message processed:', message.response.effects.html !== undefined);
        
        // Only check when there's an HTML update
        if (message.response.effects.html !== undefined) {
            try {
                if (component.data.isCapturing && !cameraInitialized) {
                    debugLog('Livewire hook: isCapturing is true, checking for camera elements');
                    setTimeout(checkForCameraElements, 300);
                }
            } catch (e) {
                debugLog('Error checking component data:', e);
            }
        }
    });
    
    // 3. Direct DOM mutation observer as a fallback
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length) {
                // Check if camera feed element was added
                const videoElement = document.getElementById('camera-feed');
                if (videoElement && !cameraInitialized && !cameraInitializing) {
                    const livewireEl = document.querySelector('[wire\\:id]');
                    if (livewireEl) {
                        try {
                            const component = Livewire.find(livewireEl.getAttribute('wire:id'));
                            if (component && component.data.isCapturing) {
                                debugLog('DOM Mutation: Camera feed element added and isCapturing is true');
                                setTimeout(checkForCameraElements, 300);
                            }
                        } catch (e) {
                            debugLog('Error checking component data in mutation observer:', e);
                        }
                    }
                }
            }
        });
    });
    
    // Start observing the document body for DOM changes
    observer.observe(document.body, { childList: true, subtree: true });
    
    // 4. Check on page load
    const livewireEl = document.querySelector('[wire\\:id]');
    if (livewireEl) {
        try {
            const component = Livewire.find(livewireEl.getAttribute('wire:id'));
            if (component && component.data.isCapturing) {
                debugLog('Page load: already in capturing mode, checking for camera elements');
                setTimeout(checkForCameraElements, 500);
            }
        } catch (error) {
            console.error('Error accessing Livewire component on load:', error);
        }
    }
});