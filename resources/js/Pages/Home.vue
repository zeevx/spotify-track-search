<template>
  <div class="p-8 md:p-12 lg:px-16 lg:py-24">

    <!-- header -->
    <div class="max-w-lg mx-auto text-center">
      <img alt="spotify" class="mx-auto p-1 bg-white border rounded max-w-sm" src="/images/spotify.png">
      <p class="hidden text-gray-500 sm:mt-4 sm:block">
        Search for track via Spotify APIs
      </p>
    </div>

    <!-- searchBox -->
      <SearchBar
          @searched="search"
      />

    <!-- loader -->
    <div v-if="loading" class="flex justify-center">
      <div class="lds-facebook">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div v-if="tracks">
      <div class="grid md:grid-cols-4 gap-3">
        <div v-for="result in tracks">
            <div class="flex justify-center">
                <a
                    href="#"
                    class="my-6 p-4 rounded-lg shadow-sm shadow-green-100"
                >
                    <img
                        :alt="result.name"
                        :src="result.album.images.pop().url"
                        class="object-cover w-full h-48 rounded-md"
                    />

                    <div class="mt-2">
                        <dl>
                            <div>
                                <dd class="text-sm text-gray-500">
                                    {{ result.artists.map(x => x.name).join(", ") }}
                                </dd>
                            </div>

                            <div>
                                <dd class="font-medium">
                                    {{ result.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";
import SearchBar from "../Components/SearchBar.vue";

const loading = ref(false)
const props = defineProps({
    tracks: {
        type: Array,
    }
})
let search = _.throttle(function (term) {
    Inertia.post(
        "/",
        { search: term },
        {
            onStart: (visit) => loading.value = true,
            onFinish: (visit) => loading.value = false,
            preserveState: true,
        }
    );
}, 300);
</script>

<style scoped>
.lds-facebook {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}

.lds-facebook div {
  display: inline-block;
  position: absolute;
  left: 8px;
  width: 16px;
  background: #00d95b;
  animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
}

.lds-facebook div:nth-child(1) {
  left: 8px;
  animation-delay: -0.24s;
}

.lds-facebook div:nth-child(2) {
  left: 32px;
  animation-delay: -0.12s;
}

.lds-facebook div:nth-child(3) {
  left: 56px;
  animation-delay: 0;
}

@keyframes lds-facebook {
  0% {
    top: 8px;
    height: 64px;
  }
  50%, 100% {
    top: 24px;
    height: 32px;
  }
}
</style>
