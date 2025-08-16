# Stripe Hosted Checkout Integration Guide

## 🎯 **Overview**

This guide explains the Stripe hosted checkout integration that replaces the custom payment form with Stripe's secure hosted checkout page.

## 🔧 **Integration Features**

### **✅ Implemented Features:**

1. **Stripe Checkout Sessions**
   - Redirects users to Stripe's hosted checkout page
   - Secure payment processing with Stripe's PCI-compliant infrastructure
   - Automatic handling of payment methods (cards, digital wallets)
   - Session expiration (30 minutes)

2. **Database Integration**
   - New `stripe_session_id` field in tickets table
   - New `payment_id` foreign key in tickets table
   - Enhanced payments table with session tracking
   - Payment status tracking: `pending`, `paid`, `unpaid`, `refunded`

3. **Email Confirmation System**
   - Automatic email sending after successful payment
   - Professional HTML email template with ticket details
   - QR code placeholder for future implementation
   - Download links for ticket PDF

4. **Webhook Handling**
   - Processes `checkout.session.completed` events
   - Updates ticket and payment records automatically
   - Handles payment failures gracefully
   - Comprehensive logging for debugging

## 🚀 **Payment Flow**

### **1. User Journey:**
```
1. User selects event and quantity
2. Clicks "Proceed to Payment"
3. Redirected to Stripe Checkout (hosted)
4. Completes payment on Stripe
5. Redirected back to success page
6. Receives confirmation email
7. Can download ticket PDF
```

### **2. Technical Flow:**
```
1. ClientController::processBooking() creates ticket
2. PaymentController::checkout() creates Stripe Session
3. User redirected to Stripe hosted checkout
4. Stripe processes payment
5. Webhook receives checkout.session.completed
6. PaymentController::handleCheckoutSessionCompleted()
7. Payment record created, ticket marked as paid
8. Email confirmation sent
```

## 🔑 **Configuration**

### **Environment Variables:**
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

### **Webhook Endpoint:**
```
POST /stripe/webhook
```

### **Required Stripe Events:**
- `checkout.session.completed`
- `payment_intent.succeeded` (backup)
- `payment_intent.payment_failed`

## 💰 **Pricing Structure**

### **Platform Fee:**
- **5% platform fee** added to ticket price
- Example: $20 ticket = $21 total ($20 + $1 platform fee)
- Fee breakdown stored in payment record

### **Free Events:**
- No payment processing required
- Immediate ticket confirmation
- Email sent directly after booking

## 📧 **Email System**

### **Confirmation Email Includes:**
- Ticket code (unique identifier)
- Event details (date, time, location)
- Ticket quantity and pricing
- Download links for PDF ticket
- Important event information
- QR code placeholder

### **Email Template:**
- Professional HTML design
- Mobile-responsive layout
- Branded with event management system
- Clear call-to-action buttons

## 🧪 **Testing Guide**

### **Test Payment Flow:**

1. **Setup Test Environment:**
   ```bash
   # Use Stripe test keys
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   ```

2. **Test Card Numbers:**
   ```
   Success: 4242 4242 4242 4242
   Decline: 4000 0000 0000 0002
   Insufficient Funds: 4000 0000 0000 9995
   ```

3. **Test Scenarios:**
   - ✅ Successful payment
   - ❌ Failed payment
   - 🔄 Payment cancellation
   - 📧 Email delivery
   - 🎫 Ticket generation

### **Webhook Testing:**

1. **Use Stripe CLI:**
   ```bash
   stripe listen --forward-to localhost:8000/stripe/webhook
   ```

2. **Test Events:**
   ```bash
   stripe trigger checkout.session.completed
   ```

3. **Verify Logs:**
   - Check Laravel logs for webhook processing
   - Verify database updates
   - Confirm email sending

## 🔍 **Debugging**

### **Common Issues:**

1. **Webhook Not Receiving Events:**
   - Check webhook endpoint URL
   - Verify webhook secret
   - Check Stripe dashboard for delivery attempts

2. **Payment Not Updating:**
   - Check webhook signature verification
   - Verify metadata in Stripe session
   - Check Laravel logs for errors

3. **Email Not Sending:**
   - Check mail configuration
   - Verify email queue processing
   - Check email logs

### **Log Locations:**
```
storage/logs/laravel.log
```

### **Debug Commands:**
```bash
# Check webhook events
php artisan tinker
>>> \App\Models\Payment::latest()->first()

# Test email sending
>>> Mail::to('test@example.com')->send(new \App\Mail\TicketConfirmation($ticket))
```

## 🛡️ **Security Features**

### **Stripe Security:**
- PCI DSS Level 1 compliance
- Secure hosted checkout page
- No sensitive card data touches your server
- Webhook signature verification

### **Application Security:**
- CSRF protection on all forms
- User authentication required
- Ticket ownership verification
- Session expiration handling

## 📊 **Database Schema**

### **Tickets Table Updates:**
```sql
ALTER TABLE tickets ADD COLUMN stripe_session_id VARCHAR(255) NULL;
ALTER TABLE tickets ADD COLUMN payment_id BIGINT UNSIGNED NULL;
ALTER TABLE tickets MODIFY payment_status ENUM('paid','unpaid','pending','refunded');
```

### **Payments Table Updates:**
```sql
ALTER TABLE payments ADD COLUMN stripe_session_id VARCHAR(255) NULL;
ALTER TABLE payments ADD COLUMN total_amount DECIMAL(10,2) NULL;
ALTER TABLE payments ADD COLUMN payment_method VARCHAR(255) NULL;
ALTER TABLE payments ADD COLUMN processed_at TIMESTAMP NULL;
```

## 🔄 **Migration Commands**

```bash
# Run new migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback --step=2
```

## 📈 **Monitoring**

### **Key Metrics to Track:**
- Payment success rate
- Webhook delivery success
- Email delivery rate
- Session completion rate
- Average payment time

### **Stripe Dashboard:**
- Monitor payment volume
- Track failed payments
- Review webhook delivery logs
- Analyze payment methods used

## 🚨 **Error Handling**

### **Payment Failures:**
- User redirected to cancel page
- Clear error messages displayed
- Ticket reservation maintained for retry
- Automatic cleanup of expired sessions

### **Webhook Failures:**
- Automatic retry by Stripe
- Manual processing capability
- Comprehensive error logging
- Admin notification system

## 🎯 **Next Steps**

### **Recommended Enhancements:**
1. **QR Code Generation** - Add actual QR codes to tickets
2. **Refund Processing** - Implement automated refunds
3. **Payment Analytics** - Add revenue reporting
4. **Multi-currency** - Support international payments
5. **Subscription Events** - Add recurring event payments

### **Production Checklist:**
- [ ] Replace test keys with live keys
- [ ] Configure production webhook endpoint
- [ ] Set up monitoring and alerts
- [ ] Test email delivery in production
- [ ] Configure backup payment methods
- [ ] Set up customer support processes

---

## 📞 **Support**

For technical issues or questions about the Stripe integration:
- Check Laravel logs first
- Review Stripe dashboard for payment details
- Test webhook delivery manually
- Contact development team with specific error messages

**Happy Payment Processing! 💳✨**
