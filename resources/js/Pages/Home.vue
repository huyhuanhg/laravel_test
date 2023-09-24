<template>
    <div>
        Welcome Home!
        <Link href="/test">Test</Link>
    </div>
</template>

<script>
import { defineComponent,  } from 'vue'
import { Link } from '@inertiajs/vue3'

export default defineComponent({
  name: 'Test',
  components: {
    Link
  },
  setup () {
  }
})
</script>
