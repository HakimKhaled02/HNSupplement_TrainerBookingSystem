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
                        @if(request()->anyFilled(['category', 'state', 'price_range', 'availability_day', 'rating_range', 'user_lat', 'user_lng', 'radius']))
                            <a href="{{ route('trainers') }}" class="trainers-clear-filters">
                                <i class="bi bi-x-circle me-1"></i>Clear All
                            </a>
                        @endif
                    </div>
                    <form method="GET" action="{{ route('trainers') }}" class="trainers-filters-form">
                        <!-- Preserve location and radius when applying filters -->
                        @if(isset($userLat) && isset($userLng))
                            <input type="hidden" name="user_lat" value="{{ $userLat }}">
                            <input type="hidden" name="user_lng" value="{{ $userLng }}">
                        @endif
                        @if(isset($radius) && $radius)
                            <input type="hidden" name="radius" value="{{ $radius }}">
                        @endif
                        
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
                            <label class="filter-label">Price Range</label>
                            <select name="price_range" class="filter-select">
                                <option value="">All Prices</option>
                                <option value="below_100" {{ request('price_range') == 'below_100' ? 'selected' : '' }}>Below RM100</option>
                                <option value="100_300" {{ request('price_range') == '100_300' ? 'selected' : '' }}>RM100 - RM300</option>
                                <option value="300_500" {{ request('price_range') == '300_500' ? 'selected' : '' }}>RM300 - RM500</option>
                                <option value="above_500" {{ request('price_range') == 'above_500' ? 'selected' : '' }}>Above RM500</option>
                            </select>
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
                            <label class="filter-label">Rating</label>
                            <select name="rating_range" class="filter-select">
                                <option value="">All Ratings</option>
                                <option value="above_4.5" {{ request('rating_range') == 'above_4.5' ? 'selected' : '' }}>Above 4.5</option>
                                <option value="4.0_4.5" {{ request('rating_range') == '4.0_4.5' ? 'selected' : '' }}>4.0 - 4.5</option>
                                <option value="3.5_4.0" {{ request('rating_range') == '3.5_4.0' ? 'selected' : '' }}>3.5 - 4.0</option>
                                <option value="3.0_3.5" {{ request('rating_range') == '3.0_3.5' ? 'selected' : '' }}>3.0 - 3.5</option>
                                <option value="below_3.0" {{ request('rating_range') == 'below_3.0' ? 'selected' : '' }}>Below 3.0</option>
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
                <!-- Location & Radius Controls -->
                <div class="trainers-controls-bar">
                    <div class="trainers-location-control">
                        <button type="button" id="get-user-location" class="btn-location-detect">
                            <i class="bi bi-geo-alt-fill me-2"></i>Find Nearest Trainer
                        </button>
                        <span id="location-status" class="location-status-text"></span>
                        <input type="hidden" id="user-latitude" name="user_lat" value="{{ $userLat ?? '' }}">
                        <input type="hidden" id="user-longitude" name="user_lng" value="{{ $userLng ?? '' }}">
                    </div>
                    <div class="trainers-radius-control">
                        <label for="radius-filter" class="radius-label">Search Radius:</label>
                        <select id="radius-filter" name="radius" class="radius-select" onchange="updateRadius()">
                            <option value="">All Distance</option>
                            <option value="5" {{ ($radius ?? '') == '5' ? 'selected' : '' }}>5 km</option>
                            <option value="10" {{ ($radius ?? '') == '10' ? 'selected' : '' }}>10 km</option>
                            <option value="30" {{ ($radius ?? '') == '30' ? 'selected' : '' }}>30 km</option>
                            <option value="50" {{ ($radius ?? '') == '50' ? 'selected' : '' }}>50 km</option>
                        </select>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="trainers-results-count">
                    <span>Found <strong>{{ $trainers->total() }}</strong> trainer{{ $trainers->total() !== 1 ? 's' : '' }}</span>
                    @if(isset($userLat) && isset($userLng))
                        <span class="location-active-badge">
                            <i class="bi bi-check-circle me-1"></i>Location detected
                        </span>
                    @endif
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
                                            <div class="trainer-card-price-left">
                                                <span class="trainer-card-price-label">Monthly Rate</span>
                                                <span class="trainer-card-price-amount">RM {{ number_format($trainer->salary, 0) }}</span>
                                            </div>
                                            @if(isset($trainer->distance) && $trainer->distance !== null)
                                                <div class="trainer-card-distance">
                                                    <i class="bi bi-signpost-2 me-1"></i>{{ $trainer->distance }} km away
                                                </div>
                                            @endif
                                        </div>
                                    @elseif(isset($trainer->distance) && $trainer->distance !== null)
                                        <div class="trainer-card-price">
                                            <div class="trainer-card-price-left"></div>
                                            <div class="trainer-card-distance">
                                                <i class="bi bi-signpost-2 me-1"></i>{{ $trainer->distance }} km away
                                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('get-user-location');
    const locationStatus = document.getElementById('location-status');
    const userLatInput = document.getElementById('user-latitude');
    const userLngInput = document.getElementById('user-longitude');

    // Get user's current location
    getLocationBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            locationStatus.textContent = 'Geolocation is not supported by your browser';
            locationStatus.style.color = '#dc3545';
            return;
        }

        locationStatus.textContent = 'Detecting location...';
        locationStatus.style.color = '#17a2b8';
        getLocationBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                userLatInput.value = lat;
                userLngInput.value = lng;
                
                locationStatus.textContent = 'Location detected!';
                locationStatus.style.color = '#28a745';
                
                // Reload page with location parameters, preserving all existing filters
                const url = new URL(window.location.href);
                url.searchParams.set('user_lat', lat);
                url.searchParams.set('user_lng', lng);
                // Preserve all existing filter parameters (category, state, price_range, availability_day, rating_range, radius)
                window.location.href = url.toString();
            },
            function(error) {
                let errorMsg = 'Unable to detect location. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg += 'Please allow location access.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg += 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMsg += 'Location request timed out.';
                        break;
                    default:
                        errorMsg += 'An unknown error occurred.';
                        break;
                }
                locationStatus.textContent = errorMsg;
                locationStatus.style.color = '#dc3545';
                getLocationBtn.disabled = false;
            }
        );
    });

    // Update radius function - preserves all existing filters
    window.updateRadius = function() {
        const radiusSelect = document.getElementById('radius-filter');
        const radiusValue = radiusSelect.value;
        const userLat = document.getElementById('user-latitude').value;
        const userLng = document.getElementById('user-longitude').value;
        
        // If radius is selected but no location, prompt user to find location first
        if (radiusValue && (!userLat || !userLng)) {
            alert('Please click "Find Nearest Trainer" first to detect your location before setting a search radius.');
            radiusSelect.value = '';
            return;
        }
        
        const url = new URL(window.location.href);
        if (radiusValue) {
            url.searchParams.set('radius', radiusValue);
        } else {
            url.searchParams.delete('radius');
        }
        // Preserve all existing filter parameters (category, state, price_range, availability_day, rating_range, user_lat, user_lng)
        window.location.href = url.toString();
    };
});
</script>
@endsection

