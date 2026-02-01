<?php

namespace App\Services\Logistics;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\VaultShipment;
use App\Models\CustomerShipment;

class LabelService
{
    public function generateVaultLabel(VaultShipment $shipment)
    {
        $user = $shipment->user;
        
        // Sending TO Vault
        $data = [
            'sender' => [
                'name' => $user->name,
                'address' => 'Dirección del Vendedor', // Should query user address
                'city' => '',
                'postal_code' => ''
            ],
            'receiver' => null, // Ends up as Vault Default
            'tracking_number' => $shipment->tracking_number,
            'carrier' => $shipment->carrier,
            'items' => $shipment->items,
        ];

        $pdf = Pdf::loadView('pdf.shipping-label', $data);
        return $pdf->download('etiqueta-envio-' . $shipment->id . '.pdf');
    }

    public function generateCustomerLabel(CustomerShipment $shipment)
    {
        $user = $shipment->user;
        $address = json_decode($shipment->shipping_address, true);

        // Sending FROM Vault TO Customer
        $data = [
            'sender' => null, // Vault Default
            'receiver' => [
                'name' => $user->name,
                'address' => $address['line1'] ?? $address['line_1'] ?? 'N/A',
                'city' => $address['city'] ?? '',
                'postal_code' => $address['postal_code'] ?? ''
            ],
            // If we don't have tracking yet, use shipment ID
            'tracking_number' => $shipment->tracking_number ?? 'PENDING-' . $shipment->id,
            'carrier' => 'Correos (Default)',
            'items' => $shipment->items,
        ];

        $pdf = Pdf::loadView('pdf.shipping-label', $data);
        return $pdf->download('albaran-entrega-' . $shipment->id . '.pdf');
    }
}
