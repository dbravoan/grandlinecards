<x-mail::message>
# ¡Felicidades, has vendido una carta!

Tu carta **{{ $orderItem->card->name }}** ha sido vendida en el Bazar.

## Resumen del Pedido
- **Carta:** {{ $orderItem->card->name }} - {{ $orderItem->card->card_id }}
- **Precio Sold:** {{ number_format($orderItem->price, 2) }} €
- **Estado:** Pendiente de Envío

Por favor, accede a tu panel de ventas para gestionar el envío al Vault de Grand Line Cards.

<x-mail::button :url="route('sales.index')">
Gestionar Mis Ventas
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
