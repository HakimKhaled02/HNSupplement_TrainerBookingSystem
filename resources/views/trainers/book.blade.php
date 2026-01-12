@extends('layouts.app')

@section('title', 'Book Trainer - ' . $trainer->user->name)

@section('content')
<div class="booking-page-container">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Trainer Info Section -->
            <div class="col-lg-4">
                <div class="booking-trainer-card">
                    <div class="booking-trainer-image">
                        @if($trainer->profile_picture)
                            <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                 alt="{{ $trainer->user->name }}">
                        @else
                            <div class="booking-trainer-image-placeholder">
                                {{ strtoupper(substr($trainer->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="booking-trainer-info">
                        <h2 class="booking-trainer-name">{{ $trainer->user->name }}</h2>
                        <div class="booking-trainer-rating">
                            <div class="booking-rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= floor($trainer->rating ?? 0) ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="booking-rating-value">{{ number_format($trainer->rating ?? 0, 1) }}</span>
                        </div>
                        <div class="booking-trainer-details">
                            <div class="booking-detail-item">
                                <i class="bi bi-tag"></i>
                                <span>{{ ucfirst(str_replace('_', ' ', $trainer->category ?? 'N/A')) }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <i class="bi bi-geo-alt"></i>
                                <span>{{ $trainer->area ?? 'N/A' }}, {{ ucfirst(str_replace('_', ' ', $trainer->state ?? 'N/A')) }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <i class="bi bi-telephone"></i>
                                <span>{{ $trainer->phone ?? 'N/A' }}</span>
                            </div>
                            @if($trainer->salary)
                                <div class="booking-detail-item booking-salary">
                                    <i class="bi bi-wallet2"></i>
                                    <span>RM {{ number_format($trainer->salary, 0) }}/month</span>
                                </div>
                            @endif
                        </div>
                        <div class="booking-monthly-note">
                            <i class="bi bi-info-circle me-2"></i>
                            <span>1 monthly booking requires selecting 3 days/week, with 1 time slot per day</span>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="booking-reviews-card">
                    <h3 class="booking-reviews-title">
                        <i class="bi bi-star me-2"></i>Reviews & Feedback
                    </h3>
                    <div class="booking-reviews-content">
                        <div class="booking-no-reviews">
                            <i class="bi bi-chat-left-text"></i>
                            <p>No reviews yet. Be the first to book and review!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form Section -->
            <div class="col-lg-8">
                <div class="booking-form-card">
                    <div class="booking-form-header">
                        <h2 class="booking-form-title">
                            <i class="bi bi-calendar-check me-2"></i>Book Training Session
                        </h2>
                        <p class="booking-form-subtitle">Select your preferred dates, days, and time slots</p>
                    </div>
                    <form action="{{ route('booking.store') }}" method="POST" id="booking-form" class="booking-form">
                        @csrf
                        <input type="hidden" name="trainer_id" value="{{ $trainer->id }}">
                        
                        <!-- Date Range Selection -->
                        <div class="booking-form-section">
                            <h3 class="booking-section-title">Select Date Range</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="booking-form-label">
                                        <i class="bi bi-calendar-event me-2"></i>Start Date
                                    </label>
                                    <input type="date" 
                                           name="start_date" 
                                           id="start_date"
                                           class="booking-form-input" 
                                           required
                                           min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="booking-form-label">
                                        <i class="bi bi-calendar-event me-2"></i>End Date
                                    </label>
                                    <input type="date" 
                                           name="end_date" 
                                           id="end_date"
                                           class="booking-form-input" 
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           readonly>
                                </div>
                            </div>
                            <p class="booking-form-help">Select a start date and the end date will automatically be set to one month later</p>
                        </div>

                        <!-- Days Selection -->
                        <div class="booking-form-section">
                            <h3 class="booking-section-title">Select Days</h3>
                            <div class="booking-days-selection">
                                @if(!empty($availableDays))
                                    @foreach($availableDays as $dayKey => $dayData)
                                        <label class="booking-day-checkbox-label">
                                            <input type="checkbox" 
                                                   name="selected_days[]" 
                                                   value="{{ $dayKey }}"
                                                   class="booking-day-checkbox"
                                                   data-day="{{ $dayKey }}">
                                            <span class="booking-day-name">{{ $dayData['name'] }}</span>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="booking-no-availability">Trainer has not set availability yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Time Selection -->
                        <div class="booking-form-section">
                            <h3 class="booking-section-title">Select Time Slots</h3>
                            <div id="time-slots-container" class="booking-time-slots-container">
                                <p class="booking-select-days-first">Please select days first to see available time slots</p>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="booking-summary-section">
                            <h3 class="booking-section-title">Booking Summary</h3>
                            <div class="booking-summary-content">
                                <div id="summary-schedule" class="booking-summary-schedule">
                                    <p class="booking-summary-empty">No days and times selected yet</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="booking-form-actions">
                            <a href="{{ route('trainers') }}" class="booking-cancel-btn">
                                <i class="bi bi-arrow-left me-2"></i>Back to Trainers
                            </a>
                            <button type="submit" class="booking-submit-btn" id="submit-booking" disabled>
                                <i class="bi bi-check-circle me-2"></i>Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const dayCheckboxes = document.querySelectorAll('.booking-day-checkbox');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const submitBtn = document.getElementById('submit-booking');
    
    // Available time slots from trainer availability
    const availableTimeSlots = @json($availableDays);
    const trainerId = {{ $trainer->id }};
    let bookedSlots = {}; // Will store booked slots from API
    
    // Set max date to 1 month from today
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1);
    startDateInput.setAttribute('max', maxDate.toISOString().split('T')[0]);
    endDateInput.setAttribute('max', maxDate.toISOString().split('T')[0]);
    
    // Check availability from server
    async function checkAvailability() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) {
            return;
        }
        
        try {
            const response = await fetch(`/trainer/${trainerId}/check-availability?start_date=${startDate}&end_date=${endDate}`);
            const data = await response.json();
            
            if (data.available && data.booked_slots) {
                bookedSlots = data.booked_slots;
                updateTimeSlots();
                updateDayAvailability();
            }
        } catch (error) {
            console.error('Error checking availability:', error);
        }
    }
    
    // Validate date range and auto-set end date
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const maxEndDate = new Date(startDate);
        maxEndDate.setMonth(maxEndDate.getMonth() + 1);
        endDateInput.setAttribute('min', this.value);
        endDateInput.setAttribute('max', maxEndDate.toISOString().split('T')[0]);
        
        // Automatically set end date to one month later
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + 1);
        endDateInput.value = endDate.toISOString().split('T')[0];
        
        // Make end date readonly after auto-fill
        endDateInput.setAttribute('readonly', 'readonly');
        
        // Check availability
        checkAvailability();
        updateSummary();
    });
    
    endDateInput.addEventListener('change', function() {
        checkAvailability();
        updateSummary();
    });
    
    // Update day availability - disable days that are fully booked
    function updateDayAvailability() {
        dayCheckboxes.forEach(checkbox => {
            const dayKey = checkbox.dataset.day;
            if (bookedSlots[dayKey]) {
                // Check if all time slots for this day are booked
                const daySlots = availableTimeSlots[dayKey]?.timeSlots || [];
                let allBooked = true;
                
                for (const slot of daySlots) {
                    const slotKey = `${slot.start}_${slot.end}`;
                    const isBooked = bookedSlots[dayKey].some(booked => 
                        booked.start_time === slot.start && booked.end_time === slot.end
                    );
                    if (!isBooked) {
                        allBooked = false;
                        break;
                    }
                }
                
                if (allBooked && daySlots.length > 0) {
                    checkbox.disabled = true;
                    checkbox.closest('.booking-day-checkbox-label').classList.add('booking-day-disabled');
                } else {
                    checkbox.disabled = false;
                    checkbox.closest('.booking-day-checkbox-label').classList.remove('booking-day-disabled');
                }
            } else {
                checkbox.disabled = false;
                checkbox.closest('.booking-day-checkbox-label').classList.remove('booking-day-disabled');
            }
        });
    }
    
    // Handle day selection
    dayCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateTimeSlots();
            updateSummary();
        });
    });
    
    function updateTimeSlots() {
        const selectedDays = Array.from(document.querySelectorAll('.booking-day-checkbox:checked'))
            .map(cb => cb.dataset.day);
        
        if (selectedDays.length === 0) {
            timeSlotsContainer.innerHTML = '<p class="booking-select-days-first">Please select days first to see available time slots</p>';
            return;
        }
        
        let html = '<div class="booking-time-slots-grid">';
        
        selectedDays.forEach(dayKey => {
            if (availableTimeSlots[dayKey] && availableTimeSlots[dayKey].timeSlots) {
                const dayName = availableTimeSlots[dayKey].name;
                html += `<div class="booking-time-day-group" data-day="${dayKey}">`;
                html += `<h4 class="booking-time-day-title">${dayName}</h4>`;
                html += '<div class="booking-time-slots-list">';
                
                availableTimeSlots[dayKey].timeSlots.forEach((slot, index) => {
                    const slotId = `${dayKey}-${index}`;
                    const startTime = formatTime(slot.start);
                    const endTime = formatTime(slot.end);
                    
                    // Check if this slot is booked
                    const isBooked = bookedSlots[dayKey]?.some(booked => 
                        booked.start_time === slot.start && booked.end_time === slot.end
                    ) || false;
                    
                    const paymentStatus = bookedSlots[dayKey]?.find(booked => 
                        booked.start_time === slot.start && booked.end_time === slot.end
                    )?.payment_status;
                    
                    const isPending = paymentStatus === 'pending';
                    const isPaid = paymentStatus === 'paid';
                    
                    html += `
                        <label class="booking-time-slot-label ${isBooked ? 'booking-time-slot-booked' : ''} ${isPending ? 'booking-time-slot-pending' : ''}">
                            <input type="checkbox" 
                                   name="time_slots[${dayKey}][]" 
                                   value="${slot.start}-${slot.end}"
                                   class="booking-time-slot-checkbox"
                                   data-day="${dayKey}"
                                   ${isBooked ? 'disabled' : ''}>
                            <span class="booking-time-slot-text">
                                ${startTime} - ${endTime}
                                ${isPending ? ' <span class="booking-pending-badge">(Pending Payment)</span>' : ''}
                                ${isPaid ? ' <span class="booking-booked-badge">(Booked)</span>' : ''}
                            </span>
                        </label>
                    `;
                });
                
                html += '</div></div>';
            }
        });
        
        html += '</div>';
        timeSlotsContainer.innerHTML = html;
        
        // Add event listeners to time slot checkboxes
        document.querySelectorAll('.booking-time-slot-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const dayKey = this.dataset.day;
                // If this checkbox is checked, uncheck all other checkboxes for the same day
                if (this.checked) {
                    document.querySelectorAll(`.booking-time-slot-checkbox[data-day="${dayKey}"]`).forEach(otherCb => {
                        if (otherCb !== this) {
                            otherCb.checked = false;
                        }
                    });
                }
                updateSummary();
            });
        });
    }
    
    function formatTime(timeString) {
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }
    
    function updateSummary() {
        const selectedDays = Array.from(document.querySelectorAll('.booking-day-checkbox:checked'))
            .map(cb => availableTimeSlots[cb.dataset.day]?.name || cb.dataset.day);
        
        const selectedTimeSlots = Array.from(document.querySelectorAll('.booking-time-slot-checkbox:checked'))
            .map(cb => {
                const dayKey = cb.dataset.day;
                const dayName = availableTimeSlots[dayKey]?.name || dayKey;
                const timeText = cb.nextElementSibling.textContent;
                return `${dayName}: ${timeText}`;
            });
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        // Calculate number of sessions
        let sessionCount = 0;
        if (startDate && endDate && selectedDays.length > 0) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const selectedDayKeys = Array.from(document.querySelectorAll('.booking-day-checkbox:checked'))
                .map(cb => cb.dataset.day);
            
            const dayMap = {
                'monday': 1,
                'tuesday': 2,
                'wednesday': 3,
                'thursday': 4,
                'friday': 5,
                'saturday': 6,
                'sunday': 0
            };
            
            const selectedDayNumbers = selectedDayKeys.map(key => dayMap[key]).filter(n => n !== undefined);
            const timeSlotCount = document.querySelectorAll('.booking-time-slot-checkbox:checked').length;
            
            let currentDate = new Date(start);
            while (currentDate <= end) {
                const dayOfWeek = currentDate.getDay();
                if (selectedDayNumbers.includes(dayOfWeek)) {
                    sessionCount += timeSlotCount;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }
        
        // Update summary with day and time details
        const summarySchedule = document.getElementById('summary-schedule');
        if (selectedDays.length === 0 || selectedTimeSlots.length === 0) {
            summarySchedule.innerHTML = '<p class="booking-summary-empty">No days and times selected yet</p>';
        } else {
            // Create a map of day to time slot
            const dayTimeMap = {};
            Array.from(document.querySelectorAll('.booking-time-slot-checkbox:checked')).forEach(cb => {
                const dayKey = cb.dataset.day;
                const dayName = availableTimeSlots[dayKey]?.name || dayKey;
                const timeText = cb.nextElementSibling.textContent.trim();
                dayTimeMap[dayKey] = {
                    dayName: dayName,
                    time: timeText
                };
            });
            
            // Sort by day order
            const dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            const sortedDays = Array.from(document.querySelectorAll('.booking-day-checkbox:checked'))
                .map(cb => cb.dataset.day)
                .sort((a, b) => dayOrder.indexOf(a) - dayOrder.indexOf(b));
            
            let html = '<div class="booking-summary-schedule-list">';
            sortedDays.forEach(dayKey => {
                if (dayTimeMap[dayKey]) {
                    html += `
                        <div class="booking-summary-schedule-item">
                            <span class="booking-summary-day-name">${dayTimeMap[dayKey].dayName}</span>
                            <span class="booking-summary-time">${dayTimeMap[dayKey].time}</span>
                        </div>
                    `;
                }
            });
            html += '</div>';
            summarySchedule.innerHTML = html;
        }
        
        // Enable/disable submit button
        // Must have at least 3 days selected and exactly 1 time slot per selected day
        const selectedDayCount = selectedDays.length;
        const timeSlotCount = selectedTimeSlots.length;
        const canSubmit = startDate && endDate && selectedDayCount >= 3 && timeSlotCount === selectedDayCount;
        submitBtn.disabled = !canSubmit;
    }
    
    // Form validation
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const selectedDays = document.querySelectorAll('.booking-day-checkbox:checked');
        const selectedTimeSlots = document.querySelectorAll('.booking-time-slot-checkbox:checked');
        
        if (!startDate || !endDate) {
            alert('Please select start and end dates');
            return;
        }
        
        if (selectedDays.length < 3) {
            alert('Please select at least 3 days per week for monthly booking');
            return;
        }
        
        // Check that exactly 1 time slot is selected per day
        const timeSlotsByDay = {};
        Array.from(selectedTimeSlots).forEach(cb => {
            const dayKey = cb.dataset.day;
            if (!timeSlotsByDay[dayKey]) {
                timeSlotsByDay[dayKey] = 0;
            }
            timeSlotsByDay[dayKey]++;
        });
        
        const selectedDayKeys = Array.from(selectedDays).map(cb => cb.dataset.day);
        for (const dayKey of selectedDayKeys) {
            if (!timeSlotsByDay[dayKey] || timeSlotsByDay[dayKey] !== 1) {
                alert('Please select exactly 1 time slot for each selected day');
                return;
            }
        }
        
        if (selectedTimeSlots.length !== selectedDays.length) {
            alert('Please select exactly 1 time slot for each selected day');
            return;
        }
        
        // Calculate date range
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 31) {
            alert('Booking period cannot exceed one month (31 days)');
            return;
        }
        
        // Form is ready to submit
        this.submit();
    });
});
</script>
@endsection

