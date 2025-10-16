<template>
  <div v-if="links.length > 3" class="flex items-center justify-center space-x-2">
    <template v-for="(link, key) in links" :key="key">
      <button
        v-if="link.url === null"
        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
        disabled
        v-html="link.label"
      />
      <a
        v-else
        :href="link.url"
        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
        :class="{
          'bg-primary text-primary-foreground hover:bg-primary/90': link.active,
        }"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
defineProps({
  links: {
    type: Array,
    required: true,
  },
})
</script> 