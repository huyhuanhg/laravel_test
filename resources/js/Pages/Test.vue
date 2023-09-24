<template>
    <div>
        Welcome Test!
        <Link href="/">Home</Link>

        <form @submit.prevent="submit">
            <label for="first_name">First name:</label>
            <input
                id="first_name"
                v-model="form.first_name"
            />
            <label for="last_name">Last name:</label>
            <input
                id="last_name"
                v-model="form.last_name"
            />
            <button type="submit">Submit</button>
        </form>
    </div>
</template>

<script>
import { defineComponent, reactive } from "vue";
import { Link, router } from "@inertiajs/vue3";

export default defineComponent({
    name: "Test",
    components: {
        Link,
    },
    setup() {
        const form = reactive({
          first_name: null,
          last_name: null,
        });

        const submit = () => {
            router.post("/login", form);
        };

        return {
            form,
            submit,
        };
    },
});
</script>
