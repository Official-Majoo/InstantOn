<div>
    <div class="row mb-4">
        <!-- Omang Front -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title h6 mb-0">Omang Front</h3>
                </div>
                <div class="card-body">
                    @if ($documents->where('document_type', 'omang_front')->count() > 0 && !$replacingDocument['omang_front'])
                        <div class="document-container">
                            <img src="{{ route('documents.show', $documents->where('document_type', 'omang_front')->first()->id) }}"
                                alt="Omang Front">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> Uploaded
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                wire:click="toggleReplaceMode('omang_front')">
                                <i class="fas fa-sync me-1"></i> Replace
                            </button>
                        </div>
                    @else
                        <div
                            class="document-upload-container mb-3 {{ $uploadProgress['omang_front'] ? 'd-none' : '' }}">
                            <div class="mb-3">
                                <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                                <h4 class="h6">Upload Omang Front</h4>
                                <p class="text-muted small">Click to browse or drag and drop</p>
                            </div>
                            <input type="file" class="form-control" wire:model="omangFront" accept="image/*"
                                id="omangFront">
                            <div class="form-text">JPG, JPEG, or PNG (Max 5MB)</div>
                        </div>

                        @if ($omangFront)
                            <div class="document-preview">
                                <img src="{{ $omangFront->temporaryUrl() }}" class="img-fluid rounded"
                                    alt="Omang Front Preview">
                                <button type="button" class="btn btn-sm btn-danger mt-2"
                                    wire:click="$set('omangFront', null)">
                                    <i class="fas fa-times me-1"></i> Remove
                                </button>
                            </div>
                        @endif

                        <div class="mt-3 {{ $uploadProgress['omang_front'] ? '' : 'd-none' }}">
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-center text-muted small">Uploading...</p>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            @if ($replacingDocument['omang_front'])
                                <button type="button" class="btn btn-outline-secondary"
                                    wire:click="toggleReplaceMode('omang_front', false)">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                            @else
                                <div></div> <!-- Empty div for flex spacing -->
                            @endif

                            <button type="button" class="btn btn-primary" wire:click="uploadOmangFront"
                                {{ $omangFront ? '' : 'disabled' }}
                                {{ $uploadProgress['omang_front'] ? 'disabled' : '' }}>
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Omang Back -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title h6 mb-0">Omang Back</h3>
                </div>
                <div class="card-body">
                    @if ($documents->where('document_type', 'omang_back')->count() > 0)
                        <div class="document-container">
                            <img src="{{ route('documents.show', $documents->where('document_type', 'omang_back')->first()->id) }}"
                                alt="Omang Back">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> Uploaded
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                wire:click="$set('uploadProgress.omang_back', false)">
                                <i class="fas fa-sync me-1"></i> Replace
                            </button>
                        </div>
                    @else
                        <div class="document-upload-container mb-3 {{ $uploadProgress['omang_back'] ? 'd-none' : '' }}">
                            <div class="mb-3">
                                <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                                <h4 class="h6">Upload Omang Back</h4>
                                <p class="text-muted small">Click to browse or drag and drop</p>
                            </div>
                            <input type="file" class="form-control" wire:model="omangBack" accept="image/*"
                                id="omangBack">
                            <div class="form-text">JPG, JPEG, or PNG (Max 5MB)</div>
                        </div>

                        @if ($omangBack)
                            <div class="document-preview">
                                <img src="{{ $omangBack->temporaryUrl() }}" class="img-fluid rounded"
                                    alt="Omang Back Preview">
                                <button type="button" class="btn btn-sm btn-danger mt-2"
                                    wire:click="$set('omangBack', null)">
                                    <i class="fas fa-times me-1"></i> Remove
                                </button>
                            </div>
                        @endif

                        <div class="mt-3 {{ $uploadProgress['omang_back'] ? '' : 'd-none' }}">
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-center text-muted small">Uploading...</p>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary" wire:click="uploadOmangBack"
                                {{ $omangBack ? '' : 'disabled' }}
                                {{ $uploadProgress['omang_back'] ? 'disabled' : '' }}>
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Proof of Address -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title h6 mb-0">Proof of Address</h3>
                </div>
                <div class="card-body">
                    @if ($documents->where('document_type', 'proof_of_address')->count() > 0)
                        @php
                            $proofOfAddress = $documents->where('document_type', 'proof_of_address')->first();
                            $isPdf = $proofOfAddress->mime_type === 'application/pdf';
                            // Use original filename if available, otherwise use system filename
                            $displayFilename =
                                $proofOfAddress->original_filename ?? basename($proofOfAddress->file_path);
                        @endphp

                        @if ($isPdf)
                            <div class="pdf-thumbnail">
                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                <p class="pdf-filename">{{ $displayFilename }}</p>
                            </div>
                        @else
                            <div class="document-container">
                                <img src="{{ route('documents.show', $proofOfAddress->id) }}" alt="Proof of Address">
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> Uploaded
                            </span>
                            <div>
                                <a href="{{ route('documents.show', $proofOfAddress->id) }}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="$set('uploadProgress.proof_of_address', false)">
                                    <i class="fas fa-sync me-1"></i> Replace
                                </button>
                            </div>
                        </div>
                    @else
                        <div
                            class="document-upload-container mb-3 {{ $uploadProgress['proof_of_address'] ? 'd-none' : '' }}">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h4 class="h6">Upload Proof of Address</h4>
                                <p class="text-muted small">Click to browse or drag and drop</p>
                            </div>
                            <input type="file" class="form-control" wire:model="proofOfAddress"
                                accept="image/*,application/pdf" id="proofOfAddress">
                            <div class="form-text">JPG, JPEG, PNG, or PDF (Max 5MB)</div>
                        </div>

                        @if ($proofOfAddress)
                            <div class="document-preview">
                                @if (in_array($proofOfAddress->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg']))
                                    <img src="{{ $proofOfAddress->temporaryUrl() }}" class="img-fluid rounded"
                                        alt="Proof of Address Preview">
                                @else
                                    <div class="pdf-thumbnail">
                                        <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                        <p class="mt-2 mb-0 small">{{ $proofOfAddress->getClientOriginalName() }}</p>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-sm btn-danger mt-2"
                                    wire:click="$set('proofOfAddress', null)">
                                    <i class="fas fa-times me-1"></i> Remove
                                </button>
                            </div>
                        @endif

                        <div class="mt-3 {{ $uploadProgress['proof_of_address'] ? '' : 'd-none' }}">
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                    role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <p class="text-center text-muted small">Uploading...</p>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary" wire:click="uploadProofOfAddress"
                                {{ $proofOfAddress ? '' : 'disabled' }}
                                {{ $uploadProgress['proof_of_address'] ? 'disabled' : '' }}>
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Document Status Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h6 mb-3">Document Upload Status</h3>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Status</th>
                                    <th>Upload Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Omang Front</td>
                                    <td>
                                        @if ($documents->where('document_type', 'omang_front')->count() > 0)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>
                                                Uploaded</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Not
                                                Uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($documents->where('document_type', 'omang_front')->count() > 0)
                                            {{ $documents->where('document_type', 'omang_front')->first()->uploaded_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Omang Back</td>
                                    <td>
                                        @if ($documents->where('document_type', 'omang_back')->count() > 0)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>
                                                Uploaded</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Not
                                                Uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($documents->where('document_type', 'omang_back')->count() > 0)
                                            {{ $documents->where('document_type', 'omang_back')->first()->uploaded_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Proof of Address</td>
                                    <td>
                                        @if ($documents->where('document_type', 'proof_of_address')->count() > 0)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>
                                                Uploaded</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Not
                                                Uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($documents->where('document_type', 'proof_of_address')->count() > 0)
                                            {{ $documents->where('document_type', 'proof_of_address')->first()->uploaded_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="progress mt-3">
                        @php
                            $uploadedCount = $documents
                                ->whereIn('document_type', ['omang_front', 'omang_back', 'proof_of_address'])
                                ->count();
                            $percentComplete = ($uploadedCount / 3) * 100;
                        @endphp
                        <div class="progress-bar {{ $percentComplete == 100 ? 'bg-success' : '' }}"
                            role="progressbar" style="width: {{ $percentComplete }}%;"
                            aria-valuenow="{{ $percentComplete }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $uploadedCount }}/3 Documents
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @error('omangFront')
        <div class="alert alert-danger mt-3">{{ $message }}</div>
    @enderror

    @error('omangBack')
        <div class="alert alert-danger mt-3">{{ $message }}</div>
    @enderror

    @error('proofOfAddress')
        <div class="alert alert-danger mt-3">{{ $message }}</div>
    @enderror

    @error('upload')
        <div class="alert alert-danger mt-3">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');

            fileInputs.forEach(input => {
                const container = input.closest('.document-upload-container');

                // Highlight on drag over
                container.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    container.classList.add('dragging');
                });

                container.addEventListener('dragleave', function() {
                    container.classList.remove('dragging');
                });

                container.addEventListener('drop', function() {
                    container.classList.remove('dragging');
                });

                // Trigger file input when container is clicked
                container.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        input.click();
                    }
                });
            });
        });
    </script>
@endpush
