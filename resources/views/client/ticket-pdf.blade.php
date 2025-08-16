<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $ticket->event->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #764ba2;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .event-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .ticket-body {
            padding: 40px;
        }
        
        .ticket-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .ticket-details {
            border-top: 2px dashed #dee2e6;
            padding-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
        }
        
        .detail-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .detail-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #6c757d;
        }
        
        .ticket-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        
        .footer-text {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Ticket Header -->
        <div class="ticket-header">
            <div class="event-title">{{ $ticket->event->title }}</div>
            <div class="event-subtitle">Event Ticket</div>
        </div>

        <!-- Ticket Body -->
        <div class="ticket-body">
            <!-- Event Information -->
            <div class="ticket-info">
                <div class="info-section">
                    <div class="info-label">Date & Time</div>
                    <div class="info-value">
                        {{ $ticket->event->start_date->format('F j, Y') }}<br>
                        {{ $ticket->event->start_date->format('g:i A') }}
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">Location</div>
                    <div class="info-value">{{ $ticket->event->location }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">Organizer</div>
                    <div class="info-value">{{ $ticket->event->organizer->name }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">Attendee</div>
                    <div class="info-value">{{ $ticket->client->name }}</div>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="ticket-details">
                <div class="detail-item">
                    <div class="detail-label">Ticket Code</div>
                    <div class="detail-value">{{ $ticket->ticket_code }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Quantity</div>
                    <div class="detail-value">{{ $ticket->quantity }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Total Price</div>
                    <div class="detail-value">
                        @if($ticket->total_price > 0)
                            ${{ number_format($ticket->total_price, 2) }}
                        @else
                            FREE
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div style="text-align: center; margin-top: 20px;">
                @if($ticket->payment_status === 'paid')
                    <span class="status-badge status-paid">✅ Confirmed</span>
                @elseif($ticket->payment_status === 'pending')
                    <span class="status-badge status-pending">⏳ Pending Payment</span>
                @else
                    <span class="status-badge status-unpaid">❌ Unpaid</span>
                @endif
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-placeholder">
                    QR Code<br>
                    (To be generated)
                </div>
                <div style="font-size: 12px; color: #6c757d;">
                    Present this QR code at the event entrance
                </div>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="ticket-footer">
            <div class="footer-text">
                <strong>Important Information:</strong><br>
                • Please arrive 30 minutes before the event starts<br>
                • This ticket is non-transferable and non-refundable<br>
                • Present a valid ID along with this ticket<br>
                • For questions, contact the organizer: {{ $ticket->event->organizer->email ?? 'N/A' }}<br><br>
                
                <strong>Booking Details:</strong><br>
                Booked on: {{ $ticket->created_at->format('F j, Y \a\t g:i A') }}<br>
                @if($ticket->payment)
                    Payment ID: {{ $ticket->payment->stripe_payment_intent_id ?? $ticket->payment->id }}<br>
                @endif
                
                <br>
                Generated on {{ now()->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
