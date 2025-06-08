<!-- Document Upload Component for Step 3 -->
<div class="step-3">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Required Documents</h3>
    
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
    
    <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded">
        <p class="text-sm text-blue-800">
            <strong>Document Requirements:</strong> Please upload clear, legible images or PDFs. All documents must be valid and not expired. Maximum file size is 5MB per document.
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="mb-4">
            <label for="proof_of_address" class="form-label">Proof of Address</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="proof_of_address" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span>Upload a file</span>
                            <input id="proof_of_address" wire:model="proof_of_address" type="file" class="sr-only">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">
                        Utility bill, lease agreement, or bank statement (PDF, JPG, PNG)
                    </p>
                </div>
            </div>
            @error('proof_of_address') <span class="form-error">{{ $message }}</span> @enderror
            
            @if ($proof_of_address)
                <div class="mt-2 flex items-center text-sm text-green-600">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    File uploaded: {{ $proof_of_address->getClientOriginalName() }}
                </div>
            @endif
        </div>
        
        <div class="mb-4">
            <label for="selfie_photo" class="form-label">Selfie Photo</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="selfie_photo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span>Upload a photo</span>
                            <input id="selfie_photo" wire:model="selfie_photo" type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">or take a new one</p>
                    </div>
                    <p class="text-xs text-gray-500">
                        Clear face photo with neutral background (JPG, PNG)
                    </p>
                </div>
            </div>
            @error('selfie_photo') <span class="form-error">{{ $message }}</span> @enderror
            
            @if ($selfie_photo)
                <div class="mt-2">
                    <div class="flex items-center text-sm text-green-600 mb-2">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        File uploaded: {{ $selfie_photo->getClientOriginalName() }}
                    </div>
                    <div class="relative h-32 w-32 mx-auto rounded-full overflow-hidden border-2 border-blue-500">
                        <img src="{{ $selfie_photo->temporaryUrl() }}" class="h-full w-full object-cover" alt="Selfie preview">
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-6 p-4 bg-gray-50 rounded-md">
        <h4 class="text-sm font-medium text-gray-900 mb-2">Document Guidelines</h4>
        <ul class="list-disc text-sm text-gray-600 pl-5 space-y-1">
            <li>Proof of address must be dated within the last 3 months</li>
            <li>Selfie photo must clearly show your face with neutral expression</li>
            <li>All documents must be clear and legible</li>
            <li>Maximum file size: 5MB per document</li>
            <li>Accepted formats: PDF, JPG, PNG</li>
        </ul>
    </div>
</div>