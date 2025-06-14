<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#149d80] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#0c8b71] focus:bg-[#0c8b71] active:bg-[#004530] focus:outline-none focus:ring-2 focus:ring-[#149d80] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg']) }}>
    {{ $slot }}
</button>
