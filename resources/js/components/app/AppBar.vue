  
<template>
    <div v-if="$store.state.auth.user">
        <v-navigation-drawer
            v-model="drawer"
            :mini="mini"
            :expand-on-hover="miniOnHover"
            :permanent="permanent"
            :temporary="temporary"
            stateless
            app
            hide-overlay
        >
            <v-list-item class="drawer-action accent" dark>
                <v-list-item-icon class="hidden-xs-only" style="cursor: pointer;" @click="$router.push('/');">
                    <v-avatar tile style="margin: -8px 0px 0px -10px;" size="42">
                        <img src="/img/boaty_boat_white.png" />
                    </v-avatar>
                </v-list-item-icon>
                <v-list-item-content style="cursor: pointer;" @click="$router.push('/');">
                    <v-list-item-title>
                        <b style="margin-top: 120px; !important">Boaty</b>
                    </v-list-item-title>
                </v-list-item-content>
                <v-list-item-icon
                    class="hidden-sm-and-up"
                    style="margin-left: 6px; cursor: pointer;"
                    @click="drawer = false"
                >
                    <v-icon>fas fa-times</v-icon>
                </v-list-item-icon>
            </v-list-item>
            <!-- MENU -->
            <v-list dense class="drawer-routes">
                <div v-for="(route, i) in routes" :key="i">
                    <v-list-group v-if="route.type == 'submenu'" :prepend-icon="route.icon ? route.icon : null">
                        <template v-slot:activator>
                            <v-list-item-title>{{ route.name }}</v-list-item-title>
                        </template>
                        <div v-for="(url, j) in route.routes" :key="j">
                            <v-list-item
                                @click="$vuetify.breakpoint.xsOnly ? (drawer = false) : (drawer = true)"
                                :to="url.url"
                                color="primary"
                                link
                            >
                                <v-list-item-icon>
                                    <v-icon>{{ url.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        {{ url.name }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </div>
                    </v-list-group>
                    <v-list-item
                        @click="$vuetify.breakpoint.xsOnly ? (drawer = false) : (drawer = true)"
                        :to="route.url"
                        v-if="route.type == 'route'"
                        color="primary"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>{{ route.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ route.name }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>
            </v-list>
            <template v-slot:append>
                <v-btn
                    to="/dev"
                    block
                    text
                    tile
                >Dev</v-btn>
            </template>
        </v-navigation-drawer>

        <v-app-bar color="accent" dark flat app fixed>
            <v-btn class="hidden-sm-and-up" icon @click="drawer = true">
                <v-icon>fas fa-bars</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-avatar
                color="secondary"
                style="cursor: pointer;"
                size="45"
                v-on="on"
                v-if="$store.state.auth.user != null"
                @click="$router.push('/perfil');"
            >
                <img
                    v-if="$store.state.auth.user.photo != null"
                    :src="$store.state.auth.user.photo"
                    width="100%"
                    height="auto"
                />
                <span
                    v-else-if="$store.state.auth.user.first_name"
                    class="text-uppercase white--text"
                    >{{ $store.state.auth.user.first_name[0] }}</span
                >
            </v-avatar>
        </v-app-bar>
    </div>
</template>

<script>
import menu from "./menu.json";

export default {
    data: () => ({
        mini: true,
        miniOnHover: true,
        drawer: false,
        permanent: false,
        temporary: true,
        routes: menu,
        searchDialog: false
    }),

    computed: {
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        },
    },

    watch: {
        isMobile() {
            this.drawerControl();
        }
    },

    mounted() {
        this.drawerControl();
    },

    methods: {
        drawerControl() {
            if (this.isMobile) {
                this.drawer = false;
                this.permanet = false;
                this.temporary = true;
                this.mini = false;
                this.miniOnHover = false;
            } else {
                this.drawer = true;
                this.permanet = true;
                this.temporary = false;
                this.mini = true;
                this.miniOnHover = true;
            }
        },
    }
};
</script>

<style lang="scss">
.drawer-action {
    height: 64px;
}

@media (max-width: 960px) {
    .drawer-action {
        height: 56px;
    }
}
</style>