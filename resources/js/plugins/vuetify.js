import "@fortawesome/fontawesome-free/css/all.css";
import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import es from "vuetify/es5/locale/es";

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        dark: false,
        themes: {
            light: {
                primary: "#0074c0",
                secondary: "#ff6f00",
                accent: "#00a9e7",
                secondary_accent: "#ffa200",
                error: "#F44336",
                info: "#00BCD4",
                success: "#4CAF50",
                warning: "#FFC107",
                disabled: "#ebebeb"
            },
            dark: {
                primary: "#0074c0",
                secondary: "#ff6f00",
                accent: "#00a9e7",
                secondary_accent: "#ffa200",
                error: "#F44336",
                info: "#00BCD4",
                success: "#4CAF50",
                warning: "#FFC107",
                disabled: "#ebebeb"
            }
        }
    },
    options: {
        customProperties: true
    },
    icons: {
        iconfont: "fa"
    },
    lang: {
        locales: { es },
        current: "es"
    }
});