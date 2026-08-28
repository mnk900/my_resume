<section id="contact" class="py-24 px-6 relative bg-[#0B0F17] border-t border-white/5">
    <div class="max-w-4xl mx-auto rounded-3xl bg-gradient-to-b from-[#121826] to-[#070A10] p-8 md:p-14 border border-white/10 shadow-2xl" data-aos="zoom-in">
        <div class="text-center space-y-4 mb-10">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400">Initiate Dialogue</h2>
            <h3 class="text-3xl md:text-5xl font-heading font-bold text-white">Let’s Collaborate</h3>
            <p class="text-slate-400 font-light text-sm max-w-md mx-auto">Available for executive advisory, consulting, and enterprise architecture.</p>
        </div>

        <form action="{{ route('portfolio.contact.store', $portfolio->id ?? $user->portfolio->id ?? 1) }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="text" name="name" placeholder="Your Name" required class="w-full px-5 py-4 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-colors">
                <input type="email" name="email" placeholder="Your Email" required class="w-full px-5 py-4 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-colors">
            </div>
            <textarea name="message" rows="4" placeholder="Your Message" required class="w-full px-5 py-4 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold transition-all shadow-lg shadow-indigo-500/25">Send Executive Inquiry</button>
        </form>
    </div>
</section>
