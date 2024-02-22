import { createStore } from "vuex";

export default createStore({
    state: {
        isSidebarOpen: JSON.parse(localStorage.getItem('isSidebarOpen')) || false,
        showTextWithIcon: JSON.parse(localStorage.getItem('showTextWithIcon')) || false,
    },
    mutations: {
        toggleSidebar(state) {
            state.isSidebarOpen = !state.isSidebarOpen;
            localStorage.setItem('isSidebarOpen', state.isSidebarOpen);
        },
        toggleShowTextWithIcon(state) {
            state.showTextWithIcon = !state.showTextWithIcon;
            localStorage.setItem('showTextWithIcon', state.showTextWithIcon);
        },
    },
    actions: {
        asyncToggleSidebar({ commit }) {
            commit("toggleSidebar");
        },
        asyncToggleShowTextWithIcon({ commit }) {
            commit("toggleShowTextWithIcon");
        },
    },
    getters: {
        isOpen: (state) => state.isSidebarOpen,
        showTextWithIcon: (state) => state.showTextWithIcon,
    },
})
