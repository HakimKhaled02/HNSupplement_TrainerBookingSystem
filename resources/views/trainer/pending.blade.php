@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="container-fluid" style="padding: 100px 20px 80px; background-color: #000000; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px; padding: 40px; text-align: center;">
                    <div style="font-size: 5rem; color: var(--accent-green); margin-bottom: 20px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h1 style="color: #ffffff; font-size: 2rem; font-weight: 700; margin-bottom: 20px; font-family: 'Poppins', sans-serif;">
                        Account Pending Approval
                    </h1>
                    <p style="color: #cccccc; font-size: 1.1rem; line-height: 1.8; margin-bottom: 30px; font-family: 'Poppins', sans-serif;">
                        Your trainer account is currently pending approval by our staff. 
                        You will receive an email with your login credentials once your account has been approved.
                    </p>
                    <p style="color: #888888; font-size: 0.95rem; font-family: 'Poppins', sans-serif;">
                        Please check your email regularly for updates.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

