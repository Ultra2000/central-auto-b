<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 24px;
        }
        .success-icon {
            font-size: 60px;
            text-align: center;
            margin: 20px 0;
        }
        .order-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #475569;
        }
        .value {
            color: #1e293b;
            font-weight: 500;
        }
        .plate-preview {
            background: white;
            border: 3px solid #000;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .total {
            background: #dcfce7;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #16a34a;
        }
        .info-box {
            background: #dbeafe;
            border-left: 4px solid #2563eb;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        .contact-info {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Commande Confirmée</h1>
            <p style="color: #64748b; margin: 10px 0 0 0;">
                Merci pour votre confiance !
            </p>
        </div>

        <div class="success-icon">
            🎉
        </div>

        <p style="text-align: center; font-size: 18px; color: #1e293b;">
            Bonjour <strong>{{ $order->customer_name }}</strong>,
        </p>

        <p style="text-align: center; color: #64748b;">
            Nous avons bien reçu votre commande de plaque d'immatriculation.<br>
            Voici le récapitulatif de votre commande :
        </p>

        <div class="plate-preview">
            {{ $order->plate_number }}
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #1e293b;">Détails de votre commande</h3>
            
            <div class="detail-row">
                <span class="label">Numéro de commande:</span>
                <span class="value">#{{ $order->id }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Type de plaque:</span>
                <span class="value">{{ $order->plate_type === 'definitive' ? 'Définitive' : 'Provisoire' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Numéro:</span>
                <span class="value">{{ $order->plate_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Département:</span>
                <span class="value">{{ $order->department }}</span>
            </div>
            
            @if($order->custom_text)
            <div class="detail-row">
                <span class="label">Texte personnalisé:</span>
                <span class="value">{{ $order->custom_text }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="label">Matériau:</span>
                <span class="value">{{ $order->material === 'plexiglass' ? 'Plexiglass' : 'Aluminium' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Quantité:</span>
                <span class="value">{{ $order->quantity }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Prix unitaire:</span>
                <span class="value">{{ number_format($order->unit_price, 2) }} €</span>
            </div>

            <div class="detail-row">
                <span class="label">Option choisie:</span>
                <span class="value">{{ $order->delivery_option === 'pickup' ? '🏪 Retrait en agence' : '🚚 Livraison à domicile' }}</span>
            </div>

            @if($order->delivery_option === 'delivery')
            <div class="detail-row">
                <span class="label">Adresse de livraison:</span>
                <span class="value">{{ $order->delivery_address }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Ville:</span>
                <span class="value">{{ $order->delivery_city }} {{ $order->delivery_postal_code }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Frais de livraison:</span>
                <span class="value">{{ number_format($order->delivery_cost, 2) }} €</span>
            </div>
            @endif
        </div>

        <div class="total">
            <div style="color: #15803d; font-weight: 600; margin-bottom: 5px;">Total payé</div>
            <div class="total-amount">{{ number_format($order->total_price, 2) }} €</div>
        </div>

        @if($order->delivery_option === 'pickup')
        <div class="info-box">
            <h4>📍 Retrait en agence</h4>
            <p style="margin: 5px 0;">Votre plaque sera prête sous 24-48h. Nous vous contacterons dès qu'elle sera disponible.</p>
            <p style="margin: 5px 0; font-weight: 600;">Central Auto B</p>
            <p style="margin: 5px 0; color: #475569;">Adresse de l'agence à définir</p>
        </div>
        @else
        <div class="info-box">
            <h4>🚚 Livraison à domicile</h4>
            <p style="margin: 5px 0;">Votre plaque sera expédiée sous 24-48h à l'adresse suivante :</p>
            <p style="margin: 5px 0; font-weight: 600;">
                {{ $order->delivery_address }}<br>
                {{ $order->delivery_city }} {{ $order->delivery_postal_code }}
            </p>
        </div>
        @endif

        <div class="contact-info">
            <h4 style="margin: 0 0 10px 0; color: #1e293b;">Une question ?</h4>
            <p style="margin: 5px 0;">
                📧 Email : contact@centralauto-b.com<br>
                📱 Téléphone : 0590 XX XX XX<br>
                🕐 Horaires : Lun-Ven 8h-17h, Sam 8h-12h
            </p>
        </div>

        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; color: #78350f;">
                <strong>⚠️ Important :</strong> Conservez ce mail comme preuve de votre commande. 
                Le numéro de commande <strong>#{{ $order->id }}</strong> pourra vous être demandé lors du retrait ou en cas de question.
            </p>
        </div>

        <div class="footer">
            <p style="font-size: 16px; color: #1e293b; font-weight: 600;">Merci d'avoir choisi Central Auto B !</p>
            <p>Cet email est un accusé de réception automatique, merci de ne pas y répondre.</p>
            <p style="margin: 0;">© {{ date('Y') }} Central Auto B - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
