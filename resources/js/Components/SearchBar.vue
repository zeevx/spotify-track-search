<template>
    <div id="tracks" class="max-w-xl mx-auto my-10">
        <div class="sm:gap-4 sm:flex">
            <div class="sm:flex-1">
                <label class="sr-only" for="track">Track</label>
                <input
                    v-model="query"
                    class="w-full p-3 text-green-600 bg-white border-2 border-green-600 transition rounded-md shadow-lg focus:ring focus:outline-none focus:ring-green-100"
                    placeholder="Search Track"
                    type="text"
                    @input="$emit('searched', query)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

const emit = defineEmits(["searched"]);
const query = ref();
</script>
