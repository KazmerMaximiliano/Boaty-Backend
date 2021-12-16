<template>
  <div>
    <v-app>
      <AppBar></AppBar>
      <v-main>
        <v-container :class="currentRoute == 'home' ? 'no-container' : ''">
          <v-row justify="center" v-if="process">
            <v-progress-circular
              :size="70"
              :width="7"
              color="primary"
              indeterminate
              style="margin-top: 250px;"
            ></v-progress-circular>
          </v-row>
          <div v-else class="scroll-fix-height">
            <Errors></Errors>
            <router-view></router-view>
          </div>
        </v-container>
      </v-main>
    </v-app>
  </div>
</template>

<script>
import AppBar from './components/app/AppBar'
import Errors from './components/app/Errors'

export default {
  data: () => ({
    process: false
  }),

  components: {
    AppBar,
    Errors
  },

  computed: {
    currentRoute() {
      return this.$route.name
    }
  },

  mounted() {
    if (JSON.parse(window.localStorage.getItem('logged'))) {
      this.recoverSession()
    }
  },

  methods: {
    recoverSession() {
      this.process = true
      this.$store
        .dispatch('auth/user')
        .then(response => {
          this.$user.set({
            rol: response.roles[0],
            permissions: ['authenticated']
          })
          this.process = false
        })
        .catch(() => {
          this.process = false
        })
    }
  }
}
</script>

<style lang="scss">
@font-face {
  font-family: 'Campton-Medium';
  src: url('../../public/fonts/Campton-Medium.otf') format('opentype');
}

@font-face {
  font-family: 'Campton-Bold';
  src: url('../../public/fonts/Campton-Bold.otf') format('opentype');
}

$font-family: 'Campton-Medium';

.v-application {
  [class*='text-'] {
    color: #36405a;
    font-family: $font-family, sans-serif !important;
  }
  font-family: $font-family, sans-serif !important;
  background-color: #ebebeb !important;
}

body::-webkit-scrollbar {
  width: 6px;
}

body::-webkit-scrollbar-thumb {
  background-color: #ebebeb;
}

.scroll-fix-height {
  min-height: 101%;
}

.no-container {
  padding: 0px !important;
}

@media (min-width: 960px) {
  .no-container {
    max-width: 100% !important;
  }
}
</style>
