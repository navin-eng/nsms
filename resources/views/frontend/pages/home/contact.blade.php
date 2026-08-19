<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Get In Touch</span>
            <h2 class="section-title mt-2">Contact Us</h2>
            <div class="section-divider center"></div>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-info-card">
                    <h4>Contact Information</h4>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Address</div>
                            Dipendra Chowk, Itahari-2, Sunsari, Nepal
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Phone</div>
                            <a href="tel:025586701">025-586701</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-envelope"></i></div>
                        <div class="info-text">
                            <div class="label">Email</div>
                            <a href="mailto:info@gplc.edu.np">info@gplc.edu.np</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fab fa-whatsapp"></i></div>
                        <div class="info-text">
                            <div class="label">WhatsApp</div>
                            <a href="https://wa.me/9812355717" target="_blank">+977 9812355717</a>
                        </div>
                    </div>
                    <div class="social-row">
                        <a href="https://www.facebook.com/GplcIth" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="https://wa.me/9812355717" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="gplc-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3565.63095380432!2d87.27117921420957!3d26.66029607733071!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ef6c6f77f068db%3A0x5459ed0af4c0ffc2!2sGreen%20Peace%20Linclon%20College!5e0!3m2!1sen!2snp!4v1671550315569!5m2!1sen!2snp"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-form-card">
                    <h4>Send Message</h4>
                    <form action="{{ route('message.send') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Your Name" class="gplc-input" required>
                        <input type="email" name="email" placeholder="Email Address" class="gplc-input" required>
                        <input type="tel" name="phone" placeholder="Phone Number" class="gplc-input">
                        <input type="text" name="address" placeholder="Your Address" class="gplc-input">
                        <textarea name="desc" placeholder="Write your message..." class="gplc-input"></textarea>
                        <button type="submit" class="btn-gplc w-100 justify-content-center">
                            <i class="fa-solid fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
