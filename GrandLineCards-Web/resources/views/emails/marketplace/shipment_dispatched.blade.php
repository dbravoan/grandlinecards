<x-mail::message>
# ¡Tu pedido está en camino!

Tu envío consolidado **#{{ $shipment->tracking_number }}** ha salido de nuestro Vault.

## Detalles del Envío
- **Dirección de Entrega:**
{{ $shipment->shipping_address }}

- **Número de Seguimiento:** {{ $shipment->tracking_number }}
- **Transportista:** Correos (Simulado)

<x-mail::button :url="route('shipments.index')">
Ver Mis Pedidos
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
