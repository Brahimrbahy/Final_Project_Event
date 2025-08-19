<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Confirmation</title>
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #111827; margin: 0; padding: 20px; color: #e5e7eb;">

  <div style="max-width: 650px; margin: auto; background-color: #1f2937; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.6); overflow: hidden;">

    <!-- Header -->
    <div style="background: linear-gradient(90deg, #10b981, #3b82f6); text-align: center; padding: 25px;">
      <h1 style="margin: 0; color: #ffffff; font-size: 28px;">🎫 My Guichet</h1>
      <p style="margin: 8px 0 0; font-size: 16px; color: #d1fae5;">Votre ticket est confirmé ✅</p>
    </div>

    <!-- Success -->
    <div style="padding: 25px; text-align: center;">
      <h2 style="color: #10b981; margin: 0;">Paiement Réussi</h2>
      <p style="color: #9ca3af; margin: 10px 0;">
        Merci pour votre achat ! Votre ticket est prêt à être utilisé.
      </p>
    </div>

    <!-- Ticket Code -->
    <div style="background: #111827; color: #10b981; padding: 18px; text-align: center; font-family: 'Courier New', monospace; font-size: 24px; font-weight: bold; letter-spacing: 2px; border: 1px solid #3b82f6; border-radius: 8px; margin: 20px;">
      {{ $ticket->ticket_code }}
    </div>
    <p style="text-align: center; color: #9ca3af; font-size: 14px;">
      <strong>Important :</strong> Gardez ce code, il est nécessaire pour accéder à l’événement.
    </p>

    <!-- Event Details -->
    <div style="background: #111827; border: 1px solid #3b82f6; padding: 20px; margin: 20px; border-radius: 10px;">
      <h3 style="margin: 0 0 15px; color: #3b82f6;">📅 Détails de l'Événement</h3>
      <table style="width: 100%; border-collapse: collapse; color: #e5e7eb;">
        <tr>
          <td style="padding: 8px; font-weight: bold;">Événement</td>
          <td>{{ $ticket->event->title }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Date & Heure</td>
          <td>{{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Lieu</td>
          <td>{{ $ticket->event->location }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Adresse</td>
          <td>{{ $ticket->event->address ?? 'Adresse communiquée ultérieurement' }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Organisateur</td>
          <td>{{ $ticket->event->organizer->name }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Catégorie</td>
          <td>{{ $ticket->event->category }}</td>
        </tr>
      </table>
    </div>

    <!-- Ticket Info -->
    <div style="background: #111827; border-left: 4px solid #10b981; padding: 20px; margin: 20px; border-radius: 10px;">
      <h3 style="margin: 0 0 15px; color: #10b981;">🎟️ Vos Tickets</h3>
      <table style="width: 100%; border-collapse: collapse; color: #e5e7eb;">
        <tr>
          <td style="padding: 8px; font-weight: bold;">Quantité</td>
          <td>{{ $ticket->quantity }} ticket(s)</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Prix</td>
          <td>
            @if($ticket->event->type === 'paid')
              @if($ticket->payment && $ticket->payment->total_amount)
                ${{ number_format($ticket->payment->total_amount, 2) }}
              @else
                ${{ number_format($ticket->total_price * 1.05, 2) }}
              @endif
            @else
              GRATUIT
            @endif
          </td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Statut</td>
          <td style="color: #10b981; font-weight: bold;">✅ CONFIRMÉ</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Date d’Achat</td>
          <td>{{ $ticket->created_at->format('F j, Y \a\t g:i A') }}</td>
        </tr>
      </table>
    </div>

    <!-- Important Info -->
    <div style="background: #1f2937; border: 1px solid #f59e0b; padding: 15px; margin: 20px; border-radius: 8px;">
      <h4 style="margin: 0 0 10px; color: #fbbf24;">⚠️ Infos Importantes</h4>
      <ul style="padding-left: 20px; margin: 0; color: #e5e7eb;">
        <li>Présentez ce mail ou le code : <strong>{{ $ticket->ticket_code }}</strong></li>
        <li>Arrivez 15 min avant le début</li>
        <li>Une pièce d’identité peut être demandée</li>
        @if($ticket->event->terms_conditions)
          <li>{{ $ticket->event->terms_conditions }}</li>
        @endif
      </ul>
    </div>

    <!-- Action Buttons -->
    <div style="text-align: center; padding: 25px;">
      <a href="{{ route('client.ticket-details', $ticket) }}" style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 5px;">🎫 Voir Ticket</a>
      <a href="{{ route('client.download-ticket', $ticket) }}" style="display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 5px;">📥 Télécharger PDF</a>
      <a href="{{ route('events.show', $ticket->event) }}" style="display: inline-block; background: #6b7280; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 5px;">📋 Détails Événement</a>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding: 20px; font-size: 13px; color: #9ca3af; border-top: 1px solid #374151;">
      <p><strong>My Guichet</strong></p>
      <p>Besoin d’aide ? <a href="mailto:support@eventmanagement.com" style="color: #3b82f6;">support@eventmanagement.com</a></p>
      <p style="font-size: 12px; margin-top: 15px;">Ceci est un email automatique, merci de ne pas répondre.</p>
    </div>
  </div>
</body>
</html>
