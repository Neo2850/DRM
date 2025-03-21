<script setup>
import Logo from "@/Pages/ClientSide/assets/DRMLogo.png";
</script>

<template>
  <!-- Footer -->
  <footer class="footer-color text-white pt-12 pb-6">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <div>
          <h3 class="text-lg font-bold mb-4"><img :src="Logo" alt="DRM Logo"></h3>
          <p class="text-gray-400 mb-2">Customer Support:</p>
          <p class="text-gray-400">(629) 555-0129</p>
          <p class="text-gray-400 mb-4">Phone & Support: 9am-6pm Caloocan City</p>
          <p class="text-gray-400">drm.roofs@gmail.com</p>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">TOP CATEGORY</h3>
          <ul class="space-y-2">
            <li>
              <Link href="#" class="text-gray-400 hover:text-white"
                >Wirings</Link
              >
            </li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white">Plumbing</Link>
            </li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white">Hardware</Link>
            </li>
            <li><Link href="#" class="text-gray-400 hover:text-white">Locks</Link></li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">QUICK LINKS</h3>
          <ul class="space-y-2">
            <li>
              <Link href="#" class="text-gray-400 hover:text-white"
                >DRM Shopee</Link
              >
            </li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white"
                >DRM Lazada</Link
              >
            </li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white">Privacy Policy</Link>
            </li>
            <li><Link href="#" class="text-gray-400 hover:text-white">FAQ</Link></li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white">Contact</Link>
            </li>
            <li>
              <Link href="#" class="text-gray-400 hover:text-white">About Us</Link>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-navy-800 pt-6">
        <p class="text-center text-gray-400">DRM © 2025</p>
      </div>
    </div>
  </footer>
</template>
