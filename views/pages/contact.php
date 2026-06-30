<div class="container" style="padding-top:64px;padding-bottom:96px;max-width:700px;">
    <h1 style="font-size:2.8rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 8px;">Contact Us</h1>
    <p style="font-size:1.1rem;color:var(--text-secondary);line-height:1.7;margin-bottom:48px;">
        Have questions, suggestions, or feedback? We'd love to hear from you. Send us a message and we'll get back to you as soon as possible.
    </p>

    <div class="card" style="padding:40px;">
        <form id="contactForm" onsubmit="return handleContact(event)">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label">Subject *</label>
                <input type="text" name="subject" class="form-input" required>
            </div>
            <div style="margin-bottom:24px;">
                <label class="form-label">Message *</label>
                <textarea name="message" class="form-textarea" rows="6" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
            <p id="contactMessage" style="font-size:14px;margin-top:12px;color:var(--text-muted);"></p>
        </form>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;margin-top:48px;">
        <div class="card" style="text-align:center;padding:24px;">
            <div style="font-size:28px;margin-bottom:8px;">📧</div>
            <h3 style="font-size:14px;margin:0 0 4px;">Email</h3>
            <p style="font-size:14px;color:var(--text-muted);margin:0;">contact@pfsrv.com</p>
        </div>
        <div class="card" style="text-align:center;padding:24px;">
            <div style="font-size:28px;margin-bottom:8px;">⏱️</div>
            <h3 style="font-size:14px;margin:0 0 4px;">Response Time</h3>
            <p style="font-size:14px;color:var(--text-muted);margin:0;">Within 24 hours</p>
        </div>
        <div class="card" style="text-align:center;padding:24px;">
            <div style="font-size:28px;margin-bottom:8px;">🌍</div>
            <h3 style="font-size:14px;margin:0 0 4px;">Location</h3>
            <p style="font-size:14px;color:var(--text-muted);margin:0;">Remote · Global</p>
        </div>
    </div>
</div>

<script>
function handleContact(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    const msg = document.getElementById('contactMessage');
    btn.disabled = true;
    btn.textContent = 'Sending...';
    msg.textContent = 'Thank you for your message! We will get back to you soon.';
    msg.style.color = 'var(--success)';
    form.reset();
    btn.disabled = false;
    btn.textContent = 'Send Message';
    return false;
}
</script>
