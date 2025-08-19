<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Account Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #111827; margin: 0; padding: 20px; color: #e5e7eb;">

  <div style="max-width: 650px; margin: auto; background-color: #1f2937; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.6); overflow: hidden;">

    <!-- Header -->
    <div style="background: linear-gradient(90deg, #10b981, #3b82f6); text-align: center; padding: 30px;">
      <div style="font-size: 48px;">🎉</div>
      <h1 style="margin: 0; color: #ffffff; font-size: 26px;">Félicitations !</h1>
      <p style="margin: 8px 0 0; font-size: 16px; color: #d1fae5;">Votre compte organisateur est approuvé</p>
    </div>

    <!-- Welcome Message -->
    <div style="background: #064e3b; border-left: 4px solid #10b981; padding: 20px; margin: 20px; border-radius: 8px;">
      <h2 style="color: #10b981; margin-top: 0;">Bienvenue sur My Guichet !</h2>
      <p style="margin-bottom: 0;">
        <strong>Bonjour {{ $organizer->name }},</strong><br>
        Bonne nouvelle 🎊 Votre compte organisateur a été <span style="background: #facc15; color: #1f2937; padding: 2px 6px; border-radius: 4px; font-weight: bold;">approuvé</span> par notre équipe.  
        Vous pouvez maintenant créer et gérer vos événements !
      </p>
    </div>

    <!-- Organizer Info -->
    <div style="background: #111827; border: 1px solid #3b82f6; padding: 20px; margin: 20px; border-radius: 10px;">
      <h3 style="margin: 0 0 15px; color: #3b82f6;">📋 Vos Informations</h3>
      <table style="width: 100%; border-collapse: collapse; color: #e5e7eb;">
        <tr>
          <td style="padding: 8px; font-weight: bold;">Nom</td>
          <td>{{ $organizer->name }}</td>
        </tr>
        <tr>
          <td style="padding: 8px; font-weight: bold;">Email</td>
          <td>{{ $organizer->email }}</td>
        </tr>
        @if($organizer->profile && $organizer->profile->company_name)
        <tr>
          <td style="padding: 8px; font-weight: bold;">Entreprise</td>
          <td>{{ $organizer->profile->company_name }}</td>
        </tr>
        @endif
        @if($organizer->profile && $organizer->profile->contact_phone)
        <tr>
          <td style="padding: 8px; font-weight: bold;">Téléphone</td>
          <td>{{ $organizer->profile->contact_phone }}</td>
        </tr>
        @endif
      </table>
    </div>

    <!-- Dashboard Button -->
    <div style="text-align: center; margin: 30px 0;">
      <a href="{{ route('organizer.dashboard') }}" style="display: inline-block; background: #10b981; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">🚀 Accéder à mon Dashboard</a>
    </div>

    <!-- Features -->
    <div style="background: #1f2937; border: 1px solid #374151; padding: 20px; margin: 20px; border-radius: 10px;">
      <h3 style="margin: 0 0 15px; color: #3b82f6;">🎯 Ce que vous pouvez faire maintenant</h3>
      <ul style="margin: 0; padding-left: 20px; color: #d1d5db;">
        <li><strong>Créer des événements :</strong> Lancez votre premier événement</li>
        <li><strong>Gérer les réservations :</strong> Suivez vos ventes et vos participants</li>
        <li><strong>Analyser vos revenus :</strong> Accédez à des rapports détaillés</li>
        <li><strong>Ajouter du contenu :</strong> Images, descriptions et plus</li>
        <li><strong>Fixer vos prix :</strong> Créez des événements gratuits ou payants</li>
        <li><strong>Suivre vos performances :</strong> Consultez vos statistiques</li>
      </ul>
    </div>

    <!-- Next Steps -->
    <div style="background: #1f2937; border: 1px solid #f59e0b; padding: 20px; margin: 20px; border-radius: 10px;">
      <h3 style="margin: 0 0 10px; color: #facc15;">📝 Prochaines Étapes</h3>
      <ol style="margin: 0; padding-left: 20px; color: #fef3c7;">
        <li><strong>Complétez votre profil :</strong> Ajoutez plus d’infos</li>
        <li><strong>Créez votre premier événement :</strong> Utilisez notre assistant</li>
        <li><strong>Consultez nos règles :</strong> Familiarisez-vous avec nos guidelines</li>
        <li><strong>Explorez vos outils :</strong> Prenez en main votre dashboard</li>
      </ol>
    </div>

    <!-- Info -->
    <div style="background: #1e3a8a; border: 1px solid #3b82f6; padding: 15px; margin: 20px; border-radius: 8px;">
      <h4 style="color: #93c5fd; margin-top: 0;">ℹ️ Informations Importantes</h4>
      <ul style="margin: 0; padding-left: 20px; color: #dbeafe;">
        <li>Tous les événements nécessitent une validation</li>
        <li>Frais de plateforme : 15% des ventes</li>
        <li>Paiements sécurisés via Stripe</li>
        <li>Vous recevez 85% de vos revenus</li>
      </ul>
    </div>

    <!-- Support -->
    <div style="background: #111827; border: 1px solid #374151; padding: 20px; margin: 20px; border-radius: 8px; text-align: center;">
      <h4 style="margin-top: 0; color: #3b82f6;">Besoin d'aide ?</h4>
      <p style="margin: 10px 0; color: #d1d5db;">
        Notre équipe est disponible pour vous accompagner.  
      </p>
      <p style="margin: 0; color: #d1d5db;">
        📧 <a href="mailto:support@eventmanagement.com" style="color: #10b981;">support@eventmanagement.com</a><br>
        📞 Lundi-Vendredi, 9h - 18h
      </p>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding: 20px; font-size: 13px; color: #9ca3af; border-top: 1px solid #374151;">
      <p><strong>My Guichet</strong></p>
      <p>Merci d’avoir choisi notre plateforme. Nous avons hâte de voir vos événements 🎭</p>
      <p style="font-size: 12px; margin-top: 15px;">Ceci est un email automatique, merci de ne pas répondre.</p>
    </div>
  </div>
</body>
</html>
