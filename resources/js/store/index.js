import Vue from "vue";
import Vuex from "vuex";

import modules from "./modules";

import { process } from "./subscribers/process";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        inProcess: false,
    },

    mutations: {
        iterateProcess(state, value) {
            state.inProcess = value;
        },
    },

    actions: {},

    modules: modules,

    plugins: [process]
});