<?php 
include_once('../includes/header.php');
?>

<!-- <body class="bg-[#F8F9FA] font-sans antialiased min-h-screen pt-20"> -->
    <div class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1 class="font-serif font-bold text-4xl md:text-5xl text-[#0F172A] mb-4">Contact Us</h1>
            <p class="text-base text-[#566473] max-w-2xl mx-auto">
                Have questions? We're here to help you on your learning journey. Our support team is ready to assist you with any inquiries about our courses, enrollment, or anything else you need to know.
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 bg-white rounded-3xl p-10 border border-gray-100 shadow-sm">
                <form action="#" method="POST" class="space-y-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="first-name" class="block text-sm font-bold text-slate-800">First Name</label>
                                <input type="text" id="first-name" name="first-name" 
                                    class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200"
                                    placeholder="John">
                            </div>
                            <div class="space-y-2">
                                <label for="last-name" class="block text-sm font-bold text-slate-800">Last Name</label>
                                <input type="text" id="last-name" name="last-name" 
                                    class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200"
                                    placeholder="Doe">
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-slate-800">Email Address</label>
                            <input type="email" id="email" name="email" 
                                class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200"
                                placeholder="your.email@example.com">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-bold text-slate-800">Phone Number (Optional)</label>
                            <input type="tel" id="phone" name="phone" 
                                class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200"
                                placeholder="+1 (555) 000-0000">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="subject" class="block text-sm font-bold text-slate-800">Subject</label>
                            <select id="subject" name="subject" 
                                class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200">
                                <option value="">Select a subject</option>
                                <option value="course-info">Course Information</option>
                                <option value="pricing">Pricing & Fees</option>
                                <option value="enrollment">Enrollment Process</option>
                                <option value="technical">Technical Support</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="feedback">Feedback & Suggestions</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="message" class="block text-sm font-bold text-slate-800">Message</label>
                            <textarea id="message" name="message" rows="6" 
                                class="w-full bg-white border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200 resize-none"
                                placeholder="How can we help you today?"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="privacy" name="privacy" class="w-5 h-5 text-brandOrange border-gray-300 rounded focus:ring-brandOrange focus:ring-2">
                        <label for="privacy" class="text-sm text-gray-600">
                            I agree to the <a href="#" class="text-brandOrange hover:text-brandOrangeHover font-medium">Privacy Policy</a> and <a href="#" class="text-brandOrange hover:text-brandOrangeHover font-medium">Terms of Service</a>
                        </label>
                    </div>
                    
                    <button type="submit" 
                        class="w-full bg-[#FF8A00] hover:bg-[#E07A00] text-white font-bold text-sm py-4 px-6 rounded-xl shadow-[0_4px_12px_rgba(255,138,0,0.2)] transition-all duration-200 transform active:scale-[0.98]
                        flex items-center justify-center gap-2">
                        <span>Send Message</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
            
            <div class="lg:col-span-5 lg:pl-8 space-y-8">
                <div>
                    <h2 class="text-xl font-bold text-[#0F172A] mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-[#FFEAD6] rounded-xl flex items-center justify-center text-[#A87034]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-[#0F172A] mb-1">Email Us</h3>
                                <p class="text-sm text-[#566473] font-medium">support@accessedu.com</p>
                                <p class="text-xs text-gray-400 mt-1">We typically respond within 24 hours</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-[#FFEAD6] rounded-xl flex items-center justify-center text-[#A87034]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-[#0F172A] mb-1">Call Us</h3>
                                <p class="text-sm text-[#566473] font-medium">+1 (555) 000-0000</p>
                                <p class="text-xs text-gray-400 mt-1">Mon-Fri 9am-5pm EST</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-[#FFEAD6] rounded-xl flex items-center justify-center text-[#A87034]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-[#0F172A] mb-1">Visit Us</h3>
                                <p class="text-sm text-[#566473] font-medium">123 Learning Way, EdTech City</p>
                                <p class="text-xs text-gray-400 mt-1">Building A, Suite 200</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#F8F9FA] rounded-2xl p-6 border border-gray-100">
                    <h3 class="font-bold text-[#0F172A] mb-4">Why Contact Us?</h3>
                    <ul class="space-y-3 text-sm text-[#566473]">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-brandOrange mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Course information and curriculum details
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-brandOrange mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Enrollment assistance and payment options
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-brandOrange mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Technical support for learning platform
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-brandOrange mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Partnership and business inquiries
                        </li>
                    </ul>
                </div>
                
                <div class="bg-[#FFEAD6] rounded-2xl p-6 border border-orange-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-brandOrange rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0L7 14m5-10l5 2M7 14a2 2 0 11-4 0 2 2 0 014 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0F172A] mb-1">Quick Response Guaranteed</h3>
                            <p class="text-sm text-[#566473]">We prioritize responding to all inquiries within 24 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Here you would typically send the data to your backend
            console.log('Form submitted:', data);
            
            // Show success message
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '
                <span>Sending...</span>
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 0110 10h2a8 8 0 00-16 0A10 10 0 0112 2zm0 18a8 8 0 01-8-8 8 8 0 018-8v2a6 6 0 100 12 6 6 0 000-12V4z"></path>
                </svg>
            ';
            submitButton.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50';
                successMessage.innerHTML = '
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Message Sent!</p>
                            <p class="text-sm">Thank you for contacting us. We\'ll get back to you soon.</p>
                        </div>
                    </div>
                ';
                document.body.appendChild(successMessage);
                
                setTimeout(() => {
                    successMessage.remove();
                }, 5000);
                
                // Reset form
                this.reset();
            }, 2000);
        });
        
        // Input focus effects
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });
    </script>
</body>
</html>
<?php 
include_once('../includes/footer.php');
?>