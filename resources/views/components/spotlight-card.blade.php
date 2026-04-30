@props(['class' => '', 'spotlightColor' => 'rgba(229, 9, 20, 0.15)'])

<div class="spotlight-card relative overflow-hidden {{ $class }}" data-spotlight-color="{{ $spotlightColor }}">
    <div class="spotlight-gradient pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 rounded-[inherit]"></div>
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.spotlight-card').forEach(card => {
        const gradient = card.querySelector('.spotlight-gradient');
        const color = card.dataset.spotlightColor;

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            gradient.style.background = `radial-gradient(600px circle at ${x}px ${y}px, ${color}, transparent 40%)`;
            gradient.style.opacity = '1';
        });

        card.addEventListener('mouseleave', () => {
            gradient.style.opacity = '0';
        });
    });
});
</script>
@endpush
@endonce
