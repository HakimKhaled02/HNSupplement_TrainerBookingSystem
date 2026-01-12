@extends('layouts.dashboard')

@section('title', 'Trainers - Admin Dashboard')

@section('page-title', 'All Trainers')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@section('content')
<!-- Trainers List -->
<div class="card dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-people me-2"></i>
            Approved Trainers
            <span class="approval-count-badge">{{ $trainers->count() }}</span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        @if($trainers->count() > 0)
            <div class="trainers-list-simple">
                @foreach($trainers as $trainer)
                    <div class="trainer-list-item">
                        <div class="trainer-list-avatar">
                            @if($trainer->profile_picture)
                                <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                     alt="{{ $trainer->user->name }}">
                            @else
                                <span>{{ strtoupper(substr($trainer->user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        
                        <div class="trainer-list-info">
                            <div class="trainer-list-name">{{ $trainer->user->name }}</div>
                            <div class="trainer-list-details">
                                <span><i class="bi bi-envelope-at"></i> {{ $trainer->user->email }}</span>
                                <span><i class="bi bi-telephone"></i> {{ $trainer->phone ?? 'N/A' }}</span>
                                <span><i class="bi bi-geo-alt"></i> {{ $trainer->area ?? 'N/A' }}, {{ $trainer->state ?? 'N/A' }}</span>
                                <span><i class="bi bi-tag"></i> {{ ucfirst($trainer->category ?? 'N/A') }}</span>
                                <span><i class="bi bi-star"></i> {{ number_format($trainer->rating ?? 0, 2) }}</span>
                                @if($trainer->qualification_file)
                                    <a href="{{ asset('storage/' . $trainer->qualification_file) }}" 
                                       target="_blank" 
                                       class="qualification-link-inline">
                                        <i class="bi bi-file-earmark-pdf"></i> Qualification
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="trainer-list-salary">
                            <form action="{{ route('admin.trainer.update-salary', $trainer->id) }}" 
                                  method="POST" 
                                  class="salary-form-inline">
                                @csrf
                                <div class="salary-input-inline">
                                    <span class="salary-currency-inline">RM</span>
                                    <input type="number" 
                                           name="salary" 
                                           value="{{ $trainer->salary ?? '' }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="salary-input-inline-field" 
                                           placeholder="0.00"
                                           required>
                                    <button type="submit" class="salary-save-btn-inline" title="Save">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="trainer-list-actions">
                            <button type="button" 
                                    class="btn trainer-view-btn-inline" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#trainerModal{{ $trainer->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Trainer Details Modal -->
                    <div class="modal fade" id="trainerModal{{ $trainer->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content trainer-modal-content">
                                <div class="modal-header trainer-modal-header">
                                    <h5 class="modal-title">Trainer Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body trainer-modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-3">
                                            @if($trainer->profile_picture)
                                                <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                                     alt="{{ $trainer->user->name }}" 
                                                     class="trainer-modal-img">
                                            @else
                                                <div class="trainer-avatar-large">
                                                    {{ strtoupper(substr($trainer->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <h4 class="mt-3">{{ $trainer->user->name }}</h4>
                                            <p class="text-muted">{{ $trainer->user->email }}</p>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="trainer-details-list">
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-telephone me-2"></i>Phone:</strong>
                                                    <span>{{ $trainer->phone ?? 'N/A' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-geo-alt me-2"></i>Location:</strong>
                                                    <span>{{ $trainer->area ?? 'N/A' }}, {{ $trainer->state ?? 'N/A' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-tag me-2"></i>Category:</strong>
                                                    <span>{{ ucfirst($trainer->category ?? 'N/A') }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-star me-2"></i>Rating:</strong>
                                                    <span>{{ number_format($trainer->rating ?? 0, 2) }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-currency-dollar me-2"></i>Hourly Rate:</strong>
                                                    <span>RM {{ number_format($trainer->hourly_rate ?? 0, 2) }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-wallet2 me-2"></i>Salary:</strong>
                                                    <span>RM {{ number_format($trainer->salary ?? 0, 2) }}</span>
                                                </div>
                                                @if($trainer->bio)
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-person-badge me-2"></i>Bio:</strong>
                                                    <p>{{ $trainer->bio }}</p>
                                                </div>
                                                @endif
                                                @if($trainer->specialties)
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-award me-2"></i>Specialties:</strong>
                                                    <div class="specialties-list">
                                                        @foreach($trainer->specialties as $specialty)
                                                            <span class="badge badge-specialty">{{ $specialty }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                @if($trainer->certifications)
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-patch-check me-2"></i>Certifications:</strong>
                                                    <div class="certifications-list">
                                                        @foreach($trainer->certifications as $cert)
                                                            <span class="badge badge-cert">{{ $cert }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                @if($trainer->qualification_file)
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-file-earmark-pdf me-2"></i>Qualification File:</strong>
                                                    <a href="{{ asset('storage/' . $trainer->qualification_file) }}" 
                                                       target="_blank" 
                                                       class="qualification-link">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No approved trainers found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
