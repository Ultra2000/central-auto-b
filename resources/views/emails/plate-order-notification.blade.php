<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande de plaque</title>
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
        .customer-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Nouvelle Commande de Plaque</h1>
            <p style="color: #64748b; margin: 10px 0 0 0;">
                Commande #{{ $order->id }} - {{ $order->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>

        <div style="margin: 20px 0;">
            <span class="status-badge status-pending">{{ strtoupper($order->status) }}</span>
        </div>

        <div class="plate-preview">
            {{ $order->plate_number }}
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #1e293b;">Détails de la commande</h3>
            
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
                <span class="label">Option de récupération:</span>
                <span class="value">{{ $order->delivery_option === 'pickup' ? 'Retrait en agence' : 'Livraison à domicile' }}</span>
            </div>

            @if($order->delivery_option === 'delivery')
            <div class="detail-row">
                <span class="label">Adresse de livraison:</span>
                <span class="value">{{ $order->delivery_address }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Ville:</span>
                <span class="value">{{ $order->delivery_city }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Code postal:</span>
                <span class="value">{{ $order->delivery_postal_code }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Frais de livraison:</span>
                <span class="value">{{ number_format($order->delivery_cost, 2) }} €</span>
            </div>
            @endif
        </div>

        <div class="total">
            <div style="color: #15803d; font-weight: 600; margin-bottom: 5px;">Total de la commande</div>
            <div class="total-amount">{{ number_format($order->total_price, 2) }} €</div>
        </div>

        <div class="customer-info">
            <h3 style="margin-top: 0; color: #1e293b;">Informations client</h3>
            
            <div class="detail-row">
                <span class="label">Nom:</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Email:</span>
                <span class="value">
                    <a href="mailto:{{ $order->customer_email }}" style="color: #2563eb; text-decoration: none;">
                        {{ $order->customer_email }}
                    </a>
                </span>
            </div>
            
            <div class="detail-row">
                <span class="label">Téléphone:</span>
                <span class="value">
                    <a href="tel:{{ $order->customer_phone }}" style="color: #2563eb; text-decoration: none;">
                        {{ $order->customer_phone }}
                    </a>
                </span>
            </div>

            @if($order->customer_address)
            <div class="detail-row">
                <span class="label">Adresse:</span>
                <span class="value">{{ $order->customer_address }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Cette notification a été envoyée automatiquement depuis votre système de commandes.</p>
            <p style="margin: 0;">© {{ date('Y') }} Central Auto B - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
