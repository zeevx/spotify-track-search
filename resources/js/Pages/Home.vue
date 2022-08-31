<template>
  <div class="p-8 md:p-12 lg:px-16 lg:py-24">

    <!-- header -->
    <div class="max-w-lg mx-auto text-center">
      <img alt="spotify" class="mx-auto p-1 bg-white border rounded max-w-sm" src="/images/spotify.png">
      <p class="hidden text-gray-500 sm:mt-4 sm:block">
        Search for track via Spotify APIs
      </p>
      <div v-if="submitted">
        <p v-if="status" class="font-bold text-green-600 p-2">{{ message }}</p>
        <p v-else class="font-bold text-red-600 p-2">{{ message }}</p>
      </div>
    </div>

    <!-- searchBox -->
    <div id="tracks" class="max-w-xl mx-auto my-10">
      <div class="sm:gap-4 sm:flex">
        <div class="sm:flex-1">
          <label class="sr-only" for="track">Track</label>
          <input
              v-model="track"
              class="w-full p-3 text-green-600 bg-white border-2 border-green-600 transition rounded-md shadow-lg focus:ring focus:outline-none focus:ring-green-100"
              placeholder="Search Track"
              type="text"
          />
        </div>
        <button
            class="flex items-center justify-center w-full px-5 py-3 mt-4 text-white transition rounded-md bg-green-600 sm:mt-0 sm:w-auto group focus:outline-none focus:ring-green-100"
            type="submit"
            v-on:click="search(offset)"
        >
          <span class="text-sm font-medium"> Search </span>
          <svg
              class="w-5 h-5 ml-3"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
          >
            <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- loader -->
    <div v-if="loading" class="flex justify-center">
      <div class="lds-facebook">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>

    <!-- searchResults -->
    <div v-if="submitted && status">
      <div class="grid md:grid-cols-4 gap-3">
        <div v-for="result in tracks.items" :key="result.id">
          <div class="flex justify-center">
            <a
                :href="result.external_urls.spotify"
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

      <div class="flex justify-end flex-wrap">
        <span v-if="tracks.previous" class="cursor-pointer font-bold px-2 text-2xl hover:text-green-600"
              v-on:click="previousPage">Previous</span>
        <span v-if="tracks.next" class="cursor-pointer font-bold px-2 text-2xl hover:text-green-600"
              v-on:click="nextPage">Next</span>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Home",
  data() {
    return {
      status: false,
      message: null,
      track: null,
      submitted: false,
      tracks: null,
      loading: false,
      offset: 0,
      tracksPerPage: 20
    }
  },
  methods: {
    async search(offset) {
      this.loading = true
      this.status = false
      let result;
      const data = {
        track: this.track,
        offset,
        _token: this.$page.props.csrf_token,
      }
      await axios.post('/search', data)
          .then(response => {
            result = response.data
            this.tracks = result.data.tracks
            this.status = result.status
            this.message = result.message
          })
          .catch(error => {
            result = error.response.data
            this.status = result.status
            this.message = result.message
          })
      this.loading = false;
      this.submitted = true;
    },
    nextPage() {
      this.offset += this.tracksPerPage
      this.search(this.offset)
      document.getElementById('tracks').scrollIntoView();
    },
    previousPage() {
      this.offset -= this.tracksPerPage
      this.search(this.offset)
      document.getElementById('tracks').scrollIntoView();
    }
  }
}
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
