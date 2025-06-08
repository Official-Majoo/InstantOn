/**
 * Facial Verification JavaScript Component
 * 
 * This script handles the camera access, capturing images, and 
 * sending them to the server for verification.
 */

// DOM Elements
const videoElement = document.getElementById('video');
const captureButton = document.getElementById('capture-button');
const retakeButton = document.getElementById('retake-button');
const verifyButton = document.getElementById('verify-button');
const cameraView = document.getElementById('camera-view');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const resultContainer = document.getElementById('result-container');
const loadingContainer = document.getElementById('loading-container');
const errorContainer = document.getElementById('error-container');
const errorMessage = document.getElementById('error-message');
const selfieImage = document.getElementById('selfie-image');
const omangImage = document.getElementById('omang-image');
const similarityScore = document.getElementById('similarity-score');
const scoreProgressBar = document.getElementById('score-progress-bar');
const verificationResult = document.getElementById('verification-result');
const verificationMessage = document.getElementById('verification-message');
const nextButton = document.getElementById('next-button');

// Global variables
let stream = null;
let capturedImageData = null;

/**
 * Initialize the facial verification component
 */
function initFacialVerification() {
    // Setup event listeners
    captureButton.addEventListener('click', captureImage);
    retakeButton.addEventListener('click', startCamera);
    verifyButton.addEventListener('click', verifyImage);
    
    // Start the camera
    startCamera();
}

/**
 * Start the device camera
 */
async function startCamera() {
    try {
        // Hide other containers and show camera view
        previewContainer.classList.add('hidden');
        resultContainer.classList.add('hidden');
        errorContainer.classList.add('hidden');
        loadingContainer.classList.add('hidden');
        cameraView.classList.remove('hidden');
        
        // Access the camera with preferred settings
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        });
        
        // Connect the stream to the video element
        videoElement.srcObject = stream;
        
        // Wait for video to be ready
        await videoElement.play();
    } catch (error) {
        console.error('Error accessing camera:', error);
        showError('Unable to access your camera. Please ensure camera permissions are granted and try again.');
    }
}

/**
 * Capture an image from the video stream
 */
function captureImage() {
    // Create a canvas to capture the frame
    const canvas = document.createElement('canvas');
    canvas.width = videoElement.videoWidth;
    canvas.height = videoElement.videoHeight;
    
    // Draw the current video frame to the canvas
    const context = canvas.getContext('2d');
    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
    
    // Convert the canvas to a data URL (base64 image)
    capturedImageData = canvas.toDataURL('image/jpeg', 0.9);
    
    // Display the captured image in the preview
    previewImage.src = capturedImageData;
    
    // Stop the camera stream
    stopCamera();
    
    // Show the preview container
    cameraView.classList.add('hidden');
    previewContainer.classList.remove('hidden');
}

/**
 * Stop the camera stream
 */
function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
        videoElement.srcObject = null;
    }
}

/**
 * Verify the captured image against the Omang photo
 */
async function verifyImage() {
    if (!capturedImageData) {
        showError('No image captured. Please take a photo first.');
        return;
    }
    
    // Show loading state
    previewContainer.classList.add('hidden');
    loadingContainer.classList.remove('hidden');
    
    try {
        // Prepare the form data
        const formData = new FormData();
        formData.append('selfie_base64', capturedImageData);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Send the image to the server
        const response = await fetch('/verification/facial', {
            method: 'POST',
            body: formData
        });
        
        // Parse the response
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Verification failed. Please try again.');
        }
        
        // Display the verification result
        displayVerificationResult(result);
    } catch (error) {
        console.error('Verification error:', error);
        showError(error.message || 'An error occurred during verification. Please try again.');
    }
}

/**
 * Display the verification result
 */
function displayVerificationResult(result) {
    // Hide loading container
    loadingContainer.classList.add('hidden');
    
    // Update the UI with the result
    selfieImage.src = capturedImageData;
    
    // For demo purposes, we're using the selfie as the Omang photo
    // In a real implementation, this would come from the server
    omangImage.src = capturedImageData;
    
    // Update the similarity score
    similarityScore.textContent = `${result.similarity_score.toFixed(1)}%`;
    scoreProgressBar.style.width = `${result.similarity_score}%`;
    
    // Apply appropriate styling based on result
    if (result.passed) {
        verificationResult.textContent = 'Verification Passed';
        verificationResult.classList.add('text-green-600');
        verificationMessage.textContent = 'Your facial verification has been approved. You can now continue to the next step.';
        verificationMessage.classList.add('text-green-800');
        verificationMessage.classList.remove('text-red-800');
        nextButton.classList.remove('hidden');
    } else {
        verificationResult.textContent = 'Verification Failed';
        verificationResult.classList.add('text-red-600');
        verificationMessage.textContent = `The similarity score (${result.similarity_score.toFixed(1)}%) is below the required threshold (${result.threshold}%). Please try again with better lighting and a clear view of your face.`;
        verificationMessage.classList.add('text-red-800');
        verificationMessage.classList.remove('text-green-800');
        nextButton.classList.add('hidden');
    }
    
    // Show the result container
    resultContainer.classList.remove('hidden');
}

/**
 * Show an error message
 */
function showError(message) {
    errorMessage.textContent = message;
    
    // Hide other containers
    cameraView.classList.add('hidden');
    previewContainer.classList.add('hidden');
    loadingContainer.classList.add('hidden');
    resultContainer.classList.add('hidden');
    
    // Show error container
    errorContainer.classList.remove('hidden');
}

// Initialize when the DOM is ready
document.addEventListener('DOMContentLoaded', initFacialVerification);

// Cleanup when the page is unloaded
window.addEventListener('beforeunload', stopCamera);
