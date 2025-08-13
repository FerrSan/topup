#!/usr/bin/env bash
set -euo pipefail

echo "==> Membuat direktori…"

DIRS=(
app/Console/Commands
app/Contracts
app/Enums
app/Exceptions
app/Http/Controllers/Admin
app/Http/Controllers
app/Http/Middleware
app/Http/Requests
app/Jobs
app/Models
app/Providers
app/Services/Payments/Drivers
app/Services/Topup/Drivers
bootstrap
config
database/migrations
database/seeders
resources/js/Components
resources/js/Layouts
resources/js/Pages/Admin/Games
resources/js/Pages/Admin/Products
resources/js/Pages/Admin/Orders
resources/js/Pages/Admin/Webhooks
resources/js/Pages/Games
resources/js/Pages/Invoice
resources/js/Pages/Track
resources/views
routes
docker/nginx
)

for d in "${DIRS[@]}"; do
  mkdir -p "$d"
done

echo "==> Membuat file kosong…"

EMPTY_FILES=(
app/Console/Commands/ExpireOrders.php
app/Console/Commands/SyncProviderPrices.php
app/Console/Commands/PollProviderStatus.php
app/Contracts/PaymentGatewayInterface.php
app/Contracts/TopupProviderInterface.php
app/Enums/OrderStatus.php
app/Enums/PaymentStatus.php
app/Enums/RefundStatus.php
app/Exceptions/PaymentException.php
app/Exceptions/TopupException.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/GameController.php
app/Http/Controllers/Admin/ProductController.php
app/Http/Controllers/Admin/PriceTierController.php
app/Http/Controllers/Admin/OrderController.php
app/Http/Controllers/Admin/PaymentController.php
app/Http/Controllers/Admin/WebhookLogController.php
app/Http/Controllers/Admin/BannerController.php
app/Http/Controllers/Admin/ArticleController.php
app/Http/Controllers/Admin/UserController.php
app/Http/Controllers/CheckoutController.php
app/Http/Controllers/GameController.php
app/Http/Controllers/InvoiceController.php
app/Http/Controllers/TrackingController.php
app/Http/Controllers/WebhookController.php
app/Http/Requests/CheckoutRequest.php
app/Http/Requests/TrackingRequest.php
app/Jobs/ProcessFulfillment.php
app/Jobs/ProcessRefund.php
app/Jobs/SendNotification.php
app/Models/User.php
app/Models/Wallet.php
app/Models/WalletTransaction.php
app/Models/Game.php
app/Models/GameProduct.php
app/Models/PriceTier.php
app/Models/ProductRequirement.php
app/Models/Order.php
app/Models/Payment.php
app/Models/Refund.php
app/Models/WebhookLog.php
app/Models/Banner.php
app/Models/Article.php
app/Services/Payments/Drivers/MidtransGateway.php
app/Services/Payments/Drivers/XenditGateway.php
app/Services/Payments/Drivers/TripayGateway.php
app/Services/Topup/Drivers/DigiflazzProvider.php
app/Services/Topup/Drivers/ApigamesProvider.php
database/migrations/2025_01_01_000001_create_users_table.php
database/migrations/2025_01_01_000002_create_wallets_table.php
database/migrations/2025_01_01_000003_create_wallet_transactions_table.php
database/migrations/2025_01_01_000004_create_games_table.php
database/migrations/2025_01_01_000005_create_game_products_table.php
database/migrations/2025_01_01_000006_create_price_tiers_table.php
database/migrations/2025_01_01_000007_create_product_requirements_table.php
database/migrations/2025_01_01_000008_create_orders_table.php
database/migrations/2025_01_01_000009_create_payments_table.php
database/migrations/2025_01_01_000010_create_refunds_table.php
database/migrations/2025_01_01_000011_create_webhook_logs_table.php
database/migrations/2025_01_01_000012_create_banners_table.php
database/migrations/2025_01_01_000013_create_articles_table.php
resources/js/Components/Navbar.vue
resources/js/Components/GameCard.vue
resources/js/Components/ProductCard.vue
resources/js/Components/PaymentModal.vue
resources/js/Components/InvoiceStatus.vue
resources/js/Layouts/AppLayout.vue
resources/js/Layouts/AdminLayout.vue
resources/js/Pages/Admin/Dashboard.vue
resources/js/Pages/Admin/Games/Index.vue
resources/js/Pages/Admin/Games/Edit.vue
resources/js/Pages/Admin/Products/Index.vue
resources/js/Pages/Admin/Products/Edit.vue
resources/js/Pages/Admin/Orders/Index.vue
resources/js/Pages/Admin/Orders/Show.vue
resources/js/Pages/Admin/Webhooks/Index.vue
resources/js/Pages/Home.vue
resources/js/Pages/Games/Index.vue
resources/js/Pages/Games/Show.vue
resources/js/Pages/Invoice/Show.vue
resources/js/Pages/Track/Index.vue
resources/views/app.blade.php
docker/Dockerfile
docker/docker-compose.yml
docker/nginx/default.conf
.env.example
composer.json
package.json
tailwind.config.js
vite.config.js
README.md
)

for f in "${EMPTY_FILES[@]}"; do
  [[ -f "$f" ]] || { touch "$f"; echo "buat $f"; }
done

echo "==> Selesai (in-place)."
