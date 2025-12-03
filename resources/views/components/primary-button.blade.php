<button type="submit"
    {{ $attributes->merge([
        'class' =>
        'inline-flex items-center px-4 py-2 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 transition'
    ]) }}>
    {{ $slot }}
</button>
