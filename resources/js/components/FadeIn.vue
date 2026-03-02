<template>
  <div
    ref="domRef"
    class="transition-all duration-1000 ease-out"
    :class="isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
    :style="{ transitionDelay: `${delay}ms` }"
  >
    <slot></slot>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  delay: {
    type: Number,
    default: 0
  }
});

const isVisible = ref(false);
const domRef = ref(null);

let observer = null;

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      if (entries[0].isIntersecting) {
        isVisible.value = true;
        if (domRef.value) observer.unobserve(domRef.value);
      }
    },
    { threshold: 0.1 }
  );

  if (domRef.value) observer.observe(domRef.value);
});

onUnmounted(() => {
  if (observer && domRef.value) {
    observer.unobserve(domRef.value);
  }
});
</script>
