<div>
    <form wire:submit.prevent="submit">
        <!-- Step 1: Account Info -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center font-bold mr-3">1</div>
                <h2 class="text-lg font-semibold text-white">Enter Account Information</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 text-sm mb-2">User ID</label>
                    <input type="text" wire:model="playerUid" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('playerUid') border-red-500 @enderror"
                        placeholder="Enter your User ID">
                    @error('playerUid')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                @if($game->slug == 'mobile-legends')
                <div>
                    <label class="block text-gray-300 text-sm mb-2">Server ID</label>
                    <input type="text" wire:model="playerServer" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('playerServer') border-red-500 @enderror"
                        placeholder="Enter Server ID">
                    @error('playerServer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>
            
            <button type="button" wire:click="validatePlayer" wire:loading.attr="disabled"
                class="mt-4 px-6 py-2 bg-zinc-800 text-gray-300 rounded-xl hover:bg-zinc-700 transition disabled:opacity-50">
                <span wire:loading.remove wire:target="validatePlayer">Validate Account</span>
                <span wire:loading wire:target="validatePlayer">Validating...</span>
            </button>
            
            @if($isValidated)
            <div class="mt-4 flex items-center space-x-2 text-green-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Account validated: <span class="font-semibold">{{ $playerName }}</span></span>
            </div>
            @endif
        </div>

        <!-- Step 2: Select Product -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center font-bold mr-3">2</div>
                <h2 class="text-lg font-semibold text-white">Select Top Up Amount</h2>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($products as $product)
                <label class="cursor-pointer">
                    <input type="radio" wire:model="selectedProductId" value="{{ $product->id }}" class="hidden peer">
                    <div class="bg-zinc-800/50 border-2 border-white/10 rounded-xl p-4 hover:border-accent-primary/50 peer-checked:border-accent-primary peer-checked:bg-accent-primary/10 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-white font-semibold">{{ $product->name }}</span>
                            @if($product->is_hot)
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">HOT</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-accent-primary font-bold">{{ formatCurrency($product->price) }}</span>
                            @if($product->original_price)
                            <span class="text-gray-500 line-through text-sm">{{ formatCurrency($product->original_price) }}</span>
                            @endif
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('selectedProductId')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Step 3: Quantity -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center font-bold mr-3">3</div>
                <h2 class="text-lg font-semibold text-white">Quantity</h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="button" wire:click="decrementQuantity" 
                    class="w-10 h-10 bg-zinc-800 rounded-xl flex items-center justify-center hover:bg-zinc-700 transition">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </button>
                <input type="number" wire:model="quantity" min="1" max="10" readonly
                    class="w-20 px-3 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white text-center">
                <button type="button" wire:click="incrementQuantity"
                    class="w-10 h-10 bg-zinc-800 rounded-xl flex items-center justify-center hover:bg-zinc-700 transition">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Step 4: Promo Code -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-gray-700 text-gray-300 rounded-full flex items-center justify-center font-bold mr-3">4</div>
                <h2 class="text-lg font-semibold text-white">Promo Code (Optional)</h2>
            </div>
            
            @if(!$appliedCoupon)
            <div class="flex space-x-2">
                <input type="text" wire:model="couponCode"
                    class="flex-1 px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                    placeholder="Enter promo code">
                <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled"
                    class="px-6 py-3 bg-zinc-800 text-gray-300 rounded-xl hover:bg-zinc-700 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="applyCoupon">Apply</span>
                    <span wire:loading wire:target="applyCoupon">Applying...</span>
                </button>
            </div>
            @else
            <div class="flex items-center justify-between p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                <div>
                    <p class="text-green-500 font-semibold">Coupon Applied!</p>
                    <p class="text-gray-400 text-sm">You saved {{ formatCurrency($discount) }}</p>
                </div>
                <button type="button" wire:click="removeCoupon" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @endif
        </div>

        <!-- Step 5: Payment Method -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center font-bold mr-3">5</div>
                <h2 class="text-lg font-semibold text-white">Payment Method</h2>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($paymentChannels as $channel)
                <label class="cursor-pointer">
                    <input type="radio" wire:model="selectedPaymentChannel" value="{{ $channel->code }}" class="hidden peer">
                    <div class="bg-zinc-800/50 border-2 border-white/10 rounded-xl p-4 hover:border-accent-primary/50 peer-checked:border-accent-primary peer-checked:bg-accent-primary/10 transition-all text-center">
                        <div class="h-8 flex items-center justify-center mb-2">
                            <span class="text-white text-sm font-medium">{{ $channel->name }}</span>
                        </div>
                        @if($channel->fee_flat > 0 || $channel->fee_percent > 0)
                        <p class="text-gray-500 text-xs">
                            Fee: {{ $channel->fee_percent > 0 ? $channel->fee_percent . '%' : '' }}
                            {{ $channel->fee_flat > 0 ? '+ ' . formatCurrency($channel->fee_flat) : '' }}
                        </p>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
            @error('selectedPaymentChannel')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <label class="block text-gray-300 text-sm mb-2">Notes (Optional)</label>
            <textarea wire:model="buyerNote" rows="3"
                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                placeholder="Additional notes for your order..."></textarea>
        </div>

        <!-- Order Summary -->
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <h3 class="text-lg font-semibold text-white mb-4">Order Summary</h3>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-white">{{ formatCurrency($subtotal) }}</span>
                </div>
                @if($discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Discount</span>
                    <span class="text-green-500">- {{ formatCurrency($discount) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Fee</span>
                    <span class="text-white">{{ formatCurrency($fee) }}</span>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-4">
                <div class="flex justify-between">
                    <span class="text-white font-semibold">Total</span>
                    <span class="text-accent-primary text-xl font-bold">{{ formatCurrency($total) }}</span>
                </div>
            </div>
            
            <button type="submit" wire:loading.attr="disabled"
                class="w-full mt-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all disabled:opacity-50">
                <span wire:loading.remove wire:target="submit">Buy Now</span>
                <span wire:loading wire:target="submit">Processing...</span>
            </button>
        </div>
    </form>
</div>