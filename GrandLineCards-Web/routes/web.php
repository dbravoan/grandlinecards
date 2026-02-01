<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Front\CatalogController;
use App\Http\Controllers\Web\Front\ShopController;
use App\Http\Controllers\Web\Front\ContentController;
use App\Http\Controllers\Web\Front\AcademyController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Frontoffice
Route::prefix('/')->group(function () {
    Route::controller(CatalogController::class)->group(function () {
        Route::get('/cartas', 'index')->name('catalog.index');
        Route::get('/cartas/set/{expansion}', 'index')->name('catalog.index.set');
        Route::get('/cartas/color/{color}', 'index')->name('catalog.index.color');
        
        // Smart Catch-All for Filters (e.g. /cartas/op01/rojo)
        // Must come BEFORE 'show' if 'show' is generic, OR AFTER if 'show' is strict.
        // We make 'show' strict (requiring hyphen) so we can put smart route after? 
        // Or put strict 'show' first.
        
        Route::get('/cartas/{card}', 'show')
            ->name('catalog.show')
            ->where('card', '.*-.*'); // Enforce hyphen for Card IDs (e.g. OP01-001)

        Route::get('/cartas/{slug}', 'smartIndex')
            ->name('catalog.smart_index')
            ->where('slug', '.*'); // Catch everything else
    });

    Route::get('/tienda', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/noticias', [ContentController::class, 'index'])->name('content.index');

    // Blog Post Detail
    Route::get('/noticias/{post:slug}', [ContentController::class, 'show'])->name('content.show');
    
    // Tag Landing Page
    Route::get('/etiqueta/{tag:slug}', [App\Http\Controllers\Web\Front\TagController::class, 'show'])->name('tags.show');

    // Academy
    Route::get('/academia', [AcademyController::class, 'index'])->name('academy.index');

    // Meta
    Route::get('/meta', [\App\Http\Controllers\Web\Front\MetaController::class, 'index'])->name('meta.index');

    // Rules Cheat Sheet
    Route::get('/reglas', [App\Http\Controllers\Web\Front\RulesController::class, 'index'])->name('rules.index');

    // Log Pose (Events/Calendar)
    Route::get('/eventos', [App\Http\Controllers\Web\Front\EventController::class, 'index'])->name('events.index');

    // Marketing
    Route::get('/nosotros', function () {
        return Inertia::render('Marketing/About');
    })->name('marketing.about');
});

Route::middleware([
    'auth:web',
    'verified',
])->prefix('profile')->name('profile.')->group(function () {
    Route::controller(App\Http\Controllers\Web\Front\Profile\SalesController::class)->group(function () {
        Route::get('/ventas', 'index')->name('sales.index');
        Route::post('/ventas', 'store')->name('sales.store');
        Route::delete('/ventas/{listing}', 'destroy')->name('sales.destroy');
    });

    Route::controller(App\Http\Controllers\Web\Front\Profile\VaultShipmentController::class)->group(function () {
        Route::get('/envios', 'index')->name('shipments.index');
        Route::post('/envios', 'store')->name('shipments.store');
        Route::get('/envios/{shipment}/label', 'downloadLabel')->name('shipments.label');
    });

    Route::controller(App\Http\Controllers\Web\Front\Profile\CustomerShipmentController::class)->group(function () {
        Route::get('/recibos', 'index')->name('incoming.index');
        Route::post('/recibos', 'store')->name('incoming.store');
        Route::get('/recibos/{shipment}/label', 'downloadLabel')->name('incoming.label');
    });

    Route::controller(App\Http\Controllers\Web\Front\Profile\AddressController::class)->group(function () {
        Route::get('/direcciones', 'index')->name('addresses.index');
        Route::post('/direcciones', 'store')->name('addresses.store');
        Route::put('/direcciones/{address}', 'update')->name('addresses.update');
        Route::delete('/direcciones/{address}', 'destroy')->name('addresses.destroy');
    });

    Route::controller(App\Http\Controllers\Web\Front\Profile\CollectionController::class)->group(function () {
        Route::get('/coleccion', 'index')->name('collection.index');
        Route::post('/coleccion', 'update')->name('collection.update');
    });





    Route::get('/', function () {
        return Inertia::render('Profile/Dashboard');
    })->name('dashboard');

    Route::get('/mazos', function () {
        return Inertia::render('Academy/DeckBuilder');
    })->name('decks.index');
});

Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::post('/checkout/init', [App\Http\Controllers\MarketplaceController::class, 'checkout'])->name('marketplace.checkout');

    // Payments
    Route::controller(App\Http\Controllers\PaymentController::class)->group(function () {
        Route::post('/checkout/{order}', 'checkout')->name('payment.checkout');
        Route::get('/pago/exito', 'success')->name('payment.success');
        Route::get('/pago/cancelado', 'cancel')->name('payment.cancel');
    });
    // Auctions
    Route::post('/auctions/{listing}/bid', [\App\Http\Controllers\Web\Front\AuctionController::class, 'bid'])->name('auctions.bid');
});

// Backoffice
// Backoffice
Route::prefix('dbadmin')->name('admin.')->group(function () {
    // Admin Auth
    Route::middleware('guest')->group(function () {
        Route::get('login', [App\Http\Controllers\Web\Admin\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('login', [App\Http\Controllers\Web\Admin\Auth\AuthenticatedSessionController::class, 'store'])
            ->name('login.store');
    });

    // Protected Admin Routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::post('logout', [App\Http\Controllers\Web\Admin\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::get('/', function () {
            // If accessing /dbadmin directly, redirect to dashboard or login handled by middleware/auth
            return to_route('admin.dashboard'); 
        });

        Route::get('/dashboard', [App\Http\Controllers\Web\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('posts', App\Http\Controllers\Web\Admin\PostController::class);
        Route::post('/posts/generate-excerpt', [App\Http\Controllers\Web\Admin\PostController::class, 'generateExcerpt'])->name('posts.generate-excerpt');
        Route::resource('event_suggestions', App\Http\Controllers\Web\Admin\EventSuggestionController::class);

        Route::get('/traducciones', [App\Http\Controllers\Web\Admin\TranslationController::class, 'index'])->name('translations.index');

        Route::controller(App\Http\Controllers\Web\Admin\InventoryController::class)->group(function () {
            Route::get('/inventario', 'index')->name('inventory.index');
            Route::put('/inventario/{card}', 'update')->name('inventory.update');
        });

        Route::controller(App\Http\Controllers\Web\Admin\CategoryController::class)->group(function () {
            Route::get('/taxonomias', 'index')->name('taxonomies.index');
            Route::post('/categorias', 'store')->name('categories.store');
            Route::put('/categorias/{category}', 'update')->name('categories.update');
            Route::delete('/categorias/{category}', 'destroy')->name('categories.destroy');
        });

        Route::controller(App\Http\Controllers\Web\Admin\TagController::class)->group(function () {
            Route::post('/etiquetas', 'store')->name('tags.store');
            Route::put('/etiquetas/{tag}', 'update')->name('tags.update');
            Route::delete('/etiquetas/{tag}', 'destroy')->name('tags.destroy');
        });

        Route::controller(App\Http\Controllers\Web\Admin\ScraperController::class)->group(function () {
            Route::get('/rastreador', 'index')->name('scraper.index');
            Route::post('/rastreador/run', 'run')->name('scraper.run');
        });

        Route::controller(App\Http\Controllers\Web\Admin\LogisticsController::class)->group(function () {
            Route::get('/logistica', 'index')->name('logistics.index');
            Route::post('/logistica/recibir/{shipment}', 'receive')->name('logistics.receive');
            Route::post('/logistica/enviar/{user}', 'createCustomerShipment')->name('logistics.ship');
        });

        Route::controller(App\Http\Controllers\Web\Admin\UserController::class)->group(function () {
            Route::get('/usuarios', 'index')->name('users.index');
            Route::post('/usuarios/{user}/ban', 'toggleBan')->name('users.ban');
            Route::put('/usuarios/{user}/role', 'updateRole')->name('users.role');
        });
    });
});

// Socialite Auth
Route::controller(App\Http\Controllers\Auth\SocialiteController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirect')->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('socialite.callback');
});

// Stripe Webhook (outside auth middleware)
Route::post('/webhook/stripe', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
