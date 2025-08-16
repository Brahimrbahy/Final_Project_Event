<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 28px;
        }
        .ticket-info {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .event-details {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .ticket-code {
            background-color: #1f2937;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 20px 0;
        }
        .important-info {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }
        .detail-item {
            padding: 10px;
            background-color: white;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .detail-label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }
        .detail-value {
            color: #1f2937;
            margin-top: 5px;
        }
        @media (max-width: 600px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎫 Ticket Confirmation</h1>
            <p>Your tickets are confirmed!</p>
        </div>

        <!-- Success Message -->
        <div style="text-align: center; margin: 20px 0;">
            <h2 style="color: #059669; margin: 0;">✅ Payment Successful</h2>
            <p style="color: #6b7280; margin: 10px 0;">
                Thank you for your purchase. Your tickets have been confirmed and are ready to use.
            </p>
        </div>

        <!-- Ticket Code -->
        <div class="ticket-code">
            {{ $ticket->ticket_code }}
        </div>
        <p style="text-align: center; color: #6b7280; font-size: 14px;">
            <strong>Important:</strong> Save this ticket code. You'll need it at the event entrance.
        </p>

        <!-- Event Information -->
        <div class="event-details">
            <h3 style="color: #1f2937; margin-top: 0;">📅 Event Details</h3>
            
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Event</div>
                    <div class="detail-value">{{ $ticket->event->title }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Date & Time</div>
                    <div class="detail-value">{{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">{{ $ticket->event->location }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">{{ $ticket->event->address ?? 'Address will be provided' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Organizer</div>
                    <div class="detail-value">{{ $ticket->event->organizer->name }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Category</div>
                    <div class="detail-value">{{ $ticket->event->category }}</div>
                </div>
            </div>
        </div>

        <!-- Ticket Information -->
        <div class="ticket-info">
            <h3 style="color: #1f2937; margin-top: 0;">🎟️ Your Tickets</h3>
            
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Quantity</div>
                    <div class="detail-value">{{ $ticket->quantity }} ticket(s)</div>
                </div>
                
                @if($ticket->event->type === 'paid')
                <div class="detail-item">
                    <div class="detail-label">Total Paid</div>
                    <div class="detail-value">
                        @if($ticket->payment && $ticket->payment->total_amount)
                            ${{ number_format($ticket->payment->total_amount, 2) }}
                        @else
                            ${{ number_format($ticket->total_price * 1.05, 2) }}
                        @endif
                    </div>
                </div>
                @else
                <div class="detail-item">
                    <div class="detail-label">Price</div>
                    <div class="detail-value">FREE</div>
                </div>
                @endif
                
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value" style="color: #059669; font-weight: bold;">✅ CONFIRMED</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Purchase Date</div>
                    <div class="detail-value">{{ $ticket->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Important Information -->
        <div class="important-info">
            <h4 style="margin-top: 0; color: #92400e;">⚠️ Important Information</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Bring this email or save your ticket code: <strong>{{ $ticket->ticket_code }}</strong></li>
                <li>Arrive at least 15 minutes before the event starts</li>
                <li>Valid photo ID may be required for entry</li>
                @if($ticket->event->terms_conditions)
                <li>{{ $ticket->event->terms_conditions }}</li>
                @endif
            </ul>
        </div>

        <!-- Action Buttons -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('client.ticket-details', $ticket) }}" class="button">
                🎫 View Ticket Details
            </a>
            <br>
            <a href="{{ route('client.download-ticket', $ticket) }}" class="button" style="background-color: #059669; margin-top: 10px;">
                📥 Download Ticket PDF
            </a>
            <br>
            <a href="{{ route('events.show', $ticket->event) }}" class="button" style="background-color: #6b7280; margin-top: 10px;">
                📋 View Event Details
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Event Management System</strong></p>
            <p>
                Need help? Contact us at 
                <a href="mailto:support@eventmanagement.com" style="color: #3b82f6;">support@eventmanagement.com</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
