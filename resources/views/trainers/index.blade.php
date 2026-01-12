@extends('layouts.app')

@section('title', 'Find Trainers - HN Supplement')

@section('content')
<div class="trainers-page-container">
    <div class="container py-5">
        <div class="trainers-layout-container">
            <!-- Filters Sidebar -->
            <div class="trainers-filters-sidebar">
                <div class="trainers-filters-card">
                    <div class="trainers-filters-header">
                        <h3 class="trainers-filters-title">
                            <i class="bi bi-funnel me-2"></i>Filters
                        </h3>
                        @if(request()->anyFilled(['category', 'state', 'area', 'availability_day', 'min_rating']))
                            <a href="{{ route('trainers') }}" class="trainers-clear-filters">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </a>
                        @endif
                    </div>
                    <form method="GET" action="{{ route('trainers') }}" class="trainers-filters-form">
                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <select name="category" class="filter-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">State</label>
                            <select name="state" class="filter-select">
                                <option value="">All States</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $state)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Area</label>
                            <input type="text" 
                                   name="area" 
                                   class="filter-input" 
                                   placeholder="Enter area"
                                   value="{{ request('area') }}">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Availability Day</label>
                            <select name="availability_day" class="filter-select">
                                <option value="">Any Day</option>
                                <option value="monday" {{ request('availability_day') == 'monday' ? 'selected' : '' }}>Monday</option>
                                <option value="tuesday" {{ request('availability_day') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="wednesday" {{ request('availability_day') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="thursday" {{ request('availability_day') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="friday" {{ request('availability_day') == 'friday' ? 'selected' : '' }}>Friday</option>
                                <option value="saturday" {{ request('availability_day') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="sunday" {{ request('availability_day') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Min Rating</label>
                            <select name="min_rating" class="filter-select">
                                <option value="">Any Rating</option>
                                <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ Stars</option>
                                <option value="4.0" {{ request('min_rating') == '4.0' ? 'selected' : '' }}>4.0+ Stars</option>
                                <option value="3.5" {{ request('min_rating') == '3.5' ? 'selected' : '' }}>3.5+ Stars</option>
                                <option value="3.0" {{ request('min_rating') == '3.0' ? 'selected' : '' }}>3.0+ Stars</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-submit-btn">
                            <i class="bi bi-search me-2"></i>Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            <!-- Trainers Content -->
            <div class="trainers-content-area">
                <!-- Results Count -->
                <div class="trainers-results-count">
                    <span>Found <strong>{{ $trainers->total() }}</strong> trainer{{ $trainers->total() !== 1 ? 's' : '' }}</span>
                </div>

                <!-- Trainers Grid -->
                @if($trainers->count() > 0)
                    <div class="trainers-grid">
                        @foreach($trainers as $trainer)
                            <a href="{{ route('trainer.book', $trainer->id) }}" class="trainer-card-link">
                            <div class="trainer-card">
                                <div class="trainer-card-image">
                                    @if($trainer->profile_picture)
                                        <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                             alt="{{ $trainer->user->name }}">
                                    @else
                                        <div class="trainer-card-image-placeholder">
                                            {{ strtoupper(substr($trainer->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="trainer-card-badge">CERTIFIED</div>
                                    <div class="trainer-card-rating-badge">
                                        <i class="bi bi-star-fill"></i>
                                        <span>{{ number_format($trainer->rating ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="trainer-card-content">
                                    <h3 class="trainer-card-name">{{ $trainer->user->name }}</h3>
                                    <div class="trainer-card-info">
                                        <div class="trainer-card-info-item">
                                            <i class="bi bi-tag"></i>
                                            <span>{{ ucfirst(str_replace('_', ' ', $trainer->category ?? 'N/A')) }}</span>
                                        </div>
                                        <div class="trainer-card-info-item">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>{{ $trainer->area ?? 'N/A' }}, {{ ucfirst(str_replace('_', ' ', $trainer->state ?? 'N/A')) }}</span>
                                        </div>
                                        @if($trainer->availability && count($trainer->availability) > 0)
                                            @php
                                                $uniqueDays = [];
                                                $dayNames = [
                                                    'monday' => 'Monday',
                                                    'tuesday' => 'Tuesday',
                                                    'wednesday' => 'Wednesday',
                                                    'thursday' => 'Thursday',
                                                    'friday' => 'Friday',
                                                    'saturday' => 'Saturday',
                                                    'sunday' => 'Sunday'
                                                ];
                                                foreach ($trainer->availability as $avail) {
                                                    if (isset($avail['day']) && isset($dayNames[$avail['day']])) {
                                                        $dayName = $dayNames[$avail['day']];
                                                        if (!in_array($dayName, $uniqueDays)) {
                                                            $uniqueDays[] = $dayName;
                                                        }
                                                    }
                                                }
                                                $daysDisplay = !empty($uniqueDays) ? implode(', ', $uniqueDays) : 'Not set';
                                            @endphp
                                            <div class="trainer-card-info-item">
                                                <i class="bi bi-calendar-event"></i>
                                                <span>{{ $daysDisplay }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if($trainer->salary)
                                        <div class="trainer-card-price">
                                            <span class="trainer-card-price-label">Monthly Rate</span>
                                            <span class="trainer-card-price-amount">RM {{ number_format($trainer->salary, 0) }}</span>
                                        </div>
                                    @endif
                                    <div class="trainer-card-button">
                                        Book Now
                                    </div>
                                </div>
                            </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="trainers-pagination">
                        {{ $trainers->links() }}
                    </div>
                @else
                    <div class="trainers-empty">
                        <i class="bi bi-person-x"></i>
                        <h3>No trainers found</h3>
                        <p>Try adjusting your filters to see more results.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

