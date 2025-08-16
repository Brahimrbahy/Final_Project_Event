<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Account Approved</title>
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
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 28px;
        }
        .celebration {
            text-align: center;
            font-size: 48px;
            margin: 20px 0;
        }
        .welcome-message {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .organizer-info {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #059669;
        }
        .features-list {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .features-list li {
            margin: 8px 0;
            color: #374151;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .highlight {
            background-color: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="celebration">🎉</div>
            <h1>Congratulations!</h1>
            <p>Your organizer account has been approved</p>
        </div>

        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2 style="color: #059669; margin-top: 0;">Welcome to the Event Management Platform!</h2>
            <p style="margin-bottom: 0;">
                <strong>Hi {{ $organizer->name }},</strong><br>
                Great news! Your organizer account has been reviewed and <span class="highlight">approved</span> by our admin team. 
                You can now start creating and managing amazing events on our platform.
            </p>
        </div>

        <!-- Organizer Information -->
        <div class="organizer-info">
            <h3 style="color: #1f2937; margin-top: 0;">📋 Your Account Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <strong>Name:</strong><br>
                    {{ $organizer->name }}
                </div>
                <div>
                    <strong>Email:</strong><br>
                    {{ $organizer->email }}
                </div>
                @if($organizer->profile && $organizer->profile->company_name)
                <div>
                    <strong>Company:</strong><br>
                    {{ $organizer->profile->company_name }}
                </div>
                @endif
                @if($organizer->profile && $organizer->profile->contact_phone)
                <div>
                    <strong>Phone:</strong><br>
                    {{ $organizer->profile->contact_phone }}
                </div>
                @endif
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('organizer.dashboard') }}" class="button">
                🚀 Access Your Dashboard
            </a>
        </div>

        <!-- What You Can Do Now -->
        <div class="features-list">
            <h3 style="color: #1f2937; margin-top: 0;">🎯 What You Can Do Now</h3>
            <ul>
                <li><strong>Create Events:</strong> Start planning your first event with our easy-to-use event creation tools</li>
                <li><strong>Manage Bookings:</strong> Track ticket sales and manage attendee information</li>
                <li><strong>Monitor Revenue:</strong> View detailed analytics and revenue reports for your events</li>
                <li><strong>Upload Media:</strong> Add images and descriptions to make your events more attractive</li>
                <li><strong>Set Pricing:</strong> Create both free and paid events with flexible pricing options</li>
                <li><strong>Track Performance:</strong> Access insights about your events and audience engagement</li>
            </ul>
        </div>

        <!-- Next Steps -->
        <div class="next-steps">
            <h3 style="color: #92400e; margin-top: 0;">📝 Next Steps</h3>
            <ol style="margin: 0; padding-left: 20px;">
                <li><strong>Complete Your Profile:</strong> Add more details about your company and services</li>
                <li><strong>Create Your First Event:</strong> Use our event creation wizard to get started</li>
                <li><strong>Review Guidelines:</strong> Familiarize yourself with our event policies and best practices</li>
                <li><strong>Explore Features:</strong> Take a tour of your dashboard and available tools</li>
            </ol>
        </div>

        <!-- Important Information -->
        <div style="background-color: #dbeafe; border: 1px solid #3b82f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h4 style="color: #1e40af; margin-top: 0;">ℹ️ Important Information</h4>
            <ul style="margin: 0; padding-left: 20px; color: #1e3a8a;">
                <li>All events require admin approval before going live</li>
                <li>Platform fee: 15% of ticket sales (automatically calculated)</li>
                <li>Payments are processed securely through Stripe</li>
                <li>You'll receive 85% of ticket revenue after platform fees</li>
            </ul>
        </div>

        <!-- Support Information -->
        <div class="contact-info">
            <h4 style="margin-top: 0; color: #374151;">Need Help Getting Started?</h4>
            <p style="margin: 10px 0;">
                Our support team is here to help you succeed. Don't hesitate to reach out if you have any questions.
            </p>
            <p style="margin: 0;">
                📧 <a href="mailto:support@eventmanagement.com" style="color: #3b82f6;">support@eventmanagement.com</a><br>
                📞 Support available Monday-Friday, 9 AM - 6 PM
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Event Management System</strong></p>
            <p>
                Thank you for choosing our platform to manage your events. We're excited to see what amazing experiences you'll create!
            </p>
            <p style="margin-top: 20px; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
