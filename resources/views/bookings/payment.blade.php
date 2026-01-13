@extends('layouts.app')

@section('title', 'Payment - Booking #' . $booking->id)

@section('content')
<div class="payment-page-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="payment-card">
                    <div class="payment-header">
                        <h2 class="payment-title">
                            <i class="bi bi-credit-card me-2"></i>Complete Payment
                        </h2>
                        <p class="payment-subtitle">Booking #{{ $booking->id }}</p>
                        @if($booking->payment_expires_at)
                            <div class="payment-timer" id="payment-timer">
                                <i class="bi bi-clock me-2"></i>
                                <span id="timer-text">Time remaining: <strong id="countdown">--:--</strong></span>
                            </div>
                        @endif
                    </div>

                    <!-- Booking Summary -->
                    <div class="payment-booking-summary">
                        <h3 class="payment-section-title">Booking Details</h3>
                        <div class="payment-booking-info">
                            <div class="payment-info-row">
                                <span class="payment-info-label">Trainer:</span>
                                <span class="payment-info-value">{{ $booking->trainer->user->name }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span class="payment-info-label">Category:</span>
                                <span class="payment-info-value">{{ ucfirst(str_replace('_', ' ', $booking->trainer->category ?? 'N/A')) }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span class="payment-info-label">Location:</span>
                                <span class="payment-info-value">
                                    {{ $booking->trainer->area ?? 'N/A' }}, {{ ucfirst(str_replace('_', ' ', $booking->trainer->state ?? 'N/A')) }}
                                </span>
                            </div>
                            <div class="payment-info-row">
                                <span class="payment-info-label">Period:</span>
                                <span class="payment-info-value">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="payment-info-row">
                                <span class="payment-info-label">Days:</span>
                                <span class="payment-info-value">
                                    @php
                                        $dayNames = [
                                            'monday' => 'Monday',
                                            'tuesday' => 'Tuesday',
                                            'wednesday' => 'Wednesday',
                                            'thursday' => 'Thursday',
                                            'friday' => 'Friday',
                                            'saturday' => 'Saturday',
                                            'sunday' => 'Sunday'
                                        ];
                                        $selectedDays = array_map(function($day) use ($dayNames) {
                                            return $dayNames[$day] ?? ucfirst($day);
                                        }, $booking->selected_days);
                                    @endphp
                                    {{ implode(', ', $selectedDays) }}
                                </span>
                            </div>
                            <div class="payment-info-row">
                                <span class="payment-info-label">Time Slots:</span>
                                <span class="payment-info-value">
                                    @if($booking->time_slots)
                                        @foreach($booking->time_slots as $slot)
                                            @php
                                                $dayName = $dayNames[$slot['day']] ?? ucfirst($slot['day']);
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                            @endphp
                                            <div class="payment-time-slot">
                                                <strong>{{ $dayName }}:</strong> {{ $startTime }} - {{ $endTime }}
                                            </div>
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Amount -->
                    <div class="payment-amount-section">
                        <div class="payment-amount-row">
                            <span class="payment-amount-label">Total Amount:</span>
                            <span class="payment-amount-value">RM {{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('booking.payment.process', $booking->id) }}" method="POST" class="payment-form">
                        @csrf
                        
                        <div class="payment-method-section">
                            <h3 class="payment-section-title">Payment Method</h3>
                            <div class="payment-methods">
                                <label class="payment-method-option">
                                    <input type="radio" name="payment_method" value="card" checked>
                                    <div class="payment-method-content">
                                        <i class="bi bi-credit-card"></i>
                                        <span>Credit/Debit Card</span>
                                    </div>
                                </label>
                                <label class="payment-method-option">
                                    <input type="radio" name="payment_method" value="bank">
                                    <div class="payment-method-content">
                                        <i class="bi bi-bank"></i>
                                        <span>Bank Transfer</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Card Payment Fields (shown by default) -->
                        <div id="card-payment-fields" class="payment-fields">
                            <div class="payment-form-group">
                                <label class="payment-form-label">Card Number</label>
                                <input type="text" class="payment-form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="payment-form-group">
                                        <label class="payment-form-label">Expiry Date</label>
                                        <input type="text" class="payment-form-input" placeholder="MM/YY" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-form-group">
                                        <label class="payment-form-label">CVV</label>
                                        <input type="text" class="payment-form-input" placeholder="123" maxlength="3">
                                    </div>
                                </div>
                            </div>
                            <div class="payment-form-group">
                                <label class="payment-form-label">Cardholder Name</label>
                                <input type="text" class="payment-form-input" placeholder="John Doe">
                            </div>
                        </div>

                        <!-- Bank Transfer Fields (hidden by default) -->
                        <div id="bank-payment-fields" class="payment-fields" style="display: none;">
                            <div class="payment-bank-info">
                                <p><strong>Bank Name:</strong> Maybank</p>
                                <p><strong>Account Number:</strong> 1234567890</p>
                                <p><strong>Account Name:</strong> HN Supplement Sdn Bhd</p>
                                <p class="payment-bank-note">Please transfer the exact amount and include booking ID #{{ $booking->id }} in the transfer reference.</p>
                            </div>
                        </div>

                        <div class="payment-actions">
                            <a href="{{ route('trainers') }}" class="payment-cancel-btn">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="payment-submit-btn">
                                <i class="bi bi-check-circle me-2"></i>Pay RM {{ number_format($booking->total_amount, 2) }}
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
    // Payment countdown timer
    @if($booking->payment_expires_at)
        const expiresAt = new Date('{{ $booking->payment_expires_at->toIso8601String() }}').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiresAt - now;
            
            if (distance < 0) {
                document.getElementById('timer-text').innerHTML = '<strong style="color: #dc3545;">Payment time expired!</strong>';
                document.getElementById('payment-timer').classList.add('payment-timer-expired');
                
                // Disable form
                document.querySelector('.payment-form').style.pointerEvents = 'none';
                document.querySelector('.payment-form').style.opacity = '0.6';
                document.querySelector('.payment-submit-btn').disabled = true;
                
                // Redirect after 2 seconds
                setTimeout(function() {
                    window.location.href = '{{ route("trainers") }}';
                }, 2000);
                
                return;
            }
            
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            const minutesStr = minutes.toString().padStart(2, '0');
            const secondsStr = seconds.toString().padStart(2, '0');
            
            document.getElementById('countdown').textContent = minutesStr + ':' + secondsStr;
            
            // Change color when less than 30 seconds
            if (distance < 30000) {
                document.getElementById('payment-timer').classList.add('payment-timer-warning');
            }
        }
        
        // Update countdown every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    @endif

    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardFields = document.getElementById('card-payment-fields');
    const bankFields = document.getElementById('bank-payment-fields');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'card') {
                cardFields.style.display = 'block';
                bankFields.style.display = 'none';
            } else {
                cardFields.style.display = 'none';
                bankFields.style.display = 'block';
            }
        });
    });

    // Format card number
    const cardInput = document.querySelector('#card-payment-fields input[placeholder="1234 5678 9012 3456"]');
    if (cardInput) {
        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Format expiry date
    const expiryInput = document.querySelector('#card-payment-fields input[placeholder="MM/YY"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
});
</script>
@endsection
