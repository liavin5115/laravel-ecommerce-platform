@props(['product'])

<div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-gray-100 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300">
    <!-- Image Skeleton/Placeholder -->
    <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
        @if($product->images->count() > 0)
            <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out" />
        @else
            <!-- Placeholder -->
            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <!-- Glassmorphism overlay on placeholder for effect -->
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/5 to-purple-500/5 mix-blend-overlay"></div>
        @endif
        
        <!-- Quick Action Add to Cart button -->
        <div class="absolute bottom-4 left-0 right-0 px-4 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 z-20">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $product->variants->first()?->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full py-2.5 bg-white/90 backdrop-blur-md text-gray-900 font-medium rounded-xl shadow-lg hover:bg-white transition-colors">
                    Quick Add
                </button>
            </form>
        </div>
    </div>

    <!-- Details -->
    <div class="p-5 flex flex-col flex-1">
        <div class="flex justify-between items-start gap-4">
            <div>
                <h3 class="font-semibold text-gray-900 text-lg line-clamp-1">
                    <a href="{{ route('public.products.show', $product->slug) }}">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ $product->name }}
                    </a>
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $product->store?->name ?? 'Unknown Store' }}
                </p>
            </div>
            <p class="text-lg font-bold text-gray-900 shrink-0">
                ${{ number_format($product->price, 2) }}
            </p>
        </div>
    </div>
</div>
