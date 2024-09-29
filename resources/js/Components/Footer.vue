<!-- Footer.vue -->
<template>
    <footer class="bg-white text-sky-800 py-12 font-sans">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Company Info -->
          <div class="space-y-4">
            <h3 class="text-2xl font-bold text-sky-600">{{ appName }}</h3>
            <p class="text-sky-700">Innovating for a brighter tomorrow.</p>
            <div class="flex space-x-4">
              <a v-for="(icon, name) in socialIcons" :key="name" href="#" class="text-sky-500 hover:text-sky-600 transition-colors">
                <component :is="icon" class="w-6 h-6" />
              </a>
            </div>
          </div>
  
          <!-- Quick Links -->
          <div>
            <h4 class="text-lg font-semibold mb-4 text-sky-600">Quick Links</h4>
            <ul class="space-y-2">
              <li v-for="link in quickLinks" :key="link">
                <inertia-link :href="route(link.toLowerCase())" class="text-sky-700 hover:text-sky-500 transition-colors">
                  {{ link }}
                </inertia-link>
              </li>
            </ul>
          </div>
  
          <!-- Contact Info -->
          <div>
            <h4 class="text-lg font-semibold mb-4 text-sky-600">Contact Us</h4>
            <ul class="space-y-2">
              <li v-for="(info, index) in contactInfo" :key="index" class="flex items-center">
                <component :is="info.icon" class="w-5 h-5 mr-2 text-sky-500" />
                <a v-if="info.link" :href="info.link" class="text-sky-700 hover:text-sky-500 transition-colors">
                  {{ info.text }}
                </a>
                <span v-else class="text-sky-700">{{ info.text }}</span>
              </li>
            </ul>
          </div>
  
          <!-- Newsletter Signup -->
          <div>
            <h4 class="text-lg font-semibold mb-4 text-sky-600">Stay Updated</h4>
            <p class="text-sky-700 mb-4">Subscribe to our newsletter for the latest updates.</p>
            <form @submit.prevent="subscribeNewsletter" class="flex">
              <input
                v-model="email"
                type="email"
                placeholder="Enter your email"
                class="flex-grow px-4 py-2 rounded-l-md border-2 border-sky-300 focus:outline-none focus:border-sky-500"
              />
              <button
                type="submit"
                class="bg-sky-500 text-white px-4 py-2 rounded-r-md hover:bg-sky-600 transition-colors"
              >
                Subscribe
              </button>
            </form>
            <p v-if="subscriptionMessage" class="mt-2 text-sm" :class="subscriptionMessage.type">
              {{ subscriptionMessage.text }}
            </p>
          </div>
        </div>
  
        <!-- Bottom Bar -->
        <div class="mt-12 pt-8 border-t border-sky-200 flex flex-col sm:flex-row justify-between items-center">
          <p class="text-sky-600">&copy; {{ new Date().getFullYear() }} {{ appName }}. All rights reserved.</p>
          <div class="mt-4 sm:mt-0">
            <inertia-link :href="route('policy.show')" class="text-sky-600 hover:text-sky-500 transition-colors mr-4">Privacy Policy</inertia-link>
            <inertia-link :href="route('terms.show')" class="text-sky-600 hover:text-sky-500 transition-colors">Terms of Service</inertia-link>
          </div>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { usePage } from '@inertiajs/vue3'
  import { Facebook, Twitter, Instagram, Linkedin, Mail, Phone, MapPin } from 'lucide-vue-next'
  
  export default {
    setup() {
      const page = usePage()
      const appName = page.props.app.name
      const email = ref('')
      const subscriptionMessage = ref(null)
  
      const socialIcons = {
        Facebook,
        Twitter,
        Instagram,
        Linkedin
      }
  
      const quickLinks = ['Home', 'About', 'Services', 'Blog', 'Contact']
  
      const contactInfo = [
        { icon: Mail, text: 'info@yourcompany.com', link: 'mailto:info@yourcompany.com' },
        { icon: Phone, text: '+1 (234) 567-890', link: 'tel:+1234567890' },
        { icon: MapPin, text: '123 Innovation Street, Tech City, TC 12345' }
      ]
  
      const subscribeNewsletter = async () => {
        try {
          // Here you would typically make an API call to your backend
          // For demonstration, we'll just simulate a successful subscription
          await new Promise(resolve => setTimeout(resolve, 1000))
          subscriptionMessage.value = { type: 'text-green-500', text: 'Successfully subscribed!' }
          email.value = ''
        } catch (error) {
          subscriptionMessage.value = { type: 'text-red-500', text: 'An error occurred. Please try again.' }
        }
      }
  
      return {
        appName,
        socialIcons,
        quickLinks,
        contactInfo,
        email,
        subscriptionMessage,
        subscribeNewsletter
      }
    }
  }
  </script>